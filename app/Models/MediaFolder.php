<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'parent_id',
        'path',
    ];

    public function parent()
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }
    public function files()
    {
        return $this->hasMany(MediaFile::class, 'folder_id');
    }
}
