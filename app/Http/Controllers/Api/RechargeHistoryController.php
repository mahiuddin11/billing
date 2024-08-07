<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;

class RechargeHistoryController extends Controller
{
    function index($id)
    {
        $billings = Billing::where('customer_id', $id)
        ->where('status', '!=', 'paid')
        ->get();
        if ($billings->isEmpty()) {
            // return appResponse(false, "No billing records found for the provided ID");
            return [];
        }

        $responses = [];

        foreach ($billings as $billing) {
            $response = [];
            $response['pay_url'] = route('bkash-invoice-payment',$billing->id);
            $response['Package_Name'] =  $billing->getProfile->name ?? '';
            $response['date_'] = $billing->date_ ? date('F-Y', strtotime($billing->date_)) : '';
            $response['Deu_amount'] = (string)($billing->partial ?? ($billing->customer_billing_amount - $billing->pay_amount)) ?? '';

            $responses[] = $response;
        }

        return $responses;
    }
}
