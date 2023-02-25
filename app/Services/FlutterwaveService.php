<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FlutterwaveService
{
    private string $baseUrl;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('flutterwave.base_url');
        $this->secretKey = config('flutterwave.secret_key');
    }

    public function generateReference(string $transactionPrefix = null): string
    {
        if ($transactionPrefix) {
            return $transactionPrefix . '_' . uniqid(time(), true);
        }
        return 'flw_' . uniqid(time(), true);
    }

    /**
     * @throws RequestException
     */
    public function initializePayment(array $data)
    {
        $data['currency'] = 'UGX';

        return Http::acceptJson()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '. $this->secretKey,
            ])
            ->post("{$this->baseUrl}/charges?type=mobile_money_uganda", $data)
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function verifyTransaction($id)
    {
        return Http::acceptJson()
            ->withToken($this->secretKey)
            ->get($this->baseUrl . "/transactions/" . $id . '/verify')
            ->throw()
            ->json();
    }
}
