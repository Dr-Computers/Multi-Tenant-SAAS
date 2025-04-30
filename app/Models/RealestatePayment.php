<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestatePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'transaction_id',
        'payment_type',
        'payment_for',
        'amount',
        'payment_date',
        'receipt',
        'receipt_number',
        'parent_id',
        'notes',
        'cheque_id',
        'bank_account_id',
        'tenant_id',
        'unit_id',
        'property_id',
        'type',
    ];
    public function invoice()
{
    return $this->belongsTo(RealestateInvoice::class, 'invoice_id'); // Assuming 'invoice_id' is the foreign key
}
public function account()
{
    return $this->belongsTo(BankAccount::class,'bank_account_id');
}
public function unit()
{
    return $this->belongsTo(PropertyUnit::class, 'unit_id');
}

public function property()
{
    return $this->belongsTo(Property::class, 'property_id');
}
}
