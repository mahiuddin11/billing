<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_details_id',
        'product_id',
        'columns_one',
        'columns_two',
        'columns_three',
        'columns_four',
        'columns_five',
        'status'
    ];
}
