<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;
    protected $fillable = [
        "queue_mid",
        "queue_name",
        "queue_target",
        "queue_dst",
        "server_id",
        "queue_max_upload",
        "queue_max_download",
        "queue_disabled",
        "amount"
    ];
}
