<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_account_id',
        'opening_balance',
        'transaction_amount',
        'closing_balance',
        'transaction_type',
        'transaction_date',
        'reference',
        'description',
        'transaction_id',
    ];
    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
