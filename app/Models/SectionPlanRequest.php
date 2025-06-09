<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionPlanRequest extends Model
{
    protected $fillable = [
        'user_id',
        'section_id',
        'duration',
    ];

    protected $appends = ['section_ids'];


    public function section()
    {
        return $this->hasOne('App\Models\Section', 'id', 'section_id');
    }

    public function getSectionIdsAttribute()
    {
        return explode(',', $this->attributes['section_ids'] ?? '');
    }

    public function company()
    {
        return $this->hasOne('App\Models\User', 'id', 'company_id');
    }


    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'company_id');
    }
}
