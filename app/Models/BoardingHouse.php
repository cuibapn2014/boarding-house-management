<?php

namespace App\Models;

use App\Trait\CommonTrait;
use App\Trait\HasOwnership;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingHouse extends Model
{
    use HasFactory, CommonTrait, HasOwnership;

    protected $table = 'boarding_houses';

    protected $fillable = [
        'title',
        'category',
        'description',
        'content',
        'district',
        'ward',
        'address',
        'map_link',
        'phone',
        'price',
        'require_deposit',
        'deposit_amount',
        'min_contract_months',
        'area',
        'status',
        'furniture_status',
        'is_publish',
        'listing_days',
        'published_at',
        'expires_at',
        'pushed_at',
        'tags',
        'meta_title',
        'meta_description',
        'canonical_url',
        'completion_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'pushed_at' => 'datetime',
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

    public function canEdit(): bool
    {
        return $this->created_by === auth()->id() || auth()->id() === 1;
    }

    public function canDelete(): bool
    {
        return $this->created_by === auth()->id() || auth()->id() === 1;
    }
}
