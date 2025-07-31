<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingHouseFile extends Model
{
    use HasFactory;

    public function boarding_house()
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }
}
