<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMaintenanceRequest extends Model
{

    public function property()
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }

    public function unit()
    {
        return $this->hasOne(PropertyUnit::class, 'id', 'unit_id');
    }

    public function issue()
    {
        return $this->hasOne(MaintenanceTypes::class, 'id', 'issue_type');
    }

    public function maintainer()
    {
        return $this->hasOne(User::class, 'id', 'maintainer_id');
    }

    public function maintenanceRequestAttachments()
    {
        return $this->belongsToMany(MediaFile::class, 'maintenance_request_attachments', 'request_id', 'file_id');
    }

    public function invoice()
    {
        return $this->hasOne(RealestateInvoice::class, 'id', 'invoice_id');
    }
}
