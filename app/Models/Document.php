<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function file()
    {
        return $this->hasOne(MediaFile::class,'id','file_id');
    }
}
