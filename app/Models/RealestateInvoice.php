<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateInvoice extends Model
{
    use HasFactory;
    protected $table = 'realestate_invoices';

    protected $fillable = [
        'invoice_id',
        'property_id',
        'unit_id',
        'invoice_month',
        'end_date',
        'status',
        'notes',
        'parent_id',
        'invoice_period',
        'invoice_period_end_date',
        'created_in_month',
        'tenant_id',
        'invoice_type',
    ];
}
