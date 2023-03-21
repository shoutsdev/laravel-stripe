<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe($price)
    {
        $url                        = url('/');
        $us                         = ['card','us_bank_account','alipay'];

        $session = [
            'payment_method_types' => $us,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $price * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $url,
            'cancel_url' => url()->previous(),
            'phone_number_collection' => [
                'enabled' => "true"
            ],
        ];

        $secret_key = 'sk_test_51KSEttAEcZ6N0eChifvBvTyjszi2Bti1iuLvOjuIvt9PfMgDLRlRDgfgzGylXTqkOGFO6KYPeCb7N78FCKSUNwNh00hE1xcGN2';
        $headers = [
            'Authorization' => "Bearer $secret_key",
        ];

        $client = new \GuzzleHttp\Client(['verify' => false]);
        $response = $client->request('POST', 'https://api.stripe.com/v1/checkout/sessions', [
            'headers' => $headers,
            'form_params' => $session,
        ]);

        $result = $response->getBody()->getContents();
        $response =  json_decode($result,true);
        return redirect($response['url']);

    }
}
