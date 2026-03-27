<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Support\ListingCache;
use Illuminate\Http\Request;

class RentalHomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $ttlSeconds = (int) config('cache.listing_ttl_seconds', 300);
        $category = (array) $request->input('category', []);
        $price    = (array) $request->input('price', []);
        $district = (array) $request->input('district', []);
        $furnitureStatus = (array) $request->input('furniture_status', []);

        $rangesPrice = array_map(function (string $item) {
            list($min, $max) = explode('-', $item);
            return [intval($min), intval($max)];
        }, $price);

        $cacheKey = 'rental-home:index:' . md5(http_build_query($this->normalizeForCache($request->query())));
        $boardingHouses = ListingCache::remember($cacheKey, $ttlSeconds, function () use (
            $request,
            $category,
            $rangesPrice,
            $district,
            $furnitureStatus
        ) {
            $query = BoardingHouse::query()
                ->published()
                ->select(
                    'boarding_houses.id',
                    'boarding_houses.title',
                    'boarding_houses.category',
                    'boarding_houses.description',
                    'boarding_houses.address',
                    'boarding_houses.district',
                    'boarding_houses.ward',
                    'boarding_houses.price',
                    'boarding_houses.status',
                    'boarding_houses.area',
                    'boarding_houses.created_at',
                    'boarding_houses.created_by'
                )
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('boarding_houses.title', 'like', '%' . trim($request->search) . '%');
                })
                ->when(! empty($category), function ($query) use ($category) {
                    $query->whereIn('boarding_houses.category', $category);
                })
                ->when(! empty($rangesPrice), function ($query) use ($rangesPrice) {
                    $query->where(function ($query) use ($rangesPrice) {
                        foreach ($rangesPrice as $range) {
                            $query->orWhereBetween('boarding_houses.price', $range);
                        }
                    });
                })
                ->when(! empty($district), function ($query) use ($district) {
                    $query->whereIn('boarding_houses.district', $district);
                })
                ->when(! empty($furnitureStatus), function ($query) use ($furnitureStatus) {
                    $query->whereIn('boarding_houses.furniture_status', $furnitureStatus);
                });

            $query->orderByListingPriority($request->input('sort'));

            return $query->paginate(20);
        });

        $boardingHouses->appends($request->query());

        return view('apps.list-rental-home', compact('boardingHouses'));
    }

    public function show($id, $title)
    {
        $boardingHouse = BoardingHouse::published()
                                    ->with([
                                        'user_create:id,firstname,lastname,phone',
                                        'boarding_house_files:id,boarding_house_id,type,url'
                                    ])
                                    ->select(
                                        'id',
                                        'title',
                                        'description',
                                        'content',
                                        'category',
                                        'address',
                                        'district',
                                        'ward',
                                        'price',
                                        'status',
                                        'furniture_status',
                                        'tags',
                                        'phone',
                                        'map_link',
                                        'require_deposit',
                                        'deposit_amount',
                                        'min_contract_months',
                                        'area',
                                        'updated_at',
                                        'created_by'
                                    )
                                    ->find($id);

        if(!$boardingHouse || $title != $boardingHouse->slug) abort(404);

        $ttlSeconds = (int) config('cache.listing_ttl_seconds', 300);
        $boardingHouseRelation = ListingCache::remember(
            "rental-home:related:{$boardingHouse->district}:{$boardingHouse->id}",
            $ttlSeconds,
            function () use ($boardingHouse) {
                return BoardingHouse::published()
                    ->where('boarding_houses.district', $boardingHouse->district)
                    ->where('boarding_houses.id', '!=', $boardingHouse->id)
                    ->where('boarding_houses.status', 'available')
                    ->select(
                        'boarding_houses.id',
                        'boarding_houses.title',
                        'boarding_houses.category',
                        'boarding_houses.district',
                        'boarding_houses.price',
                        'boarding_houses.status',
                        'boarding_houses.area',
                        'boarding_houses.created_at',
                        'boarding_houses.created_by'
                    )
                    ->orderByListingPriority('newest')
                    ->take(8)
                    ->get();
            }
        );

        return view('apps.detail', compact('boardingHouse', 'boardingHouseRelation'));
    }

    private function normalizeForCache(array $params): array
    {
        ksort($params);

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                sort($value);
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
