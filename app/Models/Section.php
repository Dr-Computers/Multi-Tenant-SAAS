<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function addedSections(){
        return $this->hasOne(CompanySubscription::class,'section_id','id');
    }

}