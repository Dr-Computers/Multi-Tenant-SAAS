<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'type',
        'property_id',
        'location',
        'purchase_date',
        'purchase_price',
        'vendor_name',
        'initial_value',
        'current_market_value',
        'accumulated_depreciation',
        'accumulated_depreciation',
        'owner_name',
        'title_deed_number',
        'condition',
        'company_id',
        'status'
    ];
}
