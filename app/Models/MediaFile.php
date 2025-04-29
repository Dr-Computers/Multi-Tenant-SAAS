<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MediaFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'folder_id',
        'name',
        'alt',
        'mime_type',
        'size',
        'url',
        'options'
    ];

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            
            get: fn($value) =>  $this->folder_id != 0
                ? $this->folder->path . '/' . $this->url
                : 'uploads/company_' . $this->company_id . '/' . $this->url
        );
    }

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }
}
