<?php

use App\Helpers\Billing as HelpersBilling;
use App\Models\Billing;
use App\Models\Company;
use App\Models\Tj;
use Illuminate\Support\Facades\Http;

if (!function_exists('format_bytes')) {
    /**
     * Format bytes to kb, mb, gb, tb
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    function format_bytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' kbps', ' Mbps', ' Gbps', ' Tbps');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}

if (!function_exists('invoiceNumber')) {
    /**
     *
     * @return integer
     */
    function invoiceNumber($id)
    {

        $purchaseLastData = Billing::find($id);
        if ($purchaseLastData) :
            $purchaseData = $purchaseLastData->id;
        else :
            $purchaseData = 1;
        endif;
        $invoice_no = 'BV' . str_pad($purchaseData, 5, "0", STR_PAD_LEFT);

        return $invoice_no;
    }
}

if (!function_exists('sendSms')) {
    /**
     *
     * @return integer
     */
    function sendSms($number, $message)
    {
        $company = Company::first();
        $url = $company->url;

        $data = [
            "apiKey" => $company->apikey,
            "contactNumbers" => $number,
            "senderId" => $company->secretkey,
            "textBody" => $message
        ];

        $lool = null;
        if (!empty($company->secretkey)) {
            $lool = Http::post($url, $data);
        }
        return $lool;
    }
}

if (!function_exists('monthlyStartSms')) {
    /**
     *
     * @return integer
     */
    function monthlyStartSms($number, $message)
    {
        $company = Company::first();
        $url = $company->url;
        $data = [
            "apiKey" => $company->apikey,
            "contactNumbers" => $number,
            "senderId" => $company->secretkey,
            "textBody" => $message
        ];
        $response = null;
        if (!empty($company->secretkey)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            echo "$response";
            curl_close($ch);
        }
        return $response;
    }
}

if (!function_exists('appResponse')) {
    /**
     *
     * @return integer
     */
    function appResponse($status, $message, $response = [])
    {
        return ["success" => $status, "message" => $message, "data" => $response];
    }
}

if (!function_exists('messageconvert')) {
    /**
     * @return integer
     */
    function messageconvert($customer, $message, $payamount = 0)
    {
        $explode = explode(" ", $message);
        $clientid = array_search("%clientid%", $explode);
        $username = array_search("%username%", $explode);
        $clientname = array_search("%clientname%", $explode);
        $password = array_search("%password%", $explode);
        $monthlybill = array_search("%monthlybill%", $explode);
        $expdate = array_search("%expdate%", $explode);
        $duebill = array_search("%duebill%", $explode);
        $link = array_search("%link%", $explode);
        $monthname = array_search("%monthname%", $explode);
        $paymoney = array_search("%paymoney%", $explode);

        if ($clientid) {
            $explode[$clientid] = $customer->client_id;
        }

        if ($username) {
            $explode[$username] = $customer->username;
        }

        if ($paymoney) {
            $explode[$paymoney] = $payamount;
        }

        if ($password) {
            $explode[$password] = $customer->m_password;
        }

        if ($clientname) {
            $explode[$clientname] = $customer->name;
        }

        if ($monthlybill) {
            $explode[$monthlybill] = $customer->bill_amount;
        }

        if ($monthname) {
            $explode[$monthname] =  $customer->date_ ? date("F", strtotime($customer->date_)) : date("F");
        }

        if ($expdate) {
            $explode[$expdate] = date('d-m-Y', strtotime($customer->exp_date));
        }

        if ($duebill) {
            $billings = Billing::where('customer_id', $customer->id)->where('status', '!=', 'paid')->get();
            // $billings = Billing::where('customer_id', $customer->id)->whereMonth('date_', '!=', date('m'))->whereYear('date_', date('Y'))->where('status', '!=', 'paid')->get();
            $total = 0;
            $customercheck = ($customer->billing_type ?? "") == "day_to_day";

            foreach ($billings as $billing) {
                $total += $billing->customer_billing_amount - $billing->pay_amount;
            }

            $explode[$duebill] = $total;
        }

        if ($link) {
            $billings = Billing::where('customer_id', $customer->id)->where('status', '!=', 'paid')->whereMonth('date_', date('m'))->first();
            if ($billings) {
                $route = route('invoice.payment', ['INV' . rand(1111111 & 111, 999999999999), $billings->id]);
            } else {
                $route = "";
            }
            $explode[$link] = $route;
        }

        $implode = implode(" ", $explode);

        return $implode;
    }
}
