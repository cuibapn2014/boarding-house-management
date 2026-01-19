<?php

namespace App\Models;

use App\Trait\HasOwnership;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory, HasOwnership;

    protected $table = 'appointments';

    public function boarding_house() 
    {
        return $this->belongsTo(\App\Models\BoardingHouse::class, 'boarding_house_id', 'id');
    }

    public function payment() 
    {
        return $this->belongsTo(\App\Models\Payment::class, 'payment_id', 'id');
    }
}
