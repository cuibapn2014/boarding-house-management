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

        $rangesPrice = array_map(function(string $item) {
            list($min, $max) = explode('-', $item);
            return [intval($min), intval($max)];
        }, $price);

        $boardingHouses = BoardingHouse::when($request->filled('category'), function($query) use($category) {
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
                                    ->select(
                                        'id',
                                        'title',
                                        'category',
                                        'address',
                                        'district',
                                        'ward',
                                        'price',
                                        'status',
                                        'created_at'
                                    )
                                    ->groupBy('id')
                                    ->orderByDesc('id')
                                    ->paginate(20)
                                    ->withQueryString();

        return view('apps.list-rental-home', compact('boardingHouses'));
    }

    public function show($id, $title)
    {
        $boardingHouse = BoardingHouse::find($id, [
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
                                            'updated_at',
                                            'created_by'
                                        ]);

        if(!$boardingHouse || $title != $boardingHouse->slug) abort(404);

        $boardingHouseRelation = BoardingHouse::where('district', $boardingHouse->district)
                                            ->select(
                                                'id',
                                                'title',
                                                'category',
                                                'district',
                                                'price',
                                                'status',
                                            )
                                            ->inRandomOrder()
                                            ->take(6)
                                            ->get();

        return view('apps.detail', compact('boardingHouse', 'boardingHouseRelation'));
    }
}
