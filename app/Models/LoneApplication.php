<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoneApplication extends Model
{
    use HasFactory;

    protected $fillable = [

        'employee_id',
        'company_id',
        'amount',
        'lone_adjustment',
        'image',
        'reason',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
