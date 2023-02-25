<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FlutterwaveService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('flutterwave.base_url');
        $this->apiKey = config('flutterwave.secret_key');
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
                'Authorization' => 'Bearer '. $this->apiKey,
            ])
            ->post("{$this->baseUrl}/charges?type=mobile_money_uganda", $data)
            ->throw()
            ->json();
    }
}
