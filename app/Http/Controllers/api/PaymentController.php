<?php

namespace App\Http\Controllers\api;

use App\BakongTransaction;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Categories;
use App\Models\Customers;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Role;
use App\Models\User;
use App\Service\BakongService;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected $bakongService;

    public function __construct(BakongService $bakongService)
    {
        $this->bakongService = $bakongService;
    }

    public function index()
    {
        $payments = Payments::with(['Orders', 'Customers', 'Products'])->latest()->get();
        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payments::with(['Orders', 'Customers', 'Products'])->findOrFail($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'receiver_phone' => 'nullable|string|max:50',
            'receiver_location' => 'nullable|string|max:255',
            'transfer_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('transfer_image')) {
            $validated['transfer_image'] = $request->file('transfer_image')->store('payments', 'public');
        }

        $payment = Payments::create($validated);

        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $payment = Payments::findOrFail($id);

        $validated = $request->validate([
            'receiver_phone' => 'sometimes|nullable|string|max:50',
            'receiver_location' => 'sometimes|nullable|string|max:255',
            'order_id' => 'sometimes|integer|exists:orders,id',
            'transfer_image' => 'sometimes|nullable|image|max:5120',
        ]);

        if ($request->hasFile('transfer_image')) {
            if ($payment->transfer_image) {
                Storage::disk('public')->delete($payment->transfer_image);
            }
            $validated['transfer_image'] = $request->file('transfer_image')->store('payments', 'public');
        }

        $payment->update($validated);

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payments::findOrFail($id);

        if ($payment->transfer_image) {
            Storage::disk('public')->delete($payment->transfer_image);
        }

        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    public function createBakongPayment(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
            'product_id' => 'sometimes|integer|exists:products,id',
            'amount' => 'sometimes|numeric',
            'currency' => 'sometimes|string',
            'receiver_phone' => 'sometimes|nullable|string|max:50',
            'receiver_location' => 'sometimes|nullable|string|max:255',
            'customer_name' => 'sometimes|nullable|string|max:255',
            'customer_email' => 'sometimes|nullable|email|max:255',
            'customer_phone' => 'sometimes|nullable|string|max:50',
            'customer_address' => 'sometimes|nullable|string|max:255',
            'account_name' => 'required|string|max:255',
        ]);

        $product = null;
        if (! empty($data['product_id'])) {
            $product = Products::findOrFail($data['product_id']);
        }

        $amount = $data['amount'] ?? ($product?->price ?? 0);
        $orderId = $this->resolveOrderId($data['order_id'], $amount);

        $payload = [
            'order_id' => $orderId,
            'product_id' => $product?->id,
            'category_id' => $product?->category_id,
            'amount' => $amount,
            'currency' => $data['currency'] ?? config('Bakong.currency', env('BAKONG_CURRENCY', 'USD')),
            'receiver_phone' => $data['receiver_phone'] ?? '',
            'receiver_location' => $data['receiver_location'] ?? '',
            'customer_name' => $data['customer_name'] ?? null,
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'account_name' => $data['account_name'] ?? null,
        ];

        $result = $this->buildBakongResult($payload);
        $payload['status'] = $result['transaction']->status ?? 'pending';
        $payload['transaction_ref'] = $this->extractTransactionReference($result['resp']);
        $saved = $this->persistPaymentAndCustomer($payload);

        // Ensure we return customer with related bank info for the web/API
        $customer = $saved['customer'] instanceof \Illuminate\Database\Eloquent\Model ? $saved['customer']->load('bank') : $saved['customer'];
        $payment = $saved['payment'];
        $bank = $customer && isset($customer->bank) ? $customer->bank : null;

        $bakongResp = $result['resp'] ?? null;
        $qrString = $result['qr_string'] ?? null;
        $errorFlag = is_array($bakongResp) && ! empty($bakongResp['error']);
        $errorMessage = null;
        if (is_array($bakongResp) && isset($bakongResp['message'])) {
            $errorMessage = $bakongResp['message'];
        }

        // If we didn't receive a QR string, treat it as an error for visibility
        if ((empty($qrString) || empty($result['qr_url'])) && empty($errorFlag)) {
            $errorFlag = true;
            $errorMessage = $errorMessage ?? 'The Bakong QR image could not be generated.';
        }

        return response()->json([
            'error' => $errorFlag,
            'message' => $errorMessage,
            'transaction' => $result['transaction'],
            'bakong' => $bakongResp,
            'qr_url' => $result['qr_url'],
            'qr_string' => $qrString,
            'debug' => $bakongResp,
            'payment' => $payment,
            'customer' => $customer,
            'bank' => $bank,
        ]);
    }

    public function webhook(Request $request)
    {
        return $this->handleCallback($request);
    }

    public function handleCallback(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Bakong-Signature') ?: $request->header('X-Signature');

        if (! $this->bakongService->verifyWebhook($payload, $signature)) {
            return response()->json(['message' => 'invalid signature'], 400);
        }

        $data = $request->all();
        $transactionId = $request->input('transaction_id')
            ?? $request->input('payment_id')
            ?? $request->input('transaction_ref')
            ?? $request->input('md5');

        $payment = null;
        if ($transactionId) {
            $payment = Payments::where('transaction_ref', $transactionId)->first();
        }

        if (! $payment && $request->filled('order_id')) {
            $payment = Payments::where('order_id', $request->input('order_id'))->latest()->first();
        }

        if ($payment) {
            $status = strtolower(trim((string) ($request->input('status') ?? $request->input('payment_status') ?? '')));

            if (in_array($status, ['success', 'paid', 'completed', 'settled'], true)) {
                $payment->status = 'paid';
                $payment->save();
                $this->syncOrderStatus(['order_id' => $payment->order_id, 'status' => 'paid']);
            } elseif (in_array($status, ['failed', 'cancelled', 'canceled', 'declined', 'error'], true)) {
                $payment->status = 'failed';
                $payment->save();
                $this->syncOrderStatus(['order_id' => $payment->order_id, 'status' => 'failed']);
            }
        }

        BakongTransaction::create([
            'order_id' => $data['order_id'] ?? null,
            'amount' => $data['amount'] ?? 0,
            'currency' => $data['currency'] ?? null,
            'status' => $data['status'] ?? 'unknown',
            'bakong_response' => $data,
        ]);

        return response()->json(['message' => 'ok']);
    }

    /**
     * Poll Bakong directly for the current status of a payment (frontend calls this repeatedly).
     */
    public function checkPaymentStatus($id)
    {
        $payment = Payments::findOrFail($id);

        // If already resolved, no need to call Bakong again
        if (in_array($payment->status, ['paid', 'failed'], true)) {
            return response()->json([
                'status' => $payment->status,
                'payment' => $payment,
            ]);
        }

        if (empty($payment->transaction_ref)) {
            return response()->json([
                'status' => $payment->status,
                'message' => 'No transaction reference (md5) stored for this payment.',
            ], 422);
        }

        $result = $this->bakongService->checkTransactionStatus($payment->transaction_ref);

        $bakongStatus = strtolower(trim((string) ($result['status'] ?? '')));

        if (in_array($bakongStatus, ['success', 'paid', 'completed', 'settled'], true)) {
            $payment->status = 'paid';
            $payment->save();
            $this->syncOrderStatus(['order_id' => $payment->order_id, 'status' => 'paid']);
        } elseif (in_array($bakongStatus, ['failed', 'cancelled', 'canceled', 'declined', 'error'], true)) {
            $payment->status = 'failed';
            $payment->save();
            $this->syncOrderStatus(['order_id' => $payment->order_id, 'status' => 'failed']);
        }

        return response()->json([
            'status' => $payment->status,
            'bakong' => $result,
            'payment' => $payment,
        ]);
    }

    protected function syncOrderStatus(array $data): void
    {
        if (empty($data['order_id'])) {
            return;
        }

        $orderId = is_numeric($data['order_id']) ? intval($data['order_id']) : null;
        if (! $orderId) {
            return;
        }

        $order = Orders::find($orderId);
        if (! $order) {
            return;
        }

        $status = strtolower(trim((string) ($data['status'] ?? '')));
        if (in_array($status, ['paid', 'success', 'completed', 'settled'], true)) {
            $order->status = 'paid';
        } elseif (in_array($status, ['failed', 'cancelled', 'canceled', 'declined', 'error'], true)) {
            $order->status = 'failed';
        }

        if ($order->isDirty('status')) {
            $order->save();
        }
    }

    protected function buildBakongResult(array $payload): array
    {
        // NOTE: this uses your real BakongService::createPayment(), which returns:
        // ['error' => bool, 'status' => 200, 'data' => ['qr' => ..., 'md5' => ...]]
        // or ['error' => true, 'message' => '...'] on failure.
        $resp = $this->bakongService->createPayment($payload);

        $transaction = BakongTransaction::create([
            'order_id' => $payload['order_id'],
            'amount' => $payload['amount'],
            'currency' => $payload['currency'],
            'status' => (is_array($resp) && empty($resp['error'])) ? 'pending' : 'failed',
            'bakong_response' => $resp,
        ]);

        if (is_array($resp) && empty($resp['error'])) {
            $message = sprintf(
                '<b>Bakong Payment Ready</b>%sOrder ID: %s%sAmount: %s %s%sStatus: %s',
                "\n",
                $payload['order_id'],
                "\n",
                number_format($payload['amount'], 2),
                $payload['currency'],
                "\n",
                'pending'
            );
            $this->bakongService->sendTelegramNotification($message);
        }

        $qrString = $this->extractQrString($resp);
        $qrUrl = $this->buildQrUrl($resp, $payload);

        return [
            'resp' => $resp,
            'transaction' => $transaction,
            'qr_url' => $qrUrl,
            'qr_string' => $qrString,
        ];
    }

    protected function extractQrString(array $resp): ?string
    {
        if (! is_array($resp)) {
            return null;
        }

        // Your BakongService returns the QR string at data.qr
        if (! empty($resp['data']['qr']) && is_string($resp['data']['qr'])) {
            return $resp['data']['qr'];
        }

        if (! empty($resp['qr']) && is_string($resp['qr'])) {
            return $resp['qr'];
        }

        return null;
    }

    protected function buildQrUrl(array $resp, array $payload): ?string
    {
        $qrString = $this->extractQrString($resp);

        if (empty($qrString)) {
            return null;
        }

        try {
            $qrCode = new QrCode(
                data: $qrString,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 300,
                margin: 10,
            );

            return (new SvgWriter())->write($qrCode)->getDataUri();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function extractTransactionReference(array $resp): ?string
    {
        if (! is_array($resp)) {
            return null;
        }

        // Your BakongService returns the transaction reference (md5) at data.md5
        if (! empty($resp['data']['md5']) && is_string($resp['data']['md5'])) {
            return $resp['data']['md5'];
        }

        if (! empty($resp['md5']) && is_string($resp['md5'])) {
            return $resp['md5'];
        }

        return null;
    }

    protected function persistPaymentAndCustomer(array $payload): array
    {
        $paymentData = [
            'order_id' => $payload['order_id'],
            'receiver_phone' => $payload['receiver_phone'] ?? '',
            'receiver_location' => $payload['receiver_location'] ?? '',
            'status' => $payload['status'] ?? 'pending',
            'transaction_ref' => $payload['transaction_ref'] ?? null,
        ];

        if (! empty($payload['product_id'])) {
            $paymentData['product_id'] = $payload['product_id'];
        }

        if (! empty($payload['transfer_image'])) {
            $paymentData['transfer_image'] = $payload['transfer_image'];
        }

        $payment = Payments::create($paymentData);

        $bankId = $payload['bank_id'] ?? $this->resolveBankId();
        $categoryId = $payload['category_id'] ?? ($payload['product_id'] ? Products::find($payload['product_id'])->category_id : $this->resolveCategoryId());
        $productId = $payload['product_id'] ?? $this->resolveProductId($categoryId);

        $customerData = [
            'name' => $payload['customer_name'] ?: 'Guest Customer',
            'email' => $payload['customer_email'] ?: 'guest+' . uniqid() . '@example.invalid',
            'phone_number' => $payload['customer_phone'] ?: ($payload['receiver_phone'] ?: 'N/A'),
            'address' => $payload['customer_address'] ?? 'Unknown Address',
            'bank_id' => $bankId,
            'account_name' => $payload['account_name'],
            'product_id' => $productId,
            'category_id' => $categoryId,
            'payment_id' => $payment->id,
        ];

        if (! empty($payload['customer_email'])) {
            $customer = Customers::updateOrCreate(
                ['email' => $payload['customer_email']],
                $customerData
            );
        } else {
            $customer = Customers::create($customerData);
        }

        return [
            'payment' => $payment,
            'customer' => $customer,
        ];
    }

    protected function resolveOrderId(string $orderReference, float $amount): int
    {
        if (is_numeric($orderReference) && Orders::where('id', intval($orderReference))->exists()) {
            return intval($orderReference);
        }

        $user = User::first() ?: $this->createFallbackUser();

        return Orders::create([
            'order_date' => now()->toDateString(),
            'total_price' => $amount,
            'payment' => 'bakong',
            'status' => 'pending',
            'invoice' => 'INV-' . strtoupper(substr(md5(uniqid('inv', true)), 0, 10)),
            'user_id' => $user->id,
        ])->id;
    }

    protected function createFallbackUser(): User
    {
        $role = Role::first() ?: Role::create(['name' => 'customer']);

        return User::create([
            'name' => "user-" . uniqid(),
            'email' => 'email+' . uniqid(),
            'phone_number' => 'phone+' . uniqid(),
            'password' => Hash::make('password'),
            'role_id' => $role->id,
        ]);
    }

    protected function resolveBankId(): int
    {
        return Bank::first() ? Bank::first()->id : Bank::create(['name' => 'Bakong Bank', 'qr_code' => ''])->id;
    }

    protected function resolveCategoryId(): int
    {
        return Categories::first() ? Categories::first()->id : Categories::create(['name' => 'General', 'description' => 'Default category'])->id;
    }

    protected function resolveProductId(int $categoryId): int
    {
        $product = Products::where('category_id', $categoryId)->first();
        return Products::first() ? Products::first()->id : Products::create([
            'name' => $product ? $product->name : 'Default Product',
            'price' => 0,
            'discount' => 0,
            'details' => '',
            'category_id' => $categoryId,
        ])->id;
    }
}