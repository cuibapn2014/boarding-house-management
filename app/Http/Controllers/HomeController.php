<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Support\ListingCache;
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
        $ttlSeconds = (int) config('cache.listing_ttl_seconds', 300);
        $nearlyCentreList = [
            'Quận 1',
            'Quận 2',
            'Quận 3',
            'Quận 4',
            'Quận 5',
            'Quận 10',
            'Quận Bình Thạnh'
        ];

        $latestPosts = ListingCache::remember('home:latest-posts', $ttlSeconds, function () {
            return BoardingHouse::with(['boarding_house_files:boarding_house_id,url'])
                ->published()
                ->select(
                    'boarding_houses.id',
                    'boarding_houses.title',
                    'boarding_houses.description',
                    'boarding_houses.category',
                    'boarding_houses.address',
                    'boarding_houses.district',
                    'boarding_houses.ward',
                    'boarding_houses.price',
                    'boarding_houses.status',
                    'boarding_houses.area',
                    'boarding_houses.pushed_at',
                    'boarding_houses.expires_at',
                    'boarding_houses.created_at',
                    'boarding_houses.created_by'
                )
                ->orderByListingPriority('newest')
                ->limit(20)
                ->get();
        });

        $nearlyCentreCity = ListingCache::remember('home:nearly-centre-city', $ttlSeconds, function () use ($nearlyCentreList) {
            return BoardingHouse::with(['boarding_house_files:boarding_house_id,url'])
                ->published()
                ->select(
                    'boarding_houses.id',
                    'boarding_houses.title',
                    'boarding_houses.category',
                    'boarding_houses.district',
                    'boarding_houses.ward',
                    'boarding_houses.price',
                    'boarding_houses.status',
                    'boarding_houses.area',
                    'boarding_houses.pushed_at',
                    'boarding_houses.expires_at',
                    'boarding_houses.created_at',
                    'boarding_houses.created_by'
                )
                ->whereIn('district', $nearlyCentreList)
                ->orderByListingPriority('newest')
                ->limit(10)
                ->get();
        });

        return view('apps.home', compact('latestPosts', 'nearlyCentreList', 'nearlyCentreCity'));
    }
}
