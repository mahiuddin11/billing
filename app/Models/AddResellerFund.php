<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddResellerFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'reseller_id',
        'recive_by',
        'create_by',
        'payment_id',
        'account_id',
        'date',
        'fund',
        'payed',
        'due',
        'note',
    ];

    public function macreseller()
    {
        return $this->belongsTo(MacReseller::class, 'reseller_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recive_by');
    }
}
