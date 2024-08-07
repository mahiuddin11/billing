<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerFunding extends Model
{
    use HasFactory;

    protected $fillable = [
        'reseller_id',
        'invoice',
        'payable',
        'payed',
        'is_connect',
        'status',
        'month',
    ];
}
