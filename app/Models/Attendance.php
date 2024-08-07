<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['emplyee_id', 'date', 'sign_in', 'sign_out', 'company_id'];

    public function employe()
    {
        return $this->belongsTo(Employee::class, 'emplyee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
