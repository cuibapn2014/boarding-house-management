<?php

namespace App\Http\Controllers;

use App\Constants\SystemDefination;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\StoreBoardingHouseRequest;
use App\Models\Appointment;
use App\Models\BoardingHouse;
use App\Models\BoardingHouseFile;
use App\Models\ServicePayment;
use App\Services\Contracts\ServicePaymentServiceInterface;
use App\Services\TelegramService;
use App\Utils\ChatGptUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoardingHouseController extends Controller
{
    private TelegramService $telegramService;

    private ChatGptUtils $chatGptUtils;

    public function __construct(
        TelegramService $telegramService,
        protected ServicePaymentServiceInterface $servicePaymentService
    ) {
        $this->telegramService = $telegramService;
        $this->chatGptUtils = new ChatGptUtils;
    }

    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::with([
            'boarding_house_files:id,boarding_house_id,type,url',
            'user_create:id,firstname,lastname,email,phone,avatar',
        ])
            ->when($request->filled('byTitle'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->byTitle.'%');
            })
            ->when($request->filled('byCategory'), function ($query) use ($request) {
                $query->where('category', $request->byCategory);
            })
            ->when($request->filled('byFromPrice'), function ($query) use ($request) {
                $query->where('price', '>=', numberRemoveComma($request->byFromPrice));
            })
            ->when($request->filled('byToPrice'), function ($query) use ($request) {
                $query->where('price', '<=', numberRemoveComma($request->byToPrice));
            })
            ->when($request->filled('byStatus'), function ($query) use ($request) {
                $query->where('status', $request->byStatus);
            })
            ->when($request->filled('byFurnitureStatus'), function ($query) use ($request) {
                $query->where('furniture_status', $request->byFurnitureStatus);
            })
            ->when($request->filled('byPublish'), function ($query) use ($request) {
                $query->where('is_publish', $request->byPublish);
            })
            ->when($request->filled('byPushTop'), function ($query) use ($request) {
                $v = $request->byPushTop;
                if ($v === 'pushed') {
                    $query->whereNotNull('pushed_at')->where('expires_at', '>', now());
                } elseif ($v === 'not_pushed') {
                    $query->where(function ($q) {
                        $q->whereNull('pushed_at')->orWhere('expires_at', '<=', now());
                    });
                } elseif ($v === 'expiring_soon') {
                    $days = (int) config('boarding_house.push_expiring_warn_days', 3);
                    $query->whereNotNull('pushed_at')
                        ->where('expires_at', '>', now())
                        ->where('expires_at', '<=', now()->copy()->addDays($days));
                }
            })
            ->bySelf()
            ->orderByDesc('id')
            ->select(
                'id',
                'title',
                'category',
                'price',
                'status',
                'furniture_status',
                'is_publish',
                'listing_days',
                'expires_at',
                'pushed_at',
                'view_count',
                'created_at',
                'created_by',
            )
            ->paginate(20)
            ->withQueryString();

        return view('apps.boarding-house.index', compact('boardingHouses'));
    }

    public function create(Request $request)
    {
        $boardingHouse = BoardingHouse::find($request->input('id'));

        if ($boardingHouse) {
            return view('apps.boarding-house.clone', compact('boardingHouse'));
        }

        return view('apps.boarding-house.create');
    }

    public function store(StoreBoardingHouseRequest $request)
    {
        $isPublish = $request->has('is_publish') && $request->input('is_publish') === 'on';

        // Tin nháp không giới hạn số lượng

        try {
            $tags = $request->filled('tags') ? array_map(fn ($item) => $item->value, json_decode($request->tags)) : [];
            $content = trim($request->input('content'));
            $optimizedContent = $this->optimizeContentWithAI($content);
            $optimizedTags = $this->generateTagsWithAI($content, $tags);

            $boardingHouse = null;
            DB::transaction(function () use ($request, $optimizedContent, $optimizedTags, $isPublish, &$boardingHouse) {
                $boardingHouse = $this->createBoardingHouse($request, $optimizedContent, $optimizedTags, $isPublish);
                $this->uploadFiles($request, $boardingHouse->id);
            });

            return $this->responseSuccess($isPublish ? 'Đăng tin thành công!' : 'Lưu nháp thành công!');
        } catch (\Exception $ex) {
            Log::error('Error creating boarding house: '.$ex->getMessage(), [
                'trace' => $ex->getTraceAsString(),
            ]);

            return $this->responseError('Có lỗi xảy ra khi tạo nhà trọ. Vui lòng thử lại.');
        }
    }

    /**
     * Optimize content with AI
     */
    private function optimizeContentWithAI(string $content): ?string
    {
        if (! auth()->user()->is_admin || auth()->user()->plan_current === 'free') {
            return $content;
        }

        try {
            $message = $content."\n Hãy viết lại cái mô tả trên sao cho seo được điểm tốt. Lưu ý không viết kiểu markdown. Dùng các thẻ của HTML để biểu diễn các xuống dòng hay icon chẳng hạn. Có thể dùng emoji cho sinh động cũng được";
            $response = $this->chatGptUtils->sendMessageUsingChat($message);

            return $response?->choices[0]?->message?->content ?? $content;
        } catch (\Exception $e) {
            Log::warning('AI content optimization failed: '.$e->getMessage());

            return $content;
        }
    }

    /**
     * Generate tags with AI
     */
    private function generateTagsWithAI(string $content, array $defaultTags): string
    {
        if (! auth()->user()->is_admin || auth()->user()->plan_current === 'free') {
            return implode(', ', $defaultTags);
        }

        try {
            $message = $content."\n Hãy tạo ra những keywords hiệu quả cho bài viết này giúp tôi, những từ khoá liên quan cũng được. Response chỉ trả lời kết quả không cần giải thích";
            $response = $this->chatGptUtils->sendMessageUsingChat($message);

            if ($response) {
                $tags = $response?->choices[0]?->message?->content;
                $tags = str_replace(["\n", '-'], [', ', ''], $tags);

                return $tags;
            }
        } catch (\Exception $e) {
            Log::warning('AI tags generation failed: '.$e->getMessage());
        }

        return implode(', ', $defaultTags);
    }

    /**
     * Create boarding house record. Publish chỉ là toggle, không liên quan thời gian hiển thị.
     */
    private function createBoardingHouse($request, $content, $tags, bool $isPublish = false): BoardingHouse
    {
        $boardingHouse = new BoardingHouse;
        $boardingHouse->title = trim($request->input('title'));
        $boardingHouse->category = $request->input('category');
        $boardingHouse->description = trim($request->input('description') ?: $request->input('title'));
        $boardingHouse->content = $content;
        $boardingHouse->district = $request->input('district');
        $boardingHouse->ward = $request->input('ward');
        $boardingHouse->address = trim($request->input('address'));
        $boardingHouse->map_link = $request->filled('map_link') ? trim($request->input('map_link')) : null;
        $boardingHouse->phone = trim($request->input('phone'));
        $boardingHouse->meta_title = $request->filled('meta_title') ? trim($request->input('meta_title')) : null;
        $boardingHouse->meta_description = $request->filled('meta_description') ? trim($request->input('meta_description')) : null;
        $boardingHouse->canonical_url = $request->filled('canonical_url') ? trim($request->input('canonical_url')) : null;
        $boardingHouse->price = numberRemoveComma($request->input('price'));
        $boardingHouse->require_deposit = $request->has('require_deposit') && $request->input('require_deposit') === 'on';
        $boardingHouse->deposit_amount = $request->filled('deposit_amount') ? numberRemoveComma($request->input('deposit_amount')) : null;
        $boardingHouse->min_contract_months = $request->filled('min_contract_months') ? (int) $request->input('min_contract_months') : null;
        $boardingHouse->area = $request->filled('area') ? (int) $request->input('area') : null;
        $boardingHouse->status = $request->input('status');
        $boardingHouse->furniture_status = $request->input('furniture_status');
        $boardingHouse->is_publish = $isPublish;
        $boardingHouse->tags = $tags;
        $boardingHouse->save();

        return $boardingHouse;
    }

    /**
     * Upload files to Cloudinary
     */
    private function uploadFiles($request, int $boardingHouseId): void
    {
        foreach ($request->file('files', []) as $file) {
            $resourceType = explode('/', $file->getMimeType())[0];
            $uploadedFile = cloudinary()->upload($file->getRealPath(), [
                'resource_type' => $resourceType,
            ]);

            $boardingHouseFile = new BoardingHouseFile;
            $boardingHouseFile->boarding_house_id = $boardingHouseId;
            $boardingHouseFile->type = $uploadedFile->getFileType();
            $boardingHouseFile->public_id = $uploadedFile->getPublicId();
            $boardingHouseFile->url = $uploadedFile->getSecurePath();
            $boardingHouseFile->save();
        }
    }

    public function edit($id)
    {
        $boardingHouse = BoardingHouse::with('boarding_house_files')->find($id);

        if (! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        $pushDurationOptions = SystemDefination::LISTING_DURATION_POINTS;
        $userPoints = (int) (auth()->user()->points ?? 0);

        return view('apps.boarding-house.edit', compact('boardingHouse', 'pushDurationOptions', 'userPoints'));
    }

    public function update(StoreBoardingHouseRequest $request, $id)
    {
        $boardingHouse = BoardingHouse::find($id);

        if (! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        if (! $boardingHouse->canEdit()) {
            return $this->responseError('Không có quyền chỉnh sửa');
        }

        $wantPublish = $request->has('is_publish') && $request->input('is_publish') === 'on';

        try {
            $tags = $request->filled('tags') ? array_map(fn ($item) => $item->value, json_decode($request->tags)) : [];

            DB::transaction(function () use ($request, $boardingHouse, $tags, $wantPublish) {
                $boardingHouse->title = trim($request->input('title'));
                $boardingHouse->category = $request->input('category');
                $boardingHouse->description = trim($request->input('description') ?: $request->input('title'));
                $boardingHouse->content = trim($request->input('content'));
                $boardingHouse->district = $request->input('district');
                $boardingHouse->ward = $request->input('ward');
                $boardingHouse->address = trim($request->input('address'));
                $boardingHouse->map_link = $request->filled('map_link') ? trim($request->input('map_link')) : null;
                $boardingHouse->phone = trim($request->input('phone'));
                $boardingHouse->meta_title = $request->filled('meta_title') ? trim($request->input('meta_title')) : null;
                $boardingHouse->meta_description = $request->filled('meta_description') ? trim($request->input('meta_description')) : null;
                $boardingHouse->canonical_url = $request->filled('canonical_url') ? trim($request->input('canonical_url')) : null;
                $boardingHouse->price = numberRemoveComma($request->input('price'));
                $boardingHouse->require_deposit = $request->has('require_deposit') && $request->input('require_deposit') === 'on';
                $boardingHouse->deposit_amount = $request->filled('deposit_amount') ? numberRemoveComma($request->input('deposit_amount')) : null;
                $boardingHouse->min_contract_months = $request->filled('min_contract_months') ? (int) $request->input('min_contract_months') : null;
                $boardingHouse->area = $request->filled('area') ? (int) $request->input('area') : null;
                $boardingHouse->status = $request->input('status');
                $boardingHouse->furniture_status = $request->input('furniture_status');
                $boardingHouse->tags = implode(', ', $tags);
                $boardingHouse->is_publish = $wantPublish;
                $boardingHouse->save();
                $this->uploadFiles($request, $boardingHouse->id);
            });

            return $this->responseSuccess('Chỉnh sửa thành công!');
        } catch (\Exception $ex) {
            Log::error('Error updating boarding house: '.$ex->getMessage(), [
                'id' => $id,
                'trace' => $ex->getTraceAsString(),
            ]);

            return $this->responseError('Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.');
        }
    }

    /**
     * Đẩy tin lên top: chọn thời gian hiển thị, trừ điểm. Không cho phép hoàn.
     */
    public function push(Request $request, $id)
    {
        $boardingHouse = BoardingHouse::find($id);
        if (! $boardingHouse) {
            return $this->responseError('Tin đăng không tồn tại.');
        }
        if (! $boardingHouse->canEdit()) {
            return $this->responseError('Không có quyền thao tác.');
        }
        if (! $boardingHouse->is_publish) {
            return $this->responseError('Chỉ có thể đẩy tin đã đăng. Vui lòng bật Publish trước.');
        }
        if ($boardingHouse->pushed_at) {
            return $this->responseError('Tin này đã đẩy top. Không thể hoàn thao tác. Liên hệ admin để được hỗ trợ.');
        }

        $listingDays = (int) $request->input('listing_days');
        $pointsByDuration = SystemDefination::LISTING_DURATION_POINTS;
        if (! isset($pointsByDuration[$listingDays])) {
            return $this->responseError('Vui lòng chọn thời gian hiển thị (10, 15, 30 hoặc 60 ngày).');
        }

        $pointsCost = $pointsByDuration[$listingDays];
        $serviceName = "Đẩy tin lên top {$listingDays} ngày";

        try {
            $this->servicePaymentService->processServicePayment(
                auth()->user(),
                ServicePayment::SERVICE_PUSH_LISTING,
                $serviceName,
                $pointsCost,
                $boardingHouse,
                'Đẩy tin lên top: '.$boardingHouse->title,
                ['listing_days' => $listingDays]
            );
        } catch (\Exception $e) {
            Log::error('Push listing failed: '.$e->getMessage());
            discord_log('Push listing failed', [
                'boarding_house_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ], 'ERROR');

            return $this->responseError($e->getMessage());
        }

        return $this->responseSuccess('Đã đẩy tin lên đầu danh sách trong '.$listingDays.' ngày!');
    }

    /**
     * Admin: dừng đẩy top tin (xóa pushed_at, listing_days, expires_at liên quan đẩy top).
     */
    public function stopPush($id)
    {
        $boardingHouse = BoardingHouse::find($id);
        if (! $boardingHouse) {
            return $this->responseError('Tin đăng không tồn tại.');
        }
        if (! auth()->user()->is_admin) {
            return $this->responseError('Chỉ admin mới có quyền dừng đẩy top.');
        }
        if (! $boardingHouse->pushed_at) {
            return $this->responseError('Tin này chưa đẩy top.');
        }

        $boardingHouse->update([
            'pushed_at' => null,
            'listing_days' => null,
            'expires_at' => null,
        ]);

        return $this->responseSuccess('Đã dừng đẩy top tin đăng.');
    }

    public function destroy($id)
    {
        $boardingHouse = BoardingHouse::find($id);

        if (! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        if (! $boardingHouse->canDelete()) {
            return $this->responseError('Không có quyền xoá!');
        }

        try {
            DB::transaction(function () use ($boardingHouse) {
                // Delete files from Cloudinary
                foreach ($boardingHouse->boarding_house_files ?? [] as $file) {
                    try {
                        cloudinary()->destroy($file->public_id);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete file from Cloudinary: '.$file->public_id);
                    }
                }

                // Delete database records
                $boardingHouse->boarding_house_files()->delete();
                $boardingHouse->delete();
            });
        } catch (\Exception $ex) {
            Log::error('Error deleting boarding house: '.$ex->getMessage(), [
                'id' => $id,
                'trace' => $ex->getTraceAsString(),
            ]);

            return $this->responseError('Có lỗi xảy ra khi xóa. Vui lòng thử lại.');
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

        if (! $boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        try {
            DB::transaction(function () use ($request, $boardingHouse, $id) {
                // Create appointment
                $appointment = new Appointment;
                $appointment->customer_name = trim($request->input('customer_name'));
                $appointment->phone = trim($request->input('phone'));
                $appointment->total_person = $request->input('total_person');
                $appointment->total_bike = $request->input('total_bike');
                $appointment->boarding_house_id = $id;
                $appointment->move_in_date = convertDateWithFormat($request->input('move_in_date'), 'd/m/Y');
                $appointment->status = 'WAITING_CONFIRM';
                $appointment->note = trim($request->input('note'));
                $appointment->appointment_at = convertDateWithFormat($request->input('appointment_at'), 'd/m/Y H:i', 'Y-m-d H:i');
                $appointment->save();

                // Send notification
                $this->sendAppointmentNotification($appointment, $boardingHouse);
            });
        } catch (\Exception $ex) {
            Log::error('Error creating appointment: '.$ex->getMessage(), [
                'boarding_house_id' => $id,
                'trace' => $ex->getTraceAsString(),
            ]);

            return $this->responseError('Có lỗi xảy ra khi tạo cuộc hẹn. Vui lòng thử lại.');
        }

        return $this->responseSuccess('Đã thêm cuộc hẹn mới!');
    }

    /**
     * Send appointment notification via Telegram
     */
    private function sendAppointmentNotification(Appointment $appointment, BoardingHouse $boardingHouse): void
    {
        try {
            $message = 'CUỘC HẸN XEM PHÒNG VỪA ĐƯỢC TẠO'.PHP_EOL.PHP_EOL
                .'- Ngày giờ hẹn xem phòng: '.date('d/m/Y H:i', strtotime($appointment->appointment_at)).PHP_EOL
                ."- Họ tên khách: {$appointment->customer_name}".PHP_EOL
                ."- SĐT/Zalo: {$appointment->phone}".PHP_EOL
                ."- Tổng người ở: {$appointment->total_person}".PHP_EOL
                ."- Tổng xe: {$appointment->total_bike}".PHP_EOL
                .'- Ngày chuyển vào dự kiến: '.($appointment->move_in_date ? date('d/m/Y', strtotime($appointment->move_in_date)) : 'Không rõ').PHP_EOL
                ."- Địa chỉ: {$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}".PHP_EOL
                ."- Post: {$boardingHouse->title}".PHP_EOL
                ."- ID Post: {$boardingHouse->id}".PHP_EOL
                ."- Ghi chú: {$appointment->note}".PHP_EOL;

            $this->telegramService->sendMessage($message);
        } catch (\Exception $e) {
            Log::warning('Failed to send Telegram notification: '.$e->getMessage());
        }
    }
}
