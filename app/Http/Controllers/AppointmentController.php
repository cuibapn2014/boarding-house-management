<?php

namespace App\Http\Controllers;

use App\Constants\SystemDefination;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $statuses = SystemDefination::APPOINTMENT_STATUS;

        $listingQuery = BoardingHouse::query()->orderByDesc('id');
        if (! auth()->user()->is_admin) {
            $listingQuery->where('created_by', auth()->id());
        }
        $listingOptions = $listingQuery->limit(300)->pluck('title', 'id');

        $appointments = Appointment::query()
            ->with([
                'boarding_house:id,title,address,ward,district,created_by',
            ])
            ->when(! auth()->user()->is_admin, function ($query) {
                $query->whereHas('boarding_house', function ($q) {
                    $q->where('created_by', auth()->id());
                });
            })
            ->when($request->filled('status') && array_key_exists($request->status, $statuses), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.trim($request->q).'%';
                $query->where(function ($q) use ($term) {
                    $q->where('customer_name', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($request->filled('boarding_house_id'), function ($query) use ($request) {
                $houseId = (int) $request->boarding_house_id;
                if (auth()->user()->is_admin) {
                    $query->where('boarding_house_id', $houseId);
                } else {
                    $query->whereHas('boarding_house', function ($q) use ($houseId) {
                        $q->where('id', $houseId)->where('created_by', auth()->id());
                    });
                }
            })
            ->orderByDesc('appointment_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->only(['q', 'status', 'boarding_house_id']));

        return view('apps.appointment.index', compact('appointments', 'statuses', 'listingOptions'));
    }
}
