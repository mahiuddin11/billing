<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'bill_no',
        'billing_month',
        'payment_due',
        'invoice_no',
        'attachment',
        'note',
        'account_id',
        'created_by',
        'total',
        'discount',
        'payed',
        'due',
        'updated_by',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function billDetails()
    {
        return $this->hasMany(PurchaseBillDetails::class, 'purchase_bill_id');
    }
}
