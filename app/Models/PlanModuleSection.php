<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class PlanModuleSection extends Model
{
    public function section()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }
}
