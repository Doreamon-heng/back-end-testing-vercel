<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\BakongService;
use App\Http\Controllers\api\PaymentController;

class BakongWebController extends Controller
{
    protected $service;

    public function __construct(BakongService $service)
    {
        $this->service = $service;
    }

    public function showForm()
    {
        return view('bakong_test');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'sometimes|string',
        ]);

        $request->merge([
            'currency' => $data['currency'] ?? config('Bakong.currency', env('BAKONG_CURRENCY', 'USD')),
        ]);

        $paymentController = app(PaymentController::class);
        $response = $paymentController->createBakongPayment($request);

        $responseData = json_decode($response->getContent(), true) ?: [];
        $payload = $data;
        $qrUrl = $responseData['qr_url'] ?? null;
        $qrString = null;

        if (! empty($responseData['bakong']['data']['qr'])) {
            $qrString = $responseData['bakong']['data']['qr'];
        } elseif (! empty($responseData['bakong']['qr'])) {
            $qrString = $responseData['bakong']['qr'];
        } elseif (! empty($responseData['bakong']['data']['body'])) {
            $qrString = $responseData['bakong']['data']['body'];
        }

        return view('bakong_test', [
            'result' => $responseData,
            'payload' => $payload,
            'qr_url' => $qrUrl,
            'qr_string' => $qrString,
        ]);
    }
}
