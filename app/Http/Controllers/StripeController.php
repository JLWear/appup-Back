<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function index(){

    }

    var $domain = 'localhost:4200/success';
    var $stripe_sk = 'pk_test_51PKyLKGSWega41ZG9q0DFXHLC6AKNQW6T4V5HIq4YpDz7BzWNlz7QjADbShSnB8fcw1BsWNNScTimXLviehqG9B3000zdH2H5h';

    public function checkout(){
        \Stripe\Stripe::setApiKey($stripe_sk);
        header('Content-Type: application/json');

        $session = \Stripe\Checkout\Session::create([
            'submit_type' => 'pay',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => 500000,
                    'product_data' => [
                        'name' => 'Voiture sélectionné',
                        // 'images' => [$picture],
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                //'token' => $token
            ],
            'mode' => 'payment',
            'payment_intent_data' => [
                'capture_method' => 'manual',
                'setup_future_usage' => 'off_session'
            ],
            'success_url' => $domain + '?transaction_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $domain + 'payment_failed'
        ]);
    }

    public function success(){

    }
}
