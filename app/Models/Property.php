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
            $property->propertyImages()->detach();
            $property->propertyDocuments()->detach();
            
        });
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(RealestateAmenity::class, 'property_amenities', 'property_id', 'amenity_id');
    }

    public function landmarks(): BelongsToMany
    {
        return $this->belongsToMany(RealestateLandmark::class, 'property_landmarks', 'property_id', 'landmark_id',)->withPivot('landmark_value');
    }

    public function furnishing(): BelongsToMany
    {
        return $this->belongsToMany(RealestateLandmark::class, 'property_furnishing', 'property_id', 'furnishing_id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(RealestateCategory::class, 'property_categories', 'property_id', 'category_id');
    }
    protected function category(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->categories->first() ?: new Category();
            },
        );
    }

    public function propertyImages(): BelongsToMany
    {
        return $this->belongsToMany(MediaFile::class, 'property_images', 'property_id', 'file_id');
    }

    public function propertyDocuments(): BelongsToMany
    {
        return $this->belongsToMany(MediaFile::class, 'property_documents', 'property_id', 'file_id');
    }

    public function units()
    {
        return $this->hasMany(PropertyUnit::class, 'property_id', 'id');
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function ownerDetail()
    {
        return $this->hasOne(Owner::class, 'user_id', 'owner_id');
    }

    public function leases()
    {
        return $this->hasMany(RealestateLease::class, 'property_id');
    }
}
