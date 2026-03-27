<?php

namespace App\Http\Controllers;

use App\Support\ListingCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    public function clearListingCache(Request $request): JsonResponse
    {
        $token = (string) env('CACHE_CLEAR_TOKEN', '');
        $inputToken = (string) $request->header('X-CACHE-CLEAR-TOKEN', $request->input('token', ''));

        if ($token === '' || !hash_equals($token, $inputToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $cleared = ListingCache::clearAll();

        return response()->json([
            'success' => true,
            'message' => 'Listing cache cleared',
            'cleared_keys' => $cleared,
        ]);
    }
}
