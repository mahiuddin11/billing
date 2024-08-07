<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'id_card',
        'email',
        'status',
        'user_id',
        'dob',
        'gender',
        'personal_phone',
        'office_phone',
        'marital_status',
        'nid',
        'last_in_time',
        'reference',
        'experience',
        'present_address',
        'permanent_address',
        'department_id',
        'designation_id',
        'achieved_degree',
        'institution',
        'passing_year',
        'salary',
        'join_date',
        'image',
        'emp_signature',
        'updated_by',
        'created_by',
        'deleted_by',
        'over_time_is',
        'blood_group',
        'is_login',
    ];

    public function employelist()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
