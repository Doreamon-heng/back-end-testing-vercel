<?php

namespace App\Service;

use KHQR\BakongKHQR;
use KHQR\Models\MerchantInfo;
use KHQR\Models\IndividualInfo;
use KHQR\Helpers\KHQRData;
use Illuminate\Support\Facades\Http;

class BakongService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Bakong') ?? [];
    }

    public function createPayment(array $payload)
    {
        $token = trim((string) ($this->config['token'] ?? env('BAKONG_TOKEN') ?? ''));
        $accountId = trim((string) ($this->config['account_id'] ?? env('BAKONG_ACCOUNT_ID') ?? ''));
        $merchantName = trim((string) ($this->config['merchant_name'] ?? env('BAKONG_MERCHANT_NAME') ?? ''));
        $merchantCity = trim((string) ($this->config['merchant_city'] ?? env('BAKONG_MERCHANT_CITY', 'PHNOM PENH')));
        $rawCurrency = strtoupper(trim($payload['currency'] ?? ($this->config['currency'] ?? 'USD')));
        $supportedCurrencies = ['USD', 'KHR'];
        $amount = floatval($payload['amount'] ?? 0);
        $expirationMinutes = intval($this->config['expiration_minutes'] ?? env('BAKONG_EXPIRATION_MINUTES', 10));

        // compute expiration timestamp in milliseconds correctly
        $expirationTimestamp = (int) floor(microtime(true) * 1000) + ($expirationMinutes * 60 * 1000);
        if (! in_array($rawCurrency, $supportedCurrencies, true)) {
            return [
                'error' => true,
                'message' => sprintf('Invalid Bakong currency "%s". Supported values: %s.', $rawCurrency, implode(', ', $supportedCurrencies)),
            ];
        }

         
        // Basic config validation: provide clearer errors when values look malformed
        if (empty($token) || empty($accountId) || empty($merchantName)) {
            return [
                'error' => true,
                'message' => 'Bakong configuration missing: set BAKONG_TOKEN, BAKONG_ACCOUNT_ID, and BAKONG_MERCHANT_NAME in your environment',
            ];
        }

        // Bakong account IDs use the form `account@bank`; only whitespace is invalid.
        if (preg_match('/\s/', $accountId)) {
            return [
                'error' => true,
                'message' => 'BAKONG_ACCOUNT_ID appears invalid because it contains whitespace.',
            ];
        }

        $currencyCode = $rawCurrency === 'USD' ? KHQRData::CURRENCY_USD : KHQRData::CURRENCY_KHR;
        $expirationTimestamp = (string) floor(microtime(true) * 1000) + ($expirationMinutes * 60 * 1000);

        $merchantInfo = new MerchantInfo(
            bakongAccountID: $accountId,
            merchantName: $merchantName,
            merchantCity: $merchantCity,
            merchantID: '000000',
            acquiringBank: 'Bakong Bank',
            amount: $amount,
            currency: $currencyCode,
            expirationTimestamp: (string) $expirationTimestamp,
            merchantCategoryCode: '5999'
        );

        try {
            $khqrResponse = BakongKHQR::generateMerchant($merchantInfo);
            return [
                'error' => false,
                'status' => 200,
                'data' => [
                    'qr' => $khqrResponse->data['qr'] ?? null,
                    'md5' => $khqrResponse->data['md5'] ?? null,
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check transaction status by MD5 hash (used for polling from the frontend).
     * Calls Bakong's Open API "check transaction by MD5" endpoint.
     */
    public function checkTransactionStatus(string $md5): array
    {
        $token = trim((string) ($this->config['token'] ?? env('BAKONG_TOKEN') ?? ''));
        $baseUrl = rtrim((string) ($this->config['base_url'] ?? env('BAKONG_BASE_URL', 'https://api-bakong.nbc.gov.kh')), '/');

        if (empty($token)) {
            return [
                'error' => true,
                'message' => 'Bakong configuration missing: set BAKONG_TOKEN in your environment',
                'status' => 'pending',
            ];
        }

        try {
            $verifySsl = $this->config['verify_ssl'] ?? filter_var(env('BAKONG_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);

            $request = Http::withToken($token)->acceptJson();

            if (! $verifySsl) {
                $request = $request->withoutVerifying();
            }

            $response = $request->post("{$baseUrl}/v1/check_transaction_by_md5", [
                'md5' => $md5,
            ]);

            if ($response->failed()) {
                return [
                    'error' => true,
                    'message' => 'Failed to check transaction status.',
                    'status' => 'pending',
                ];
            }

            $data = $response->json();

            // Bakong returns responseCode 0 when the transaction is found/paid
            $status = 'pending';
            if (isset($data['responseCode']) && $data['responseCode'] === 0) {
                $status = 'success';
            } elseif (isset($data['responseMessage']) && stripos($data['responseMessage'], 'not found') !== false) {
                $status = 'pending';
            }

            return [
                'error' => false,
                'status' => $status,
                'data' => $data,
            ];
        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status' => 'pending',
            ];
        }
    }

    public function sendTelegramNotification(string $message): bool
    {
        $apiBase = $this->telegramApiBase();
        $chatId = $this->config['telegram_chat_id'] ?? env('BAKONG_TELEGRAM_CHAT_ID');
       

        if (empty($apiBase) || empty($chatId)) {
            return false;
        }

        $request = Http::withHeaders([
            'Accept' => 'application/json',
        ]);

        $response = $request->post($apiBase . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

        return $response->successful();
    }

    protected function telegramApiBase(): ?string
    {
        $url = trim($this->config['telegram_bot_url'] ?? env('BAKONG_TELEGRAM_BOT_URL') ?? '');
        $token = trim($this->config['telegram_bot_token'] ?? env('BAKONG_TELEGRAM_BOT_TOKEN') ?? '');

        if (! empty($url)) {
            $parsed = parse_url($url);
            if (! empty($parsed['scheme']) && ! empty($parsed['host']) && ! empty($parsed['path'])) {
                if (preg_match('#^/bot[^/]+#', $parsed['path'], $matches)) {
                    return rtrim($parsed['scheme'] . '://' . $parsed['host'] . $matches[0], '/');
                }
                return rtrim($url, '/');
            }
            return rtrim($url, '/');
        }

        if (! empty($token)) {
            return 'https://api.telegram.org/bot' . trim($token);
        }

        return null;
    }

    public function verifyWebhook(string $payload, ?string $signature): bool
    {
        $secret = $this->config['webhook_secret'] ?? env('BAKONG_WEBHOOK_SECRET');
        if (empty($secret) || empty($signature)) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature);
    }
}