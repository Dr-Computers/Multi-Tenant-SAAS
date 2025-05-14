<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
}
