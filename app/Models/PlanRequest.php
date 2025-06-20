<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanRequest extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'duration',
    ];

    public function plan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\User', 'id', 'company_id');
    }
}
