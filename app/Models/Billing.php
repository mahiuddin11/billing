<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_phone',
        'customer_profile_id',
        'customer_billing_amount',
        'biller_name',
        'biller_phone',
        'payment_method_id',
        'type',
        'pay_amount',
        'partial',
        'discount',
        'description',
        'date_',
        'alert',
        'billing_by',
        'invoice_name',
        'company_id',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(AccountTransaction::class, 'table_id', 'id')->where('company_id', auth()->user()->company_id)->where('type', 4)->orderBy('table_id');
    }


    public function PaymentMethod()
    {
        return $this->belongsTo(Account::class, 'payment_method_id', 'id');
    }
    public function getCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getProfile()
    {
        return $this->belongsTo(MPPPProfile::class, 'customer_profile_id', 'id');
    }
    public function getBiller()
    {
        return $this->belongsTo(User::class, 'billing_by', 'id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'local_id', 'id')->where('type', 10);
    }

    public function getBillinfBy()
    {
        return $this->belongsTo(User::class, 'billing_by', 'id');
    }
}
