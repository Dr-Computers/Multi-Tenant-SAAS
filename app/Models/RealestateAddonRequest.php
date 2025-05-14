<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateAddonRequest extends Model
{

    public function company(){
        return $this->hasOne(User::class,'id','company_id');
    }
}