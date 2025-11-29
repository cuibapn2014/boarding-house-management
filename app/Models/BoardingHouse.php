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
        'status',
        'furniture_status',
        'is_publish',
        'tags',
        'completion_id',
        'created_by',
        'updated_by',
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
