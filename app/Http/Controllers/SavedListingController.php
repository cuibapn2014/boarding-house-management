<?php

namespace App\Http\Controllers;

use App\Models\SavedListing;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedListingController extends Controller
{
    /**
     * Display a listing of saved boarding houses
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home.index')->with('error', 'Vui lòng đăng nhập để xem tin đã lưu');
        }

        $savedListings = Auth::user()->savedBoardingHouses()
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
                'boarding_houses.created_at',
                'saved_listings.created_at as saved_at'
            )
            ->orderByDesc('saved_listings.created_at')
            ->paginate(20);

        return view('apps.saved-listings', compact('savedListings'));
    }

    /**
     * Save a boarding house listing
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu tin'
            ], 401);
        }

        $request->validate([
            'boarding_house_id' => 'required|exists:boarding_houses,id'
        ]);

        $boardingHouseId = $request->boarding_house_id;

        // Check if already saved
        $exists = SavedListing::where('user_id', Auth::id())
            ->where('boarding_house_id', $boardingHouseId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Tin này đã được lưu trước đó'
            ], 400);
        }

        SavedListing::create([
            'user_id' => Auth::id(),
            'boarding_house_id' => $boardingHouseId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu tin thành công'
        ]);
    }

    /**
     * Remove a saved listing
     */
    public function destroy($boardingHouseId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $deleted = SavedListing::where('user_id', Auth::id())
            ->where('boarding_house_id', $boardingHouseId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tin đã lưu'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ lưu tin'
        ]);
    }

    /**
     * Check if a listing is saved
     */
    public function check($boardingHouseId)
    {
        if (!Auth::check()) {
            return response()->json([
                'saved' => false
            ]);
        }

        $saved = SavedListing::where('user_id', Auth::id())
            ->where('boarding_house_id', $boardingHouseId)
            ->exists();

        return response()->json([
            'saved' => $saved
        ]);
    }

    /**
     * Toggle save status
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu tin'
            ], 401);
        }

        $request->validate([
            'boarding_house_id' => 'required|exists:boarding_houses,id'
        ]);

        $boardingHouseId = $request->boarding_house_id;

        $savedListing = SavedListing::where('user_id', Auth::id())
            ->where('boarding_house_id', $boardingHouseId)
            ->first();

        if ($savedListing) {
            // Unsave
            $savedListing->delete();
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Đã bỏ lưu tin'
            ]);
        } else {
            // Save
            SavedListing::create([
                'user_id' => Auth::id(),
                'boarding_house_id' => $boardingHouseId
            ]);
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Đã lưu tin thành công'
            ]);
        }
    }
}
