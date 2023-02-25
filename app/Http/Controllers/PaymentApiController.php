<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\FlutterwaveService;
use Illuminate\Http\Client\RequestException;

class PaymentApiController extends Controller
{
    private $flutterwaveService;

    public function __construct(FlutterwaveService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    /**
     * @throws RequestException
     */
    public function initialize(Request $request): JsonResponse
    {
        $reference = $this->flutterwaveService->generateReference();

        $data = [
            'tx_ref' => $reference,
            'amount' => $request->get('amount'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'payment_options' => 'card,mobilemoneyuganda',
            'customer' => [
                'email' => $request->get('email'),
                'phone_number' => $request->get('phone_number'),
                'name' => $request->get('name')
            ],
            'customizations' => [
                'title' => 'Movie Ticket',
                'description' => "20th October"
            ]
        ];

        $payment = $this->flutterwaveService->initializePayment($data);

        if ($payment['status'] === 'success') {

            // TODO: Save the transaction here

            return response()->json([
                'payment_url' => $payment['meta']['authorization']['redirect']
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'Something went wrong. Please try again later'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
