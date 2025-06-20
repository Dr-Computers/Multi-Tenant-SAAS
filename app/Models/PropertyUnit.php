<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    protected $fillable = [
        'name',
        'kitchen',
        'bed_rooms',
        'bath_rooms',
        'balconies',
        'other_rooms',
        'registration_no',
        'rent_type',
        'price',
        'deposite_type',
        'deposite_amount',
        'late_fee_type',
        'late_fee_amount',
        'incident_reicept_amount',
        'notes',
        'flooring',
        'price_included',
        'youtube_video',
        'thumbnail_image',
        'status',
        'property_id',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function tenants()
    {
        $lease = RealestateLease::where('unit_id', $this->id)->where('status', 'active')->first(); // Ensure lease is active
        return $lease ? $lease->tenant : null;
    }
    public function totalDueAmount()
    {
        $invoices = RealestateInvoice::where('unit_id', $this->id)->get(); // Fetch all invoices for the unit
        $total = $invoices->sum(function ($invoice) {
            return $invoice->getInvoiceDueAmount(); // Assuming this method returns the due amount
        });
        return $total; // Format the total before returning
    }

    public function totalAmount()
    {
        $invoices = RealestateInvoice::where('unit_id', $this->id)->get(); // Fetch all invoices for the unit
        $total = $invoices->sum(function ($invoice) {
            return $invoice->getInvoiceSubTotalAmount(); // Assuming this method returns the due amount
        });
        return $total; // Format the total before returning
    }

    public function activeLease()
    {
        // return $this->hasOne(Lease::class, 'unit_id')->where('status', 'active');
        return $this->hasOne(RealestateLease::class, 'unit_id')
            ->where('status', '!=', 'canceled')
            ->where('status', '!=', 'awaiting_activation');
        // return $this->hasOne(Lease::class, 'unit_id')
        //         ->where('status', 'active')
        //         ->latest() // Orders by created_at DESC by default
        //         ->first(); // Retrieves only the latest record

    }
    public function getUnitInvoice()
    {
        return RealestateInvoice::where('unit_id', $this->id)->exists();
    }

    public function invoices()
    {
        // Get all invoices associated with the unit
        $invoices = RealestateInvoice::where('unit_id', $this->id)->get();

        // If there are invoices, return their invoice_ids
        return $invoices->pluck('invoice_id'); // Returns an array of invoice_ids
    }

    public function lease()
    {
        return $this->hasOne(RealestateLease::class, 'unit_id', 'id');
    }

   

    public function propertyUnitImages()
    {
        return $this->belongsToMany(MediaFile::class, 'property_unit_images', 'property_id', 'file_id');
    }
}
