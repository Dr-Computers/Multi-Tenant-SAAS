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

    public function section()
    {
        return $this->hasOne('App\Models\Section', 'id', 'section_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
