<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $nearlyCentreList = [
            'Quận 1',
            'Quận 2',
            'Quận 3',
            'Quận 4',
            'Quận 5',
            'Quận 10',
            'Quận Bình Thạnh'
        ];

        $latestPosts = BoardingHouse::with(['boarding_house_files:boarding_house_id,url'])
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
                                    ->orderByDesc('id')
                                    ->limit(10)
                                    ->get();

        $nearlyCentreCity = BoardingHouse::with(['boarding_house_files:boarding_house_id,url'])
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
                                        ->orderByDesc('id')
                                        ->whereIn('district', $nearlyCentreList)
                                        ->limit(10)
                                        ->get();

        return view('apps.home', compact('latestPosts', 'nearlyCentreList'));
    }
}
