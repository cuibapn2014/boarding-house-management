<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedListing extends Model
{
    use HasFactory;

    protected $table = 'saved_listings';

    protected $fillable = [
        'user_id',
        'boarding_house_id'
    ];

    /**
     * Get the user that saved the listing
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the boarding house that was saved
     */
    public function boardingHouse()
    {
        return $this->belongsTo(BoardingHouse::class);
    }
}
