<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Property extends Model
{
    protected static function booted(): void
    {
        static::deleting(function (Property $property) {
            $property->categories()->detach();
            $property->features()->detach();
            $property->facilities()->detach();
            $property->furnishing()->detach();
        });
    }
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(RealestateLandmark::class, 're_property_features', 'property_id', 'feature_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->morphToMany(RealestateAmenity::class, 'reference', 're_facilities_distances')->withPivot('distance');
    }

    public function furnishing(): BelongsToMany
    {
        return $this->belongsToMany(RealestateLandmark::class, 're_property_furnishing', 'property_id', 'furnishing_id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 're_property_categories');
    }

}