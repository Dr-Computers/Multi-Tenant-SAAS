<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'name', 'price', 'duration'];

    public function addedSections()
    {
        return $this->hasOne(CompanySubscription::class, 'section_id', 'id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'section_permissions', 'section_id', 'permission_id');
    }
}
