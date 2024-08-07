<?php

namespace App\Imports;

use App\Models\Billing;
use App\Models\Customer;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BillingImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $customer = Customer::where('username', $row['idip'])->first();
        if (!empty($customer)) {

            $customer->update([
                'advanced_payment' => $customer->advanced_payment + $row['advancepayemnt']
            ]);
        }

        if ($row['mbill'] > $row['received'] && $row['received'] > 0) {
            $status = 'partial';
        } elseif ($row['received'] <= 0) {
            $status = 'unpaid';
        } else {
            $status = 'unpaid';
        }

        $billing = [];
        if (!empty($customer)) {
            $billing = new Billing([
                "customer_id" => $customer->id,
                "customer_phone" => $customer->phone,
                "company_id" => auth()->id(),
                "customer_billing_amount" =>  $row['mbill'],
                "date_" => date('Y-m-d', strtotime($row['paymentdate'])) ?? null,
                "pay_amount" => $row['received'],
                "partial" => $row['mbill'] - $row['received'],
                "discount" => 0,
                "status" => $status,
            ]);
        }

        return $billing;
    }

    // public function headingRow(): int
    // {
    //     return 1;
    // }
}
