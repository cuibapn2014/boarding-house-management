<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class RentalHomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::orderByDesc('id')
                                    ->select(
                                        'id',
                                        'title',
                                        'category',
                                        'district',
                                        'ward',
                                        'price',
                                        'status',
                                        'created_at'
                                    )
                                    ->paginate(20)
                                    ->withQueryString();

        return view('apps.list-rental-home', compact('boardingHouses'));
    }
}
