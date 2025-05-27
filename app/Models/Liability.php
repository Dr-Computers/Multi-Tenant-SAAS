<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liability extends Model
{
    use HasFactory;
    protected $table='liabilities';
    protected $fillable = [
        'name',            // Liability Name/Description
        'type',            // Liability Type
        'property_id',     // Property ID (nullable)
        'amount',          // Amount of the liability
        'due_date',       // Due Date (nullable)
        'vendor_name',     // Vendor Name (nullable)
        'interest_rate',   // Interest Rate (nullable)
        'payment_terms',   // Payment Terms (nullable)
        'notes',           // Additional Notes (nullable)
        'company_id',
        'status',
        
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
