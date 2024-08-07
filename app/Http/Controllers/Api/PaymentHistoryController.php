<?php

namespace App\Http\Controllers\Api;

use App\Models\Billing;
use App\Http\Controllers\Controller;
use App\Models\Billing as ModelsBilling;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    function index($id)
    {
        $billings = Billing::where('customer_id', $id)
        ->where('status', 'paid')
        ->get();
        if ($billings->isEmpty()) {
            $responses= [];
              return  $responses;
        }

        $responses = [];

        foreach ($billings as $billing) {
            $response = [];

            $response['customer_name'] = $billing->getCustomer->name ?? '';
            $response['customer_phone'] = $billing->getCustomer->phone ?? '';
            $response['customer_profile_id'] = $billing->getCustomer->profile_id ?? '';
            $response['customer_billing_amount'] = $billing->customer_billing_amount ?? '';
            $response['date_'] = $billing->date_ ? date('F-Y', strtotime($billing->date_)) : '';

            if ($billing->payment_method_id == 500) {
                $response['payment_method'] = 'Advance Payment';
            } else {
                $response['payment_method'] = $billing->PaymentMethod->account_name ?? '';
            }

            $response['pay_amount'] = $billing->pay_amount ?? '';
            $response['Deu_amount'] = ($billing->partial ?? ($billing->customer_billing_amount - $billing->pay_amount)) ?? '';
            $response['status'] = $billing->status ?? '';

            $responses[] = $response;
        }

        return  $responses;
    }
}
