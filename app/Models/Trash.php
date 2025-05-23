<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'type',
        'deleted_id',
        'name',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
