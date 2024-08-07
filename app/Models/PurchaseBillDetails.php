<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBillDetails extends Model
{
    use HasFactory;

    protected $fillabel = [
        'purchase_bill_id',
        'item_id',
        'description',
        'unit',
        'qty',
        'rate',
        'vat',
        'from_date',
        'to_date',
        'total',
    ];

    public function getItem()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
