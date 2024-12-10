<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoardingHouseRequest;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class BoardingHouseController extends Controller
{
    //
    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::orderByDesc('id')
            ->paginate(2)
            ->withQueryString();
        return view('apps.boarding-house.index', compact('boardingHouses'));
    }

    public function create()
    {
        return view('apps.boarding-house.create');
    }

    public function store(StoreBoardingHouseRequest $request)
    {
        try {
            DB::transaction(function () use($request) {
                $boardingHouse = new BoardingHouse();

                $boardingHouse->title = trim($request->input('title'));
                $boardingHouse->description = $request->input('description');
                $boardingHouse->price = numberRemoveComma($request->input('price'));
                $boardingHouse->status = $request->input('status');
                $boardingHouse->is_publish = $request->has('is_publish') && $request->input('is_publish') === 'on';

                $boardingHouse->save();
            });
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->responseError();
        }

        return $this->responseSuccess('Thêm mới thành công!');
    }

    public function edit($id) {
        $boardingHouse = BoardingHouse::find($id);

        if(! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        return view('apps.boarding-house.edit', compact('boardingHouse'));
    }

    public function update(StoreBoardingHouseRequest $request, $id) 
    {
        $boardingHouse = BoardingHouse::find($id);

        if(! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }
        
        try {
            DB::transaction(function () use($request, $boardingHouse) {

                $boardingHouse->title = trim($request->input('title'));
                $boardingHouse->description = $request->input('description');
                $boardingHouse->price = numberRemoveComma($request->input('price'));
                $boardingHouse->status = $request->input('status');
                $boardingHouse->is_publish = $request->has('is_publish') && $request->input('is_publish') === 'on';

                $boardingHouse->save();
            });
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->responseError();
        }

        return $this->responseSuccess('Chỉnh sửa thành công!');
    }
}
