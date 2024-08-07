<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MacReselerBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'mac_reseller_id',
        'billing_month',
        'billing_amount',
        'payed',
        'due',
        'recive_by'
    ];
}
