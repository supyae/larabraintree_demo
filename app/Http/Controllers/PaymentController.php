<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Braintree_Transaction;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{

    public function get()
    {
        $user = User::first();
        $response = \Braintree_Customer::create([
            'id'        => $user->id,
            'firstName' => $user->supyae,
            'email'     => $user->email,
            'phone'     => $user->phone,

        ]);

        if ($response->success) {
            $user->braintree_customer_id = $response->customer->id;
            $user->save();

        }
        if (sizeof($response->params) > 0) {
            $user->braintree_customer_id = $response->params['customer']['id'];
            $user->save();

            $clientToken = \Braintree_ClientToken::generate([
                'customerId' => $user->braintree_customer_id,
            ]);
        }


        return view('payment', [
            'braintree_customer_id' => $user->braintree_customer_id,
            'clientToken'           => $clientToken
        ]);
    }

    public function getPayment(Request $request)
    {
        $customer = User::first();
        // brain tree customer payment nouce
        $payload = $request->get('payload');
        $payment_method_nonce = $payload['nonce'];

        if (!empty($payment_method_nonce)) {
            $customer->braintree_nonce = $payment_method_nonce;
            $customer->save();
        }

        Log::info($customer->toArray());

        // make sure that if we do not have customer nonce already
        // then we create nonce and save it to our database
//        if (!$customer->braintree_nonce) {
//            // once we recieved customer payment nonce
//            // we have to save this nonce to our customer table
//            // so that next time user does not need to enter his credit card details
//
//            Log::info([
//                'customerId'         => $customer->braintree_customer_id,
//                'paymentMethodNonce' => $payment_method_nonce
//            ]);
//
//            $result = \Braintree\PaymentMethod::create([
//                'customerId'         => $customer->braintree_customer_id,
//                'paymentMethodNonce' => $payment_method_nonce
//            ]);
//            Log::info('paymentMethod result');
//            Log::info($result);
//
//            // save this nonce to customer table
//            $customer->braintree_nonce = $result->paymentMethod->token;
//            $customer->save();
//        }

        // process the customer payment
//        $client_nonce = \Braintree\PaymentMethodNonce::create($customer->braintree_nonce);
        $result = \Braintree\Transaction::sale([
            'amount'             => 18,
            'options'            => ['submitForSettlement' => true],
            'paymentMethodNonce' => $payment_method_nonce
        ]);

        Log::info('paymentMethod nonce result');
        Log::info($result);

        // check to see if braintree has processed
        // our client purchase transaction
        if (!empty($result->transaction)) {
            Log::info($result->transaction);
            // your customer payment is done successfully
            Log::info('transaction success');
        } else {
            Log::info('transaction failed ');
        }

    }

    public function process(Request $request)
    {
        $payload = $request->input('payload', false);
        $nonce = $payload['nonce'];

        $status = Braintree_Transaction::sale([
            'amount'             => '10.00',
            'paymentMethodNonce' => $nonce,
            'options'            => [
                'submitForSettlement' => true
            ]
        ]);

        return response()->json($status);
    }

    public function subscribe(Request $request)
    {
        try {
            $payload = $request->input('payload', false);
            Log::info($payload);
            $nonce = $payload['nonce'];

            $user = User::first();
            $data = $user->newSubscription('main', '853g')->create($nonce);
            Log::info($data);

            return response()->json(['success' => true]);
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());

            return response()->json(['success' => false]);
        }
    }
}
