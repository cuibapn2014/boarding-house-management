<?php

namespace App\Models;

use App\Trait\CommonTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoardingHouse extends Model
{
    use HasFactory, CommonTrait;

    protected $table = 'boarding_houses';

    protected $appends = [
        'thumbnail',
        'location_address',
        'slug'
    ];

    public function user_create() 
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function user_updated() 
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    public function boarding_house_files()
    {
        return $this->hasMany(\App\Models\BoardingHouseFile::class, 'boarding_house_id', 'id');
    }

    public function getThumbnailAttribute() : ?string
    {
        $file = $this->boarding_house_files()
                    ->where('type', 'image')
                    ->first();

        return $file?->url ?? Storage::url('images/image.jpg');
    }

    public function getLocationAddressAttribute() : ?string 
    {
        return "{$this?->ward}, {$this?->district}";
    }

    public function getSlugAttribute() : string
    {
        return Str::slug($this->title);
    }

    /**
     * Get the saved listings for this boarding house
     */
    public function savedListings()
    {
        return $this->hasMany(SavedListing::class);
    }

    /**
     * Get the users who saved this boarding house
     */
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_listings', 'boarding_house_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get count of users who saved this listing
     */
    public function getSavedCountAttribute()
    {
        return $this->savedListings()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('is_publish', true);
    }
}
