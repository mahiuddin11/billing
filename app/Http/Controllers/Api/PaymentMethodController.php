<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    function index() {
        $paymentMethods = PaymentMethod::where('name','Bkash')->get();

        $responses = [];

        foreach ($paymentMethods as $paymentMethod) {
            $response = [];

            // Include payment method name
            $response['name'] = $paymentMethod->name;
            // Include bKash data if the payment method is bKash
                $response['Logo'] = 'https://freelogopng.com/images/all_img/1656235223bkash-logo.png';
            if ($paymentMethod->name == 'BKash') {
                $response['name'] = "Bkash";
            }

            // Add response to the array
            $responses[] = $response;
        }

        return response()->json($responses);
    }
}




