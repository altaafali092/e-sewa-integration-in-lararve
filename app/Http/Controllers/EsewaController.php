<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class EsewaController extends Controller
{

    // function name
    public function esewapay(StoreOrderRequest $request)
    {
        $client = new Client();

        //  add fields as you want
        $amount = $request->amount;
        $refId = uniqid();
        $user = $request->user_id;
        $name= $request->name;
        $email= $request->email;


        $payment = Order::create([
            'email' => $email,
            'name'=>$name,
            'user_id'=>$user,
            'esewa_status'=>'success',
            'product_id'=>Str::random(10),
            'transaction_id' => $refId,
            'amount' => $amount,

        ]);

        // eSewa payment request using Guzzle
        $response = $client->request('POST', 'https://uat.esewa.com.np/epay/main', [
            'form_params' => [
                'amt' => $amount,
                'tAmt' => $amount,
                'pid' => $refId,
                'scd' => 'EPAYTEST', // Test merchant code
                'su' => route('success', ['payment_id' => $payment->id]), // Pass the payment ID to success URL
                'fu' => route('failed', ['payment_id' => $payment->id]), // Pass the payment ID to failed URL
            ]
        ]);

        // Redirect to eSewa's payment gateway
        return redirect()->to('https://uat.esewa.com.np/epay/main?' . http_build_query([
            'amt' => $amount,
            'pdc' => 0,
            'psc' => 0,
            'txAmt' => 0,
            'tAmt' => $amount,
            'pid' => $refId,
            'scd' => 'EPAYTEST', // Use test merchant code here
            'su' => route('success', ['payment_id' => $payment->id]),
            'fu' => route('failed', ['payment_id' => $payment->id]),
        ]));
    }




    public function success()
    {
        echo "Success";

    }
    public function failed()
    {
        echo "Failed";
    }
}
