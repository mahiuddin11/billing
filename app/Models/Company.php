<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'invoice_logo',
        'company_name',
        'website',
        'type',
        'phone',
        'email',
        'apikey',
        'url',
        'secretkey',
        'address',
        'created_by',
        'updated_by',
        'account_info',
        'mobile_banking',
        'prefix',
        'create_msg',
        'billing_exp_msg',
        'bill_paid_msg',
        'bill_exp_warning_msg',
        'month_start_msg',
        'partial_bill_msg'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'company_id', 'id');
    }

    public function getLogoAttribute($val)
    {
        $img = empty($val) ? asset('logo.png') : asset('storage/' . $val);
        return "<img src='{$img}' style='height:55px;' alt='text' />";
    }
    public function getFaviconAttribute($val)
    {
        $img = empty($val) ? asset('img/avatar.png') : asset('storage/' . $val);
        return "<img src='{$img}' style='height:55px;' alt='text' />";
    }
    public function getInvoiceLogoAttribute($val)
    {
        $img = empty($val) ? asset('img/avatar.png') : asset('storage/' . $val);
        return "<img src='{$img}' style='height:55px;' alt='text' />";
    }
}
