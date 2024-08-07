<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VlanInterface extends Model
{
    use HasFactory;

    protected $fillable = [
        'mid',
        'name',
        'type',
        'server_id',
        'mtu',
        'actual_mtu',
        'running',
        'disabled',
    ];
}
