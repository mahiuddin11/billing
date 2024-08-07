<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Billing;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;
use Karim007\LaravelBkashTokenize\Facade\BkashRefundTokenize;
use \RouterOS\Query;

class BkashTokenizePaymentController extends Controller
{
    public function index()
    {
        return view('bkashT::bkash-payment');
    }

    public function createPayment(Request $request, $id)
    {
        $billing = Billing::find($id);
        $inv = uniqid();
        $request['intent'] = 'sale';
        $request['mode'] = '0011'; //0011 for checkout
        $request['payerReference'] = "Username:" . ($billing->getCustomer->username ?? "");
        $request['currency'] = 'BDT';
        $request['amount'] = $billing->customer_billing_amount - $billing->pay_amount;
        $request['merchantInvoiceNumber'] = $inv . "" . $id;
        $request['callbackURL'] = config("bkash.callbackURL");;

        session()->put('billingid', $billing->id);

        $request_data_json = json_encode($request->all());
        $response =  BkashPaymentTokenize::cPayment($request_data_json);
        //$response =  BkashPaymentTokenize::cPayment($request_data_json,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..

        //store paymentID and your account number for matching in callback request
        // return $response; //if you are using sandbox and not submit info to bkash use it for 1 response

        if (isset($response['bkashURL'])) return redirect()->away($response['bkashURL']);
        else return redirect()->back()->with('error-alert2', $response['statusMessage']);
    }

    public function callBack(Request $request)
    {
        //callback request params
        // paymentID=your_payment_id&status=success&apiVersion=1.2.0-beta
        //using paymentID find the account number for sending params
        if ($request->status == 'success') {
            $response = BkashPaymentTokenize::executePayment($request->paymentID);
            // $response = BkashPaymentTokenize::executePayment($request->paymentID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            if (!$response) { //if executePayment payment not found call queryPayment
                $response = BkashPaymentTokenize::queryPayment($request->paymentID);
                //$response = BkashPaymentTokenize::queryPayment($request->paymentID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            }

            if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
                $billing = Billing::find(session()->get('billingid'));

                $billing->update([
                    'invoice_name' => $request->invoice_name ?? 0,
                    'alert' => "white",
                    'discount' => $request->discount ?? 0,
                    'pay_amount' => $billing->customer_billing_amount,
                    "payment_method_id" => 2,
                    'status' => 'paid',
                    'description' => "TRX" . $response['trxID'],
                    'billing_by' => 1,
                ]);


                $invoice = AccountTransaction::accountInvoice();
                $transaction['invoice'] = $invoice;
                $transaction['table_id'] = $billing->id;
                $transaction['account_id'] = 10;
                $transaction['type'] = 4;
                $transaction['company_id'] = $billing->company_id;
                $transaction['credit'] = $billing->customer_billing_amount;
                $transaction['remark'] = "Internet Bill";
                $transaction['customer_id'] = $billing->customer_id;
                $transaction['created_by'] = 1;
                AccountTransaction::create($transaction);

                $transactionPay['invoice'] = $invoice;
                $transactionPay['table_id'] = $billing->id;
                $transactionPay['account_id'] = 17;
                $transactionPay['type'] = 4;
                $transactionPay['company_id'] = $billing->company_id;
                $transactionPay['debit'] = $billing->customer_billing_amount - $request->discount;
                $transactionPay['remark'] = "Internet Bill";
                $transactionPay['customer_id'] = $billing->customer_id;
                $transactionPay['created_by'] = 1;
                AccountTransaction::create($transactionPay);

                $transactiondue['date'] = $billing->getCustomer->start_date;
                $transactiondue['local_id'] = $billing->id;
                $transactiondue['pay_method_id'] = 17;
                $transactiondue['type'] = 10;
                $transactiondue['company_id'] = $billing->company_id;
                $transactiondue['credit'] = $billing->customer_billing_amount;
                $transactiondue['amount'] = $billing->customer_billing_amount;
                $transactiondue['created_by'] = auth()->id();
                Transaction::create($transactiondue);


                if ($billing->getCustomer->billing_status_id == 4 && $billing->getCustomer->billing_type == "day_to_day") { // when customer disconnect few days and after few days when the recharge the
                    $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                    $endDate = Carbon::parse(date("Y-m-d"))->addMonths($billing->getCustomer->duration);
                    if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                        $endDate = $endDate->day(date("d"));
                    }
                    $endDate = $endDate->format('Y-m-d');
                    $billing->getCustomer->update([
                        'start_date' => $startDate,
                        'billing_status_id' => 5,
                        'bill_collection_date' => date("d"),
                        'exp_date' => $endDate,
                        'disabled' => 'false',
                        'queue_disabled' => 'false',
                        "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                    ]);
                } else {
                    $startDate = Carbon::parse($billing->getCustomer->start_date)->addMonths($billing->getCustomer->duration)->format('Y-m-d');
                    $endDate = Carbon::parse($billing->getCustomer->exp_date)->addMonths($billing->getCustomer->duration);
                    if ($billing->getCustomer->bill_collection_date != 0 && !empty($billing->getCustomer->bill_collection_date)) {
                        $endDate = $endDate->day($billing->getCustomer->bill_collection_date);
                    }
                    $endDate = $endDate->format('Y-m-d');
                    $billing->getCustomer->update([
                        'start_date' => $startDate,
                        'billing_status_id' => 5,
                        'exp_date' => $endDate,
                        'disabled' => 'false',
                        'queue_disabled' => 'false',
                        "total_paid" => $billing->getCustomer->total_paid +  $billing->customer_billing_amount,
                    ]);
                }

                $client = $this->client($billing->getCustomer->server_id);
                if ($billing->getCustomer->protocol_type_id == 3) {
                    $query =  new Query('/ppp/secret/set');
                    $query->equal('.id', $billing->getCustomer->mid);
                    $query->equal('disabled', 'false');
                    $client->query($query)->read();
                } elseif ($billing->getCustomer->protocol_type_id == 1) {
                    $query =  new Query('/queue/simple/set');
                    $query->equal('.id', $billing->getCustomer->queue_id);
                    $query->equal('disabled', 'false');
                    $client->query($query)->read();
                }

                $message = messageconvert($billing->getCustomer, $billing->company->bill_paid_msg);
                // $message = "Sir," . $billing->getCustomer->username . " Recieve Amount " . $billing->customer_billing_amount . " Monthly Bill: " . $billing->customer_billing_amount . " Tk Thank you " . $billing->company->company_name;
                sendSms($billing->getCustomer->phone, $message);

                return redirect()->route('tnx.you')->with('success', 'Thank you for your payment Your Transaction ID: ' . $response['trxID']);
            }
            return redirect()->route('tnx.you')->with('failed', $response['statusMessage']);
        } else if ($request->status == 'cancel') {
            return redirect()->route('tnx.you')->with('failed', 'Your payment is canceled');
        } else {
            return redirect()->route('tnx.you')->with('failed', 'Your transaction is failed');
        }
    }

    public function searchTnx($trxID)
    {
        //response
        return BkashPaymentTokenize::searchTransaction($trxID);
        //return BkashPaymentTokenize::searchTransaction($trxID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }

    public function refund(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        $amount = 5;
        $reason = 'this is test reason';
        $sku = 'abc';
        //response
        return BkashRefundTokenize::refund($paymentID, $trxID, $amount, $reason, $sku);
        //return BkashRefundTokenize::refund($paymentID,$trxID,$amount,$reason,$sku, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
    public function refundStatus(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        return BkashRefundTokenize::refundStatus($paymentID, $trxID);
        //return BkashRefundTokenize::refundStatus($paymentID,$trxID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
}
