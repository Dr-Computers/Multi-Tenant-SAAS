<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/PropertyUnit.php
class PropertyUnit extends Model
{
    protected $fillable = [
        'name', 'kitchen', 'bed_rooms', 'bath_rooms', 'balconies',
        'other_rooms', 'registration_no', 'rent_type', 'price',
        'deposite_type', 'deposite_amount', 'late_fee_type', 'late_fee_amount',
        'incident_reicept_amount', 'notes', 'flooring', 'price_included',
        'youtube_video', 'thumbnail_image', 'status', 'property_id',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
