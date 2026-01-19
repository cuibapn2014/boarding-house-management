<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class RentalHomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $category = (array) $request->input('category', []);
        $price    = (array) $request->input('price', []);
        $district = (array) $request->input('district', []);
        $furnitureStatus = (array) $request->input('furniture_status', []);

        $rangesPrice = array_map(function(string $item) {
            list($min, $max) = explode('-', $item);
            return [intval($min), intval($max)];
        }, $price);

        $boardingHouses = BoardingHouse::when($request->filled('search'), function($query) use($request) {
                                        $query->where('title', 'like', '%'.$request->search.'%');
                                    })
                                    ->when($request->filled('category'), function($query) use($category) {
                                        $query->whereIn('category', $category);
                                    })
                                    ->when($request->filled('price'), function($query) use($rangesPrice) {
                                        $query->where(function($query) use($rangesPrice) {
                                            $query->whereBetween('price', array_pop($rangesPrice));
                                            foreach($rangesPrice as $range) {
                                                $query->orWhereBetween('price', $range);
                                            }
                                        });
                                    })
                                    ->when($request->filled('district'), function($query) use($district) {
                                        $query->whereIn('district', $district);
                                    })
                                    ->when($request->filled('furniture_status'), function($query) use($furnitureStatus) {
                                        $query->whereIn('furniture_status', $furnitureStatus);
                                    })
                                    ->published()
                                    ->select(
                                        'id',
                                        'title',
                                        'category',
                                        'description',
                                        'address',
                                        'district',
                                        'ward',
                                        'price',
                                        'status',
                                        'area',
                                        'created_at',
                                        'created_by'
                                    )
                                    ->when($request->filled('sort'), function($query) use($request) {
                                        switch($request->sort) {
                                            case 'oldest':
                                                $query->orderBy('id', 'asc');
                                                break;
                                            case 'price_low':
                                                $query->orderBy('price', 'asc');
                                                break;
                                            case 'price_high':
                                                $query->orderBy('price', 'desc');
                                                break;
                                            case 'newest':
                                            default:
                                                $query->orderByDesc('id');
                                                break;
                                        }
                                    }, function($query) {
                                        $query->orderByDesc('id');
                                    })
                                    ->paginate(20)
                                    ->withQueryString();

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

        $boardingHouseRelation = BoardingHouse::published()
                                            ->where('district', $boardingHouse->district)
                                            ->where('id', '!=', $boardingHouse->id)
                                            ->where('status', 'available')
                                            ->select(
                                                'id',
                                                'title',
                                                'category',
                                                'district',
                                                'price',
                                                'status',
                                                'area',
                                                'created_at',
                                                'created_by'
                                            )
                                            ->inRandomOrder()
                                            ->take(8)
                                            ->get();

        return view('apps.detail', compact('boardingHouse', 'boardingHouseRelation'));
    }
}
