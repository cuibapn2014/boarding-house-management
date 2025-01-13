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
}
