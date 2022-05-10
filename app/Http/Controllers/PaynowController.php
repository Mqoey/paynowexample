<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Paynow\Payments\Paynow;

class PaynowController extends Controller
{
    public function client()
    {
        return new Paynow(
            '14649',
            '37da143e-5e17-452d-a746-6c746df0f08e',
            'http://127.0.0.1:8000/paynow/update',

            // The return url can be set at later stages. You might want to do this if you want to pass data to the return url (like the reference of the transaction)
            'http://127.0.0.1:8000/paynow/return'
        );
    }

    public function paymentPaynow(Request $request)
    {
        $amount = $request->amount;
        $payment = $this->client()->createPayment('Invoice 35', 'mqoeyyy@gmail.com');
        $payment->add('orders', $amount);

        $response = $this->client()->send($payment);

        if ($response->success()) {
            // Or if you prefer more control, get the link to redirect the user to, then use it as you see fit
            $link = $response->redirectUrl();

            // Get the poll url (used to check the status of a transaction). You might want to save this in your DB
            $pollUrl = $response->pollUrl();

            return response(['link' => $link, 'poll' => $pollUrl], 200);
        }
    }

}
