<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateInvoiceItem extends Model
{
    use HasFactory;
    protected $table = 'realestate_invoice_items';

    protected $fillable = [
        'invoice_id',
        'invoice_type',
        'amount',
        'description',
        'tax_amount',
        'grand_amount',
        'vat_inclusion',
    ];

    // Optional: If you want to define the relationship
    public function invoice()
    {
        return $this->belongsTo(RealestateInvoice::class, 'invoice_id');
    }
}
