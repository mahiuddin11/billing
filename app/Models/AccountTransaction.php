<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'account_id',
        'type',
        'debit',
        'company_id',
        'credit',
        'remark',
        'table_id',
        'supplier_id',
        'customer_id',
        'created_by',
    ];

    public static function accountInvoice()
    {
        $account = AccountTransaction::latest('id')->pluck('id')->first() ?? "0";
        $invoice_no = 'invoice' . str_pad($account + 1, 5, "0", STR_PAD_LEFT);
        return $invoice_no;
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function resellerBill()
    {
        return $this->belongsTo(BandwidthSaleInvoice::class, 'table_id', 'id');
    }

    public function upstreamBill()
    {
        return $this->belongsTo(PurchaseBill::class, 'table_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function resellerCustomer()
    {
        return $this->belongsTo(BandwidthCustomer::class, 'customer_id', 'id');
    }

    public function providerCustomer()
    {
        return $this->belongsTo(Provider::class, 'customer_id', 'id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'table_id', 'id');
    }
}
