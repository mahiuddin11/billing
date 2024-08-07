<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomBillDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_bill_id',
        'service_name',
        'qty',
        'amount',
    ];
}
