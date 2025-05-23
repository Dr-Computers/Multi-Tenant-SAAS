<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateLease extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'property',
        'unit',
        'lease_start_date',
        'lease_end_date',
        'free_period_start',
        'free_period_end',
        'unit_price',
        'status',
        'previous_lease_id',
        'renewal_option',
        'rent_increase',
        'security_deposit',
        'payment_frequency',
        'notes',
        'created_by',
        'updated_by',
        'property_id',
        'unit_id',
        'cancellation_date',
        'property_number',
        'contract_number',
        'no_of_payments',
        'cheque_payment_fee',
        'tawtheeq_fees',
        'new_managemenmt_contract_fees',
        'renewal_status',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function cheques()
    {
        return $this->hasMany(RealestateChequeDetail::class, 'lease_id', 'id');
    }

    public function unitDetails()
    {
        return $this->hasOne(PropertyUnit::class, 'id', 'unit_id');
    }
}
