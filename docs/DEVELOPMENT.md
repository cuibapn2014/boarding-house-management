# Hướng dẫn phát triển

Tài liệu kỹ thuật và convention cho dự án Quản lý nhà trọ. Tài liệu nghiệp vụ (flow đăng tin, tích lũy/sử dụng điểm) xem [docs/BUSINESS_FLOWS.md](BUSINESS_FLOWS.md).

---

## 1. Convention code

### PHP
- **Chuẩn**: PSR-12; đặt tên rõ ràng (biến, hàm, class).
- **Phân quyền**: Logic chỉ admin kiểm tra `auth()->user()->is_admin` hoặc `User#is_admin`. Không dựa vào `id === 1` cho nghiệp vụ (chỉ dùng trong ownership khi cần).
- **Request**: Validation trong Form Request (vd: `StoreBoardingHouseRequest`); rule dùng constant từ `SystemDefination` khi có.

### Blade
- Dùng `@auth`, `@if(auth()->user()->is_admin)` cho phần chỉ admin; tránh logic phức tạp trong view.
- Hiển thị số điểm: lấy từ `auth()->user()->points` hoặc biến từ controller (vd: `$balance` từ `PointService::getBalance()`).

### JavaScript
- Tên hàm dễ đọc (vd: `performListingSearch`, `refreshActiveFilterCount`); hằng số viết hoa (vd: `API_PROVINCES`).
- Gọi API qua helper chung (vd: `ApiHelper.callApi`), có xử lý CSRF và loading.

---

## 2. Điểm (Points)

### Nguyên tắc
- Mọi thay đổi số dư điểm đều qua **PointService** và ghi **PointTransaction** (`balance_before`, `balance_after`, `description`, `metadata`).
- Không cộng/trừ trực tiếp `users.points` ngoài service.

### Loại giao dịch (PointTransaction)
| Type | Mô tả |
|------|--------|
| `top_up` | Nạp điểm từ gói (sau thanh toán thành công) |
| `deduction` | Trừ điểm (dịch vụ đẩy top, v.v.) |
| `refund` | Hoàn điểm (hủy dịch vụ) |
| `admin_add` | Admin cộng điểm |
| `admin_subtract` | Admin trừ điểm |

### Admin
- **Cộng/trừ điểm**: `PointService::addPointsByAdmin`, `subtractPointsByAdmin` — type `admin_add` / `admin_subtract`, metadata lưu `admin_id`, `admin_name`.
- **Route**: `point.admin.adjust` (form), `point.admin.adjust.store` (xử lý); `point.admin.transactions` (lịch sử tất cả user).

### Dịch vụ trả bằng điểm (đẩy top, v.v.)
- **ServicePaymentService::processServicePayment**. Nếu user là admin thì `points_cost = 0`, không trừ điểm; vẫn tạo `ServicePayment` và áp dụng nghiệp vụ (vd: cập nhật `pushed_at`, `listing_days`, `expires_at`).

---

## 3. Quản lý người dùng (User management)

- Trang **user-management** do `PageController@index` phục vụ với `page=user-management`.
- Dữ liệu: `User::withCount('boardingHouses')->withSum(['pointTransactions as total_points_used' => fn($q) => $q->where('amount', '<', 0)], 'amount')->paginate(20)`.
- **User** model: relation `boardingHouses()` (BoardingHouse `created_by` = user id).
- Card mỗi user: **Tổng tin đã đăng** (`boarding_houses_count`), **Tổng điểm đã dùng** (giá trị tuyệt đối của `total_points_used`), **Tổng điểm hiện có** (`users.points`).

---

## 4. Tin đăng (Boarding House)

### Ownership & quyền
- **created_by**: Gán tự động qua `CommonTrait` khi `creating` (auth user). Quan hệ `user_create()`.
- **Chỉnh sửa / xóa**: `canEdit()`, `canDelete()` — chủ tin hoặc admin (id 1).

### Trạng thái
- **Tin nháp**: `is_publish = false`; không giới hạn số lượng tin nháp.
- **Đã đăng**: `is_publish = true`. Có thể dùng dịch vụ đẩy top.

### Đẩy top
- Cập nhật `pushed_at`, `listing_days`, `expires_at`. Chi phí điểm theo `SystemDefination::LISTING_DURATION_POINTS` (10/15/30/60 ngày).
- Route: `POST boarding-house/{id}/push` (`boarding-house.push`). Chỉ tin đã publish, chưa đẩy top; không hoàn điểm.
- Admin có thể **dừng đẩy top** bất kỳ tin nào: route `boarding-house.stop-push` — xóa `pushed_at`, `listing_days`, `expires_at`.

### Ảnh / file
- Upload qua Cloudinary. Thumbnail danh sách dùng helper `getOptimizedThumbnailUrl($url, 400, 300)` (crop, auto format/quality).
- Giới hạn theo gói: trong `StoreBoardingHouseRequest::validateFilesByPlan` — gói free: tối đa 5 ảnh, 1 video (admin không giới hạn).

### AI (tùy chọn)
- `optimizeContentWithAI`, `generateTagsWithAI` trong `BoardingHouseController`: chỉ chạy khi user không phải admin free (điều kiện trong code). Có thể bỏ qua nếu lỗi.

---

## 5. Cấu trúc thư mục & thành phần chính

| Thành phần | Mô tả ngắn |
|------------|------------|
| `app/Http/Controllers/BoardingHouseController` | CRUD tin, push, stopPush, appointment |
| `app/Http/Controllers/PointController` | Ví điểm, nạp điểm, lịch sử, admin điều chỉnh |
| `app/Http/Controllers/ServicePaymentController` | Trang dịch vụ tin (đẩy top, ưu tiên, gia hạn), xử lý thanh toán điểm |
| `app/Http/Controllers/PaymentController` | Tạo/xem/hủy thanh toán (SePay); check status |
| `app/Services/PointService` | Top-up, deduct, refund, admin add/subtract, lịch sử |
| `app/Services/ServicePaymentService` | processServicePayment (điểm), apply đẩy top lên BoardingHouse |
| `app/Services/PaymentService` | createPayment, processPaymentCompletion (webhook), processPointTopUpInTransaction |
| `app/Listeners/ProcessPointTopUpOnPaymentCompleted` | Backup nạp điểm khi PaymentCompleted (TYPE_POINT_TOP_UP) |
| `app/Models/PointTransaction` | Loại giao dịch, reference polymorphic |
| `app/Constants/SystemDefination` | LISTING_DURATION_POINTS, danh mục, trạng thái |

---

## 6. Route chính (auth)

| Nhóm | Route / name |
|------|----------------|
| Tin đăng | `resource boarding-house`, `boarding-house.push`, `boarding-house.stop-push` |
| Điểm | `point.wallet`, `point.top-up`, `point.process-top-up`, `point.transactions` |
| Admin điểm | `point.admin.transactions`, `point.admin.adjust`, `point.admin.adjust.store` |
| Thanh toán | `payment.index`, `payment.create`, `payment.store`, `payment.show`, `payment.checkStatus`, `payment.cancel` |
| Dịch vụ tin | `service-payment.show`, `service-payment.process` |
| Trang tĩnh | `page.index` (vd: user-management) |

---

## 7. Chạy dự án

```bash
# Server
php artisan serve

# Queue (khi dùng job/event)
php artisan queue:work
```

### Biến môi trường liên quan
- **Điểm / thanh toán**: Cấu hình SePay (vd: `SEPAY_MERCHANT_ID`, `SEPAY_SECRET`, `SEPAY_ENV`) cho nạp điểm qua cổng thanh toán.
- **Cloudinary**: Upload ảnh/video tin đăng.
- **AI** (nếu bật): API key cho tối ưu nội dung/tag.

---

## 8. Kiểm tra nhanh

- **User thường**: Đăng nhập → tạo tin nháp → đăng tin (publish) → xem ví điểm → nạp điểm (chọn gói, thanh toán) → đẩy top tin (trừ điểm) → xem lịch sử giao dịch điểm.
- **Admin (is_admin = true)**: Đẩy top không trừ điểm; xem Lịch sử điểm (tất cả user); Điều chỉnh điểm (cộng/trừ); Dừng đẩy top tin của user khác; User management (tổng tin, tổng điểm đã dùng, điểm hiện có).

---

## 9. Tài liệu liên quan

- [BUSINESS_FLOWS.md](BUSINESS_FLOWS.md) — Flow nghiệp vụ: đăng tin, tích lũy điểm, sử dụng điểm.
