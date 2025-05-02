<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestatePaymentsPayable extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'user_id',
        'amount',
        'for_reason',
        'bank_account_id',
        'notes',
        'pay_to'
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
