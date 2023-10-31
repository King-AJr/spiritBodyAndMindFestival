<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public $amount;

    public function callback_fx() {
        $res = json_decode($this->verify_payment(request('reference')));
        if($res->status) {
            if($res->data->amount == 2000000) {
                //send email with ticket
                Log::info('inside VIP ' . $res->data->amount);
                return response()->json([
                    'status' => $res->status,
                    'message' => 'Purchase of VIP ticket successful, your ticket is ' . $res->data->reference
                ]);

            } elseif($res->data->amount == 400000) {
                //send email with ticket
                Log::info('inside regular ' . $res->data->amount);
                return response()->json([
                    'status' => $res->status,
                    'message' => 'Purchase of ticket successful, your ticket is ' . $res->data->reference
                ]);

            }
        } else {
            return response()->json([
                'status' => $res->status,
                'message' => $res->message
            ]);
        }
    }

    public function make_payment() {
        if (request('plan') == 'regular') {
            $this->amount = 4000 * 100;
        } elseif (request('plan') == 'VIP') {
            $this->amount = 20000 * 100;
        }

        $formData = [
            'email' => request('email'),
            'amount' => $this->amount,
            'currency' => "NGN",
            'callback_url' => route('callback')
        ];

        $pay = json_decode($this->initiate_payment($formData));
        if($pay) {
            if($pay->status) {
                return response()->json([
                    'status' => 'success',
                    'message' => $pay->data->authorization_url
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $pay->message
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $pay->message
            ], 400);
            // return back()->withError("something went wrong");
        }

    }

    public function initiate_payment($formData) {
        $url="https://api.paystack.co/transaction/initialize";

        $fields_string = http_build_query($formData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ". $_ENV["PAYSTACK_SECRET_KEY"],
            "Cache-Control: no-cache"
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function verify_payment($reference) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ". $_ENV["PAYSTACK_SECRET_KEY"],
                "Cache-Control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}


