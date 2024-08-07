<?php

namespace App\Helpers;

use App\Models\AccountTransaction;
use App\Models\Billing as ModelBilling;
use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Billing
{
    public function start()
    {
        $started =  Customer::where('billing_status_id', 5)->whereNotNull('name')->get();
        if ($started->isNotEmpty()) {
            $newbillcreate = [];
            $advancebill = [];
            foreach ($started as $customer) {
                $getModel = ModelBilling::whereMonth('date_', date('m'))->whereYear('date_', date('Y'))->where('customer_id', $customer->id)->first();

                if (!$getModel) {
                    if ($customer->advanced_payment >= $customer->bill_amount) {
                        $customer->update(['advanced_payment' => ($customer->advanced_payment - $customer->bill_amount)]);
                        $startDate = Carbon::parse($customer->start_date)->addMonths($customer->duration)->format('Y-m-d');
                        $endDate = Carbon::parse($customer->exp_date)->addMonths($customer->duration);
                        $customer->update(['start_date' => $startDate, 'exp_date' => $endDate]);

                        $advancebill = new ModelBilling();
                        $advancebill->date_ = date('Y-m-d');
                        $advancebill->customer_id = $customer->id;
                        $advancebill->company_id = $customer->company_id;
                        $advancebill->customer_phone = $customer->phone;
                        $advancebill->customer_profile_id = $customer->m_p_p_p_profile;
                        $advancebill->customer_billing_amount = $customer->bill_amount;
                        $advancebill->biller_name = $customer->billing_person;
                        $advancebill->type = "collection";
                        $advancebill->status = "paid";
                        $advancebill->alert = "white";
                        $advancebill->pay_amount = $customer->bill_amount;
                        $advancebill->payment_method_id = 500;
                        $advancebill->save();

                        $invoice = AccountTransaction::accountInvoice();
                        $transaction['invoice'] = $invoice;
                        $transaction['table_id'] = $advancebill->id;
                        $transaction['account_id'] = 10;
                        $transaction['type'] = 4;
                        $transaction['company_id'] = $customer->company_id;
                        $transaction['credit'] =  $customer->bill_amount;
                        $transaction['remark'] = "Internet Bill";
                        $transaction['customer_id'] = $customer->id;
                        $transaction['created_by'] = 0;
                        AccountTransaction::create($transaction);

                        $transactionPay['invoice'] = $invoice;
                        $transactionPay['table_id'] = $advancebill->id;
                        $transactionPay['account_id'] = 500;
                        $transactionPay['type'] = 4;
                        $transactionPay['company_id'] = $customer->company_id;
                        $transactionPay['debit'] = $customer->bill_amount;
                        $transactionPay['remark'] = "Internet Bill";
                        $transactionPay['customer_id'] = $customer->id;
                        $transactionPay['created_by'] = 0;
                        AccountTransaction::create($transactionPay);

                        // $transaction['date'] = $customer->start_date;
                        // $transaction['local_id'] = $billingid->id;
                        // $transaction['pay_method_id'] = 500;
                        // $transaction['type'] = 10;
                        // $transaction['company_id'] = $customer->company_id;
                        // $transaction['credit'] = $customer->bill_amount;
                        // $transaction['amount'] = $customer->bill_amount;
                        // $transaction['created_by'] = auth()->id();
                        // Transaction::create($transaction);
                    } else {
                        $newbillcreate[] = [
                            'date_' => date('Y-m-d'),
                            'customer_id' => $customer->id,
                            'company_id' => $customer->company_id,
                            'customer_phone' => $customer->phone,
                            'customer_profile_id' => $customer->m_p_p_p_profile,
                            'customer_billing_amount' => $customer->bill_amount,
                            'biller_name' => $customer->billing_person,
                            'type' => "collection",
                            'status' => "unpaid"
                        ];
                    }
                }
            }
            DB::table('billings')->insert($newbillcreate);
            // DB::table('billings')->insert($advancebill);
        }
    }

    public function smsSend($number, $message)
    {
        $url = "https://sms.solutionsclan.com/api/sms/send";
        $data = [
            "apiKey" => "A000429c348a5db-bab2-4189-a0ad-813e136ccaa4",
            "contactNumbers" => $number,
            "senderId" => "8809612441117",
            "textBody" => $message
        ];

        // Http::post($url, $data);
    }
}
