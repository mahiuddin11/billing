<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomBill extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'date',
        'total',
        'created_by',
        'updated_by',
    ];

    public function getCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function getDetails()
    {
        return $this->hasMany(CustomBillDetail::class, 'custom_bill_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
