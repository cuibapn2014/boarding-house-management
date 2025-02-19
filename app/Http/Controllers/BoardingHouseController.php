<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\StoreBoardingHouseRequest;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Models\BoardingHouseFile;
use App\Services\TelegramService;
use App\Utils\ChatGptUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class BoardingHouseController extends Controller
{
    //
    private TelegramService $telegramService;
    private ChatGptUtils $chatGptUtils;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
        $this->chatGptUtils = new ChatGptUtils();
    }

    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::with([
                'boarding_house_files:id,boarding_house_id,type,url'
            ])
            ->when($request->filled('byTitle'), function($query) use($request) {
                $query->where('title', 'like', '%'.$request->byTitle.'%');
            })
            ->when($request->filled('byCategory'), function($query) use($request) {
                $query->where('category', $request->byCategory);
            })
            ->when($request->filled('byFromPrice'), function($query) use($request) {
                $query->where('price', '>=', numberRemoveComma($request->byFromPrice));
            })
            ->when($request->filled('byToPrice'), function($query) use($request) {
                $query->where('price', '<=', numberRemoveComma($request->byToPrice));
            })
            ->when($request->filled('byStatus'), function($query) use($request) {
                $query->where('status', $request->byStatus);
            })
            ->when($request->filled('byPublish'), function($query) use($request) {
                $query->where('is_publish', $request->byPublish);
            })
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

    public function create(Request $request)
    {
        $boardingHouse = BoardingHouse::find($request->input('id'));

        if($boardingHouse) {
            return view('apps.boarding-house.clone', compact('boardingHouse'));
        }

        return view('apps.boarding-house.create');
    }

    public function store(StoreBoardingHouseRequest $request)
    {
        $tags = $request->filled('tags') ? array_map(fn($item) => $item->value, json_decode($request->tags)) : [];
        $message = trim($request->input('content')) . "\n Hãy viết lại cái mô tả trên sao cho seo được điểm tốt. Lưu ý không viết kiểu markdown. Dùng các thẻ của HTML để biểu diễn các xuống dòng hay icon chẳng hạn. Có thể dùng emoji cho sinh động cũng được";
        $messageTag = trim($request->input('content')) . "\n Hãy tạo ra những keywords hiệu quả cho bài viết này giúp tôi, những từ khoá liên quan cũng được. Response chỉ trả lời kết quả không cần giải thích";
        $response = $this->chatGptUtils->sendMessageUsingChat($message);
        $responseTags = $this->chatGptUtils->sendMessageUsingChat($messageTag);

        try {
            DB::transaction(function () use($request, $tags, $response, $responseTags) {
                $boardingHouse = new BoardingHouse();
                $tags = $responseTags ? $responseTags?->choices[0]?->message?->content : implode(', ', $tags);
                $tags = str_replace("\n", ", ", $tags);
                $tags = str_replace("-", "", $tags);

                $boardingHouse->title       = trim($request->input('title'));
                $boardingHouse->category    = $request->input('category');
                $boardingHouse->description = $request->input('description');
                $boardingHouse->content     = $response ? $response?->choices[0]?->message?->content : trim($request->input('content'));
                $boardingHouse->district    = $request->input('district');
                $boardingHouse->ward        = $request->input('ward');
                $boardingHouse->address     = trim($request->input('address'));
                $boardingHouse->phone       = trim($request->input('phone'));
                $boardingHouse->price       = numberRemoveComma($request->input('price'));
                $boardingHouse->status      = $request->input('status');
                $boardingHouse->is_publish  = $request->has('is_publish') && $request->input('is_publish') === 'on';
                $boardingHouse->tags        = $tags;
                $boardingHouse->completion_id = $response->id;

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

    public function createAppointment($id)
    {
        return view('apps.boarding-house.create_appointment', compact('id'));
    }

    public function storeAppointment(StoreAppointmentRequest $request, $id)
    {
        $boardingHouse = BoardingHouse::find($id);
        
        if(! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        try {
            DB::transaction(function() use($request, $boardingHouse, $id) {
                $appointment = new Appointment();
                $appointment->customer_name = trim($request->input('customer_name'));
                $appointment->phone = trim($request->input('phone'));
                $appointment->total_person = $request->input('total_person');
                $appointment->total_bike = $request->input('total_bike');
                $appointment->boarding_house_id = $id;
                $appointment->move_in_date = convertDateWithFormat($request->input('move_in_date'), 'd/m/Y');
                $appointment->status = 'WAITING_CONFIRM';
                $appointment->note = $request->input('note');
                $appointment->appointment_at = convertDateWithFormat($request->input('appointment_at'), 'd/m/Y H:i', 'Y-m-d H:i');
                $appointment->save();

                $messageNotify = "CUỘC HẸN XEM PHÒNG VỪA ĐƯỢC TẠO".PHP_EOL.PHP_EOL
                    ."- Ngày giờ hẹn xem phòng: ".date('d/m/Y H:i', strtotime($appointment->appointment_at)).PHP_EOL
                    ."- Họ tên khách: {$appointment->customer_name}".PHP_EOL
                    ."- SĐT/Zalo: {$appointment->phone}".PHP_EOL
                    ."- Tổng người ở: {$appointment->total_person}".PHP_EOL
                    ."- Tổng xe: {$appointment->total_bike}".PHP_EOL
                    ."- Ngày chuyển vào dự kiến: ".($appointment->move_in_date ? date('d/m/Y', strtotime($appointment->move_in_date)) : 'Không rõ').PHP_EOL
                    ."- Địa chỉ: {$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}".PHP_EOL
                    ."- Post: {$boardingHouse->title}".PHP_EOL
                    ."- ID Post: {$boardingHouse->id}".PHP_EOL
                    ."- Ghi chú: {$appointment->note}".PHP_EOL;

                $this->telegramService->sendMessage($messageNotify);
            });
        } catch(\Exception $ex) {
            Log::error($ex);
            return $this->responseError();
        }

        return $this->responseSuccess('Đã thêm cuộc hẹn mới!');
    }
}
