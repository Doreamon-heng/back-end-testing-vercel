<?php

return [
    'base_url' => env('BAKONG_BASE_URL', 'https://sit-api-bakong.nbc.org.kh'),
    'client_id' => env('BAKONG_CLIENT_ID'),
    'client_secret' => env('BAKONG_CLIENT_SECRET'),
    'token' => env('BAKONG_TOKEN'),
    'account_id' => env('BAKONG_ACCOUNT_ID'),
    'merchant_name' => env('BAKONG_MERCHANT_NAME'),
    'currency' => env('BAKONG_CURRENCY', 'USD'),
    'webhook_secret' => env('BAKONG_WEBHOOK_SECRET'),
    'merchant_city' => env('BAKONG_MERCHANT_CITY', 'PHNOM PENH'),
    'expiration_minutes' => env('BAKONG_EXPIRATION_MINUTES', 10),
    'environment' => env('BAKONG_ENV', 'sandbox'),
    'verify' => env('BAKONG_VERIFY_SSL', true),
    'telegram_bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'telegram_bot_url' => env('BAKONG_TELEGRAM_BOT_URL'),
    'telegram_chat_id' => env('TELEGRAM_CHAT_ID'),
    'telegram_verify_ssl' => env('BAKONG_TELEGRAM_VERIFY_SSL', true),
];
