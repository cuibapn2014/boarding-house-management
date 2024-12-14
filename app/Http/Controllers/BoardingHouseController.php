<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoardingHouseRequest;
use App\Models\BoardingHouse;
use App\Models\BoardingHouseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class BoardingHouseController extends Controller
{
    //
    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::with([
                'boarding_house_files:id,boarding_house_id,type,url'
            ])
            ->orderByDesc('id')
            ->select(
                'id',
                'title',
                'category',
                'price',
                'status',
                'is_publish',
                'created_at',
            )
            ->paginate(20)
            ->withQueryString();

        return view('apps.boarding-house.index', compact('boardingHouses'));
    }

    public function create()
    {
        return view('apps.boarding-house.create');
    }

    public function store(StoreBoardingHouseRequest $request)
    {
        $tags = array_map(fn($item) => $item->value, json_decode($request->tags));
        try {
            DB::transaction(function () use($request, $tags) {
                $boardingHouse = new BoardingHouse();

                $boardingHouse->title       = trim($request->input('title'));
                $boardingHouse->category    = $request->input('category');
                $boardingHouse->description = trim($request->input('description'));
                $boardingHouse->content     = $request->input('content');
                $boardingHouse->district    = $request->input('district');
                $boardingHouse->ward        = $request->input('ward');
                $boardingHouse->address     = trim($request->input('address'));
                $boardingHouse->phone       = trim($request->input('phone'));
                $boardingHouse->price       = numberRemoveComma($request->input('price'));
                $boardingHouse->status      = $request->input('status');
                $boardingHouse->is_publish  = $request->has('is_publish') && $request->input('is_publish') === 'on';
                $boardingHouse->tags        = implode(', ', $tags);

                $boardingHouse->save();

                foreach($request->file('files', []) as $file) {
                    $resourceType =$file->getMimeType();
                    $uploadedFile = cloudinary()->upload($file->getRealPath(), [
                        'resource_type' => explode('/',$resourceType)[0]
                    ]);

                    $boardingHouseFile = new BoardingHouseFile();
                    $boardingHouseFile->boarding_house_id = $boardingHouse->id;
                    $boardingHouseFile->type = $uploadedFile->getFileType();
                    $boardingHouseFile->public_id = $uploadedFile->getPublicId();
                    $boardingHouseFile->url = $uploadedFile->getSecurePath();
                    $boardingHouseFile->save();
                }
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

        if($boardingHouse->create_by != auth()->id() && auth()->id() != 1) {
            return $this->responseError('Không có quyền chỉnh sửa');
        }

        if(! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }
        $tags = array_map(fn($item) => $item->value, json_decode($request->tags));
        try {
            DB::transaction(function () use($request, $boardingHouse, $tags) {

                $boardingHouse->title       = trim($request->input('title'));
                $boardingHouse->category    = $request->input('category');
                $boardingHouse->description = trim($request->input('description'));
                $boardingHouse->content     = $request->input('content');
                $boardingHouse->district    = $request->input('district');
                $boardingHouse->ward        = $request->input('ward');
                $boardingHouse->address     = trim($request->input('address'));
                $boardingHouse->phone       = trim($request->input('phone'));
                $boardingHouse->price       = numberRemoveComma($request->input('price'));
                $boardingHouse->status      = $request->input('status');
                $boardingHouse->is_publish  = $request->has('is_publish') && $request->input('is_publish') === 'on';
                $boardingHouse->tags        = implode(', ', $tags);

                $boardingHouse->save();

                foreach($request->file('files', []) as $file) {
                    $uploadedFile = cloudinary()->upload($file->getRealPath());

                    $boardingHouseFile = new BoardingHouseFile();
                    $boardingHouseFile->boarding_house_id = $boardingHouse->id;
                    $boardingHouseFile->type = $uploadedFile->getFileType();
                    $boardingHouseFile->public_id = $uploadedFile->getPublicId();
                    $boardingHouseFile->url = $uploadedFile->getSecurePath();
                    $boardingHouseFile->save();
                }
            });
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->responseError();
        }

        return $this->responseSuccess('Chỉnh sửa thành công!');
    }

    public function destroy($id)
    {
        $boardingHouse = BoardingHouse::find($id);

        if(! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        if($boardingHouse->create_by != auth()->id() && auth()->id() != 1) {
            return $this->responseError('Không có quyền xoá!');
        }

        try {
            DB::transaction(function () use($boardingHouse) {
                foreach($boardingHouse?->boarding_house_files ?? [] as $file) {
                    cloudinary()->destroy($file->public_id);
                }

                $boardingHouse?->boarding_house_files()?->delete();
                $boardingHouse->delete();
            });
        } catch(\Exception $ex) {
            Log::error($ex);
            return $this->responseError();
        }

        return $this->responseSuccess('Xoá thành công!');
    }
}
