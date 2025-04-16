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
            $property->landmarks()->detach();
            $property->amenities()->detach();
            $property->furnishing()->detach();
        });
    }
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(RealestateAmenity::class, 'property_amenities', 'property_id', 'amenity_id');
    }

    public function landmarks(): BelongsToMany
    {
        return $this->morphToMany(RealestateLandmark::class, 'property_landmarks')->withPivot('landmark_value');
    }

    public function furnishing(): BelongsToMany
    {
        return $this->belongsToMany(RealestateLandmark::class, 'property_furnishing', 'property_id', 'furnishing_id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(RealestateCategory::class, 'property_categories', 'property_id', 'category_id');
    }

}