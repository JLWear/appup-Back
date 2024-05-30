<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckoutSession;

class StripeController extends Controller
{
    public function index(){

    }

    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_51PKyLKGSWega41ZG4CHZCvs61QPXtvD1DiMTh4v2W7amXeikK69B7496rR8qQOQWoHlkW2LRixt3qeiI4UQQFWCi00e8iyAg6v');
        header('Content-Type: application/json');

        $checkoutSession = CheckoutSession::create([
            'name' => $request->name,
            'firstname' => $request->firstname,
            'age' => $request->age,
            'locationCity' => $request->locationCity,
            'carToRent' => $request->carToRent,
            'token' => $request->token,
        ]);

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
                'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiIiwiZmlyc3RuYW1lIjoiIiwiYWdlIjoiIiwibG9jYXRpb25DaXR5IjoiIiwiY2FyVG9SZW50IjoiIiwidG9rZW4iOiIifQ.7AbSVI89ubn-KTF6tsBEwvrtDLTH-V4d4sY0DAtAFww'
            ],
            'mode' => 'payment',
            'payment_intent_data' => [
                'capture_method' => 'manual',
                'setup_future_usage' => 'off_session'
            ],
            'success_url' => 'http://localhost:4200/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:4200/cancel'
        ]);
        $checkoutSession->update(['sessionId' => $session->id]);
        return response()->json(['sessionId' => $session->id]);
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient('sk_test_51PKyLKGSWega41ZG4CHZCvs61QPXtvD1DiMTh4v2W7amXeikK69B7496rR8qQOQWoHlkW2LRixt3qeiI4UQQFWCi00e8iyAg6v');

        $response = $stripe->checkout->sessions->retrieve(
            $request->sessionId, []
        );

        $paymentI = $stripe->paymentIntents->retrieve(
            $response->payment_intent,
            []);


        $paymentM = $stripe->paymentMethods->retrieve(
            $paymentI->payment_method,
            []);


        // Récupérer les détails de la session de paiement depuis la base de données
        $checkoutSession = CheckoutSession::where('sessionId', $request->sessionId)->first();

        $amount = $response->amount_total / 100;

        $checkoutSession2 = CheckoutSession::create([
            'name' => $checkoutSession->name,
            'firstname' => $checkoutSession->firstname,
            'age' => $checkoutSession->age,
            'locationCity' => $checkoutSession->locationCity,
            'carToRent' => $checkoutSession->carToRent,
            'token' => $checkoutSession->token,
            'email' => $paymentM->billing_details->email,
            'last4' => $paymentM->card->last4,
            'amount_total' => $amount
        ]);


        return response()->json($checkoutSession2);
    }
}
