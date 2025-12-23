<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    public static function authToken()
    {
        $response = Http::post(config('services.paymob.base_url') . '/auth/tokens', [
            'api_key' => config('services.paymob.api_key'),
        ]);

        return $response['token'];
    }

    public static function createOrder($token, $amountCents)
    {
        return Http::post(config('services.paymob.base_url') . '/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $amountCents,
            'currency' => 'EGP',
            'items' => [],
        ]);
    }

    public static function paymentKey($token, $orderId, $amountCents, $user)
    {
        return Http::post(config('services.paymob.base_url') . '/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'first_name' => $user->name,
                'last_name' => 'User',
                'email' => $user->email,
                'phone_number' => '01000000000',
                'city' => 'Cairo',
                'country' => 'EG',
                'street' => 'N/A',
                'building' => 'N/A',
                'floor' => 'N/A',
                'apartment' => 'N/A',
            ],
            'currency' => 'EGP',
            'integration_id' => config('services.paymob.integration_id'),
        ]);
    }
}
