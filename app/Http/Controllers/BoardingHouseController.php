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
        $this->chatGptUtils = new ChatGptUtils();
    }

    public function index(Request $request)
    {
        $boardingHouses = BoardingHouse::with([
                'boarding_house_files:id,boarding_house_id,type,url',
                'user_create:id,firstname,lastname,email,phone,avatar'
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
            ->when($request->filled('byFurnitureStatus'), function($query) use($request) {
                $query->where('furniture_status', $request->byFurnitureStatus);
            })
            ->when($request->filled('byPublish'), function($query) use($request) {
                $query->where('is_publish', $request->byPublish);
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
            $listingDurationPoints = SystemDefination::LISTING_DURATION_POINTS;
            $draftCount = BoardingHouse::where('created_by', auth()->id())->where('is_publish', false)->count();
            $userPoints = (int) (auth()->user()->points ?? 0);
            return view('apps.boarding-house.clone', compact('boardingHouse', 'listingDurationPoints', 'draftCount', 'userPoints'));
        }

        $listingDurationPoints = SystemDefination::LISTING_DURATION_POINTS;
        $draftCount = BoardingHouse::where('created_by', auth()->id())->where('is_publish', false)->count();
        $userPoints = (int) (auth()->user()->points ?? 0);

        return view('apps.boarding-house.create', compact('listingDurationPoints', 'draftCount', 'userPoints'));
    }

    public function store(StoreBoardingHouseRequest $request)
    {
        $isPublish = $request->has('is_publish') && $request->input('is_publish') === 'on';

        if (! $isPublish) {
            // Lưu nháp: tối đa 1 tin nháp
            $draftCount = BoardingHouse::where('created_by', auth()->id())->where('is_publish', false)->count();
            if ($draftCount >= 1) {
                return $this->responseError('Bạn chỉ được lưu tối đa 1 tin nháp. Vui lòng đăng hoặc xóa tin nháp trước khi tạo mới.');
            }
        } else {
            // Đăng tin: bắt buộc chọn thời gian hiển thị và thanh toán
            $listingDays = (int) $request->input('listing_days');
            $pointsByDuration = SystemDefination::LISTING_DURATION_POINTS;
            if (! isset($pointsByDuration[$listingDays])) {
                return $this->responseError('Vui lòng chọn thời gian hiển thị tin đăng (10, 15, 30 hoặc 60 ngày).');
            }
        }

        try {
            $tags = $request->filled('tags') ? array_map(fn($item) => $item->value, json_decode($request->tags)) : [];
            $content = trim($request->input('content'));
            $optimizedContent = $this->optimizeContentWithAI($content);
            $optimizedTags = $this->generateTagsWithAI($content, $tags);

            $boardingHouse = null;

            DB::transaction(function () use ($request, $optimizedContent, $optimizedTags, $isPublish, &$boardingHouse) {
                // Tạo BH: nếu đăng tin thì tạm lưu is_publish=0, thanh toán xong mới bật
                $boardingHouse = $this->createBoardingHouse($request, $optimizedContent, $optimizedTags, false);
                $this->uploadFiles($request, $boardingHouse->id);
            });

            if ($isPublish) {
                $listingDays = (int) $request->input('listing_days');
                $pointsCost = SystemDefination::LISTING_DURATION_POINTS[$listingDays];
                $serviceName = "Đăng tin hiển thị {$listingDays} ngày";

                try {
                    $servicePayment = $this->servicePaymentService->processServicePayment(
                        auth()->user(),
                        ServicePayment::SERVICE_PUBLISH_LISTING,
                        $serviceName,
                        $pointsCost,
                        $boardingHouse,
                        $serviceName,
                        ['listing_days' => $listingDays]
                    );
                } catch (\Exception $e) {
                    Log::error('Publish listing payment failed: ' . $e->getMessage());
                    return $this->responseError($e->getMessage());
                }

                return $this->responseSuccess('Đăng tin thành công! Tin sẽ hiển thị trong ' . $listingDays . ' ngày.');
            }

            return $this->responseSuccess($isPublish ? 'Thêm mới thành công!' : 'Lưu nháp thành công!');
        } catch (\Exception $ex) {
            Log::error('Error creating boarding house: ' . $ex->getMessage(), [
                'trace' => $ex->getTraceAsString()
            ]);
            return $this->responseError('Có lỗi xảy ra khi tạo nhà trọ. Vui lòng thử lại.');
        }
    }

    /**
     * Optimize content with AI
     */
    private function optimizeContentWithAI(string $content): ?string
    {
        if(! auth()->user()->is_admin || auth()->user()->plan_current === 'free') {
            return $content;
        }

        try {
            $message = $content . "\n Hãy viết lại cái mô tả trên sao cho seo được điểm tốt. Lưu ý không viết kiểu markdown. Dùng các thẻ của HTML để biểu diễn các xuống dòng hay icon chẳng hạn. Có thể dùng emoji cho sinh động cũng được";
            $response = $this->chatGptUtils->sendMessageUsingChat($message);
            return $response?->choices[0]?->message?->content ?? $content;
        } catch (\Exception $e) {
            Log::warning('AI content optimization failed: ' . $e->getMessage());
            return $content;
        }
    }

    /**
     * Generate tags with AI
     */
    private function generateTagsWithAI(string $content, array $defaultTags): string
    {
        if(! auth()->user()->is_admin || auth()->user()->plan_current === 'free') {
            return implode(', ', $defaultTags);
        }

        try {
            $message = $content . "\n Hãy tạo ra những keywords hiệu quả cho bài viết này giúp tôi, những từ khoá liên quan cũng được. Response chỉ trả lời kết quả không cần giải thích";
            $response = $this->chatGptUtils->sendMessageUsingChat($message);
            
            if ($response) {
                $tags = $response?->choices[0]?->message?->content;
                $tags = str_replace(["\n", "-"], [", ", ""], $tags);
                return $tags;
            }
        } catch (\Exception $e) {
            Log::warning('AI tags generation failed: ' . $e->getMessage());
        }
        
        return implode(', ', $defaultTags);
    }

    /**
     * Create boarding house record
     * @param bool $isPublish When false, save as draft (used when payment will set publish later)
     */
    private function createBoardingHouse($request, $content, $tags, bool $isPublish = false): BoardingHouse
    {
        $boardingHouse = new BoardingHouse();
        $boardingHouse->title            = trim($request->input('title'));
        $boardingHouse->category         = $request->input('category');
        $boardingHouse->description      = trim($request->input('description'));
        $boardingHouse->content          = $content;
        $boardingHouse->district         = $request->input('district');
        $boardingHouse->ward             = $request->input('ward');
        $boardingHouse->address          = trim($request->input('address'));
        $boardingHouse->map_link         = $request->filled('map_link') ? trim($request->input('map_link')) : null;
        $boardingHouse->phone            = trim($request->input('phone'));
        $boardingHouse->meta_title       = $request->filled('meta_title') ? trim($request->input('meta_title')) : null;
        $boardingHouse->meta_description = $request->filled('meta_description') ? trim($request->input('meta_description')) : null;
        $boardingHouse->canonical_url     = $request->filled('canonical_url') ? trim($request->input('canonical_url')) : null;
        $boardingHouse->price            = numberRemoveComma($request->input('price'));
        $boardingHouse->require_deposit  = $request->has('require_deposit') && $request->input('require_deposit') === 'on';
        $boardingHouse->deposit_amount    = $request->filled('deposit_amount') ? numberRemoveComma($request->input('deposit_amount')) : null;
        $boardingHouse->min_contract_months = $request->filled('min_contract_months') ? (int)$request->input('min_contract_months') : null;
        $boardingHouse->area              = $request->filled('area') ? (int)$request->input('area') : null;
        $boardingHouse->status           = $request->input('status');
        $boardingHouse->furniture_status = $request->input('furniture_status');
        $boardingHouse->is_publish       = $isPublish;
        $boardingHouse->tags             = $tags;
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
                'resource_type' => $resourceType
            ]);

            $boardingHouseFile = new BoardingHouseFile();
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

        $listingDurationPoints = SystemDefination::LISTING_DURATION_POINTS;
        $userPoints = (int) (auth()->user()->points ?? 0);

        return view('apps.boarding-house.edit', compact('boardingHouse', 'listingDurationPoints', 'userPoints'));
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
        $wasDraft = ! $boardingHouse->is_publish;

        // Chuyển từ nháp sang đăng tin: bắt buộc chọn thời gian và thanh toán
        if ($wantPublish && $wasDraft) {
            $listingDays = (int) $request->input('listing_days');
            $pointsByDuration = SystemDefination::LISTING_DURATION_POINTS;
            if (! isset($pointsByDuration[$listingDays])) {
                return $this->responseError('Vui lòng chọn thời gian hiển thị tin đăng (10, 15, 30 hoặc 60 ngày).');
            }
        }

        try {
            $tags = $request->filled('tags') ? array_map(fn($item) => $item->value, json_decode($request->tags)) : [];

            DB::transaction(function () use ($request, $boardingHouse, $tags, $wantPublish, $wasDraft) {
                $boardingHouse->title            = trim($request->input('title'));
                $boardingHouse->category         = $request->input('category');
                $boardingHouse->description      = trim($request->input('description'));
                $boardingHouse->content          = trim($request->input('content'));
                $boardingHouse->district         = $request->input('district');
                $boardingHouse->ward             = $request->input('ward');
                $boardingHouse->address          = trim($request->input('address'));
                $boardingHouse->map_link         = $request->filled('map_link') ? trim($request->input('map_link')) : null;
                $boardingHouse->phone            = trim($request->input('phone'));
                $boardingHouse->meta_title       = $request->filled('meta_title') ? trim($request->input('meta_title')) : null;
                $boardingHouse->meta_description = $request->filled('meta_description') ? trim($request->input('meta_description')) : null;
                $boardingHouse->canonical_url     = $request->filled('canonical_url') ? trim($request->input('canonical_url')) : null;
                $boardingHouse->price            = numberRemoveComma($request->input('price'));
                $boardingHouse->require_deposit  = $request->has('require_deposit') && $request->input('require_deposit') === 'on';
                $boardingHouse->deposit_amount    = $request->filled('deposit_amount') ? numberRemoveComma($request->input('deposit_amount')) : null;
                $boardingHouse->min_contract_months = $request->filled('min_contract_months') ? (int)$request->input('min_contract_months') : null;
                $boardingHouse->area              = $request->filled('area') ? (int)$request->input('area') : null;
                $boardingHouse->status           = $request->input('status');
                $boardingHouse->furniture_status = $request->input('furniture_status');
                $boardingHouse->tags             = implode(', ', $tags);

                if (! $wantPublish) {
                    $boardingHouse->is_publish = false;
                    $boardingHouse->listing_days = null;
                    $boardingHouse->published_at = null;
                    $boardingHouse->expires_at = null;
                }
                // Nếu đang chuyển từ nháp sang đăng: is_publish sẽ được set sau khi thanh toán
                elseif (! $wasDraft) {
                    $boardingHouse->is_publish = true;
                }

                $boardingHouse->save();
                $this->uploadFiles($request, $boardingHouse->id);
            });

            // Chuyển từ nháp sang đăng tin: xử lý thanh toán
            if ($wantPublish && $wasDraft) {
                $listingDays = (int) $request->input('listing_days');
                $pointsCost = SystemDefination::LISTING_DURATION_POINTS[$listingDays];
                $serviceName = "Đăng tin hiển thị {$listingDays} ngày";

                try {
                    $servicePayment = $this->servicePaymentService->processServicePayment(
                        auth()->user(),
                        ServicePayment::SERVICE_PUBLISH_LISTING,
                        $serviceName,
                        $pointsCost,
                        $boardingHouse->fresh(),
                        $serviceName,
                        ['listing_days' => $listingDays]
                    );
                } catch (\Exception $e) {
                    Log::error('Publish listing payment failed: ' . $e->getMessage());
                    return $this->responseError($e->getMessage());
                }

                return $this->responseSuccess('Đăng tin thành công! Tin sẽ hiển thị trong ' . $listingDays . ' ngày.');
            }

            return $this->responseSuccess('Chỉnh sửa thành công!');
        } catch (\Exception $ex) {
            Log::error('Error updating boarding house: ' . $ex->getMessage(), [
                'id' => $id,
                'trace' => $ex->getTraceAsString()
            ]);
            return $this->responseError('Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.');
        }
    }

    /**
     * Push tin nhanh từ danh sách (trừ điểm)
     */
    public function push($id)
    {
        $boardingHouse = BoardingHouse::find($id);
        if (! $boardingHouse) {
            return $this->responseError('Tin đăng không tồn tại.');
        }
        if (! $boardingHouse->canEdit()) {
            return $this->responseError('Không có quyền thao tác.');
        }
        if (! $boardingHouse->is_publish) {
            return $this->responseError('Chỉ có thể đẩy tin đã đăng. Vui lòng đăng tin trước.');
        }
        if ($boardingHouse->pushed_at) {
            return $this->responseError('Tin này đã được đẩy top, không thể đẩy lại.');
        }
        $pointsCost = $this->servicePaymentService->getServiceCost(ServicePayment::SERVICE_PUSH_LISTING);
        try {
            $this->servicePaymentService->processServicePayment(
                auth()->user(),
                ServicePayment::SERVICE_PUSH_LISTING,
                'Đẩy tin nhanh',
                $pointsCost,
                $boardingHouse,
                'Đẩy tin nhanh: ' . $boardingHouse->title
            );
        } catch (\Exception $e) {
            Log::error('Quick push failed: ' . $e->getMessage());
            return $this->responseError($e->getMessage());
        }
        return $this->responseSuccess('Đã đẩy tin lên đầu danh sách!');
    }

    public function destroy($id)
    {
        $boardingHouse = BoardingHouse::find($id);

        if (!$boardingHouse) {
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
                        Log::warning('Failed to delete file from Cloudinary: ' . $file->public_id);
                    }
                }

                // Delete database records
                $boardingHouse->boarding_house_files()->delete();
                $boardingHouse->delete();
            });
        } catch (\Exception $ex) {
            Log::error('Error deleting boarding house: ' . $ex->getMessage(), [
                'id' => $id,
                'trace' => $ex->getTraceAsString()
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
        
        if (!$boardingHouse) {
            return $this->responseError('Dữ liệu không tồn tại hoặc đã bị xoá!');
        }

        try {
            DB::transaction(function () use ($request, $boardingHouse, $id) {
                // Create appointment
                $appointment = new Appointment();
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
            Log::error('Error creating appointment: ' . $ex->getMessage(), [
                'boarding_house_id' => $id,
                'trace' => $ex->getTraceAsString()
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
            $message = "CUỘC HẸN XEM PHÒNG VỪA ĐƯỢC TẠO" . PHP_EOL . PHP_EOL
                . "- Ngày giờ hẹn xem phòng: " . date('d/m/Y H:i', strtotime($appointment->appointment_at)) . PHP_EOL
                . "- Họ tên khách: {$appointment->customer_name}" . PHP_EOL
                . "- SĐT/Zalo: {$appointment->phone}" . PHP_EOL
                . "- Tổng người ở: {$appointment->total_person}" . PHP_EOL
                . "- Tổng xe: {$appointment->total_bike}" . PHP_EOL
                . "- Ngày chuyển vào dự kiến: " . ($appointment->move_in_date ? date('d/m/Y', strtotime($appointment->move_in_date)) : 'Không rõ') . PHP_EOL
                . "- Địa chỉ: {$boardingHouse->address}, {$boardingHouse->ward}, {$boardingHouse->district}" . PHP_EOL
                . "- Post: {$boardingHouse->title}" . PHP_EOL
                . "- ID Post: {$boardingHouse->id}" . PHP_EOL
                . "- Ghi chú: {$appointment->note}" . PHP_EOL;

            $this->telegramService->sendMessage($message);
        } catch (\Exception $e) {
            Log::warning('Failed to send Telegram notification: ' . $e->getMessage());
        }
    }
}
