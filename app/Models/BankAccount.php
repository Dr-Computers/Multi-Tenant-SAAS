<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'holder_name',
        'bank_name',
        'account_number',
        'account_type',
        'chart_account_id',
        'opening_balance',
        'closing_balance',
        'contact_number',
        'phone',
        'email',
        'bank_address',
        'bank_branch',
        'created_by',
        'company_id'
    ];
    

    public function chartAccount()
    {
        return $this->hasOne('App\Models\ChartOfAccount', 'id', 'chart_account_id');
    }

}

