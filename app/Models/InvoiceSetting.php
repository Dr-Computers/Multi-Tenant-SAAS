<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    use HasFactory;

    public function templateData(){
       return $this->hasOne(InvoiceTemplate::class,'id','template');
    }
}
