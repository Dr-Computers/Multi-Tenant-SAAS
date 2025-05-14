<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterPadTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'header', 'footer', 'image'];

}
