# Tài liệu nghiệp vụ — Đăng tin & Điểm

Tài liệu mô tả các flow nghiệp vụ chính: **đăng tin nhà trọ**, **tích lũy điểm** và **sử dụng điểm**.

---

## 1. Flow đăng tin (Tin nhà trọ)

### 1.1 Tổng quan

- User đăng nhập có thể **tạo tin nháp** (không giới hạn số lượng) hoặc **đăng tin ngay** (publish).
- Tin nháp: `is_publish = false`, chỉ lưu, không hiển thị công khai theo nghiệp vụ listing.
- Tin đã đăng: `is_publish = true`, có thể dùng dịch vụ **đẩy top** (trả bằng điểm).

### 1.2 Tạo / đăng tin (Create & Publish)

| Bước | Mô tả | Kỹ thuật |
|------|--------|----------|
| 1 | User mở form tạo tin | `GET /boarding-house/create` hoặc clone từ tin có sẵn `?id={id}` |
| 2 | Điền form (tiêu đề, danh mục, giá, địa chỉ, ảnh/video, …) | Validation: `StoreBoardingHouseRequest` (title, category, district, ward, price, files theo gói, …) |
| 3 | Chọn **Lưu nháp** hoặc **Đăng tin** | Checkbox `is_publish`: bật = đăng tin, tắt = nháp |
| 4 | Submit | `POST /boarding-house` → `BoardingHouseController@store` |
| 5 | Xử lý | Transaction: `createBoardingHouse()` + `uploadFiles()`. Nội dung/tag có thể tối ưu bằng AI (tùy điều kiện user). `created_by` gán tự động (CommonTrait). |
| 6 | Kết quả | Tin được tạo; thông báo "Lưu nháp thành công!" hoặc "Đăng tin thành công!" |

**Lưu ý:**
- Tin nháp **không** tốn điểm; đăng tin (publish) cũng **không** tốn điểm. Điểm chỉ dùng khi mua dịch vụ (vd: đẩy top).
- Gói **free** giới hạn ảnh/video (tối đa 5 ảnh, 1 video) trong request; admin không bị giới hạn.

### 1.3 Chỉnh sửa tin

- Route: `GET/PUT /boarding-house/{id}` (edit/update).
- Điều kiện: `canEdit()` — chủ tin hoặc admin.
- Có thể bật/tắt publish khi update; validation file vẫn áp theo gói (đếm cả file cũ + file mới).

### 1.4 Xóa tin

- Route: `DELETE /boarding-house/{id}`.
- Điều kiện: `canDelete()`. Xóa file Cloudinary và bản ghi tin.

### 1.5 Đẩy tin lên top (dịch vụ trả điểm)

- **Điều kiện**: Tin đã **đăng** (`is_publish = true`), **chưa** đẩy top (`pushed_at` null). Mỗi tin chỉ đẩy top một lần (không hoàn điểm).
- **Cách dùng**: Từ trang danh sách tin hoặc trang tin, chọn "Đẩy top" → chọn số ngày hiển thị (10, 15, 30, 60) → hệ thống trừ điểm tương ứng (trừ khi user là admin).
- **Chi phí điểm** (theo `SystemDefination::LISTING_DURATION_POINTS`):

| Số ngày hiển thị | Điểm cần dùng |
|------------------|----------------|
| 10 ngày          | 15 điểm        |
| 15 ngày          | 20 điểm        |
| 30 ngày          | 35 điểm        |
| 60 ngày          | 50 điểm        |

- **Kỹ thuật**: `POST /boarding-house/{id}/push` → `BoardingHouseController@push` → `ServicePaymentService::processServicePayment` (type đẩy top) → trừ điểm (nếu không phải admin) → cập nhật `pushed_at`, `listing_days`, `expires_at` trên tin.

### 1.6 Dừng đẩy top (chỉ Admin)

- Admin có thể dừng đẩy top bất kỳ tin nào: xóa `pushed_at`, `listing_days`, `expires_at`. **Không hoàn điểm** cho user.
- Route: `POST /boarding-house/{id}/stop-push` (`boarding-house.stop-push`).

---

## 2. Flow tích lũy điểm (Nạp điểm)

### 2.1 Nguồn tích lũy điểm

1. **Nạp điểm bằng tiền (mua gói điểm)**  
   User chọn gói → thanh toán qua cổng (vd: SePay) → khi thanh toán thành công → hệ thống cộng điểm vào ví (PointService + PointTransaction type `top_up`).

2. **Admin cộng điểm thủ công**  
   Admin chọn user, nhập số điểm và lý do → hệ thống cộng điểm và ghi transaction type `admin_add`.

### 2.2 Flow nạp điểm qua thanh toán (chi tiết)

| Bước | Mô tả | Kỹ thuật |
|------|--------|----------|
| 1 | User vào trang nạp điểm | `GET /point/top-up` → hiển thị danh sách gói (`PointPackage` active) và số dư hiện tại |
| 2 | Chọn gói, submit | `POST /point/top-up` (package_id) → `PointController@processTopUp` |
| 3 | Tạo yêu cầu thanh toán | `PaymentService::createPayment` với `payment_type = TYPE_POINT_TOP_UP`, metadata chứa `package_id`, `package_name`, `points`, `bonus_points` |
| 4 | User thanh toán bên ngoài | Redirect/redirect về trang thanh toán (SePay); user chuyển khoản/QR theo hướng dẫn |
| 5 | Xác nhận thanh toán | Webhook/check status → `PaymentService::processPaymentCompletion` (cập nhật payment completed) |
| 6 | Cộng điểm | Trong cùng transaction: `processPointTopUpInTransaction` → `PointService::topUpPoints(user, package, payment->id)`. Ghi `PointTransaction` type `top_up`, cập nhật `users.points` |
| 7 | Event | `PaymentCompleted` → listener `ProcessPointTopUpOnPaymentCompleted` (backup: nếu chưa có transaction cho payment thì nạp điểm) |

**Lưu ý:**
- Số điểm cộng = `PointPackage::total_points` (points + bonus_points).
- Mọi thay đổi số dư đều qua PointService và ghi PointTransaction (balance_before, balance_after, description, metadata).

### 2.3 Flow admin cộng điểm

| Bước | Mô tả | Kỹ thuật |
|------|--------|----------|
| 1 | Admin mở form điều chỉnh điểm | `GET /point/admin/adjust` |
| 2 | Chọn user, chọn "Cộng", nhập điểm và lý do | Form submit |
| 3 | Xử lý | `POST /point/admin/adjust` → `PointController@storeAdjustPoints` → `PointService::addPointsByAdmin(targetUser, points, reason, adminUser)` |
| 4 | Kết quả | Cộng điểm vào user; ghi PointTransaction type `admin_add`, metadata có `admin_id`, `admin_name` |

---

## 3. Flow sử dụng điểm

### 3.1 Các hình thức sử dụng điểm

1. **Dịch vụ tin đăng (đẩy top, tin ưu tiên, gia hạn)**  
   Thanh toán bằng điểm qua `ServicePaymentService::processServicePayment`. Trừ điểm (trừ khi user là admin thì cost = 0).

2. **Admin trừ điểm thủ công**  
   Admin chọn user, chọn "Trừ", nhập điểm và lý do → `PointService::subtractPointsByAdmin`.

### 3.2 Flow đẩy tin (sử dụng điểm) — chi tiết

Đã mô tả ở mục **1.5**. Tóm tắt:

- User (hoặc admin) chọn tin đã đăng, chọn "Đẩy top" và số ngày (10/15/30/60).
- Hệ thống kiểm tra: tin tồn tại, có quyền, đã publish, chưa đẩy top.
- Cost = `LISTING_DURATION_POINTS[listing_days]` (15/20/35/50 điểm).
- Nếu **không phải admin**: kiểm tra đủ điểm → tạo `ServicePayment` → `PointService::deductPoints` → cập nhật tin (`pushed_at`, `listing_days`, `expires_at`).
- Nếu **admin**: cost = 0, không trừ điểm; vẫn cập nhật tin và tạo ServicePayment (metadata `admin_free`).

### 3.3 Các dịch vụ khác (tin ưu tiên, gia hạn)

- Trong code có định nghĩa `ServicePayment::SERVICE_PRIORITY_LISTING`, `SERVICE_EXTEND_LISTING` và cost trong `ServicePaymentService::$serviceCosts`. Hiện nghiệp vụ **đẩy top** là flow chính đã implement đầy đủ (route riêng `boarding-house.push` với cost theo ngày). Các dịch vụ còn lại có thể gọi qua trang dịch vụ `service-payment.show` / `service-payment.process` (cost cố định trong service).

### 3.4 Flow admin trừ điểm

| Bước | Mô tả | Kỹ thuật |
|------|--------|----------|
| 1 | Admin mở form điều chỉnh điểm | `GET /point/admin/adjust` |
| 2 | Chọn user, chọn "Trừ", nhập điểm và lý do | Form submit |
| 3 | Xử lý | `PointService::subtractPointsByAdmin` — kiểm tra đủ điểm rồi trừ, ghi transaction type `admin_subtract` |

### 3.5 Xem số dư và lịch sử

- **Ví điểm**: `GET /point/wallet` — số dư hiện tại + lịch sử giao dịch (user).
- **Lịch sử giao dịch**: `GET /point/transactions` (user); `GET /point/admin/transactions` (admin, tất cả user, có thể lọc theo user).

---

## 4. Sơ đồ tóm tắt

```
ĐĂNG TIN
  → Tạo tin (nháp hoặc publish) [không tốn điểm]
  → Chỉnh sửa / Xóa (theo quyền)
  → Đẩy top (chọn 10/15/30/60 ngày) → trừ điểm (trừ admin)
  → Admin: dừng đẩy top (không hoàn điểm)

TÍCH LŨY ĐIỂM
  → Nạp qua gói: Chọn gói → Thanh toán → Webhook/confirm → PointService::topUpPoints
  → Admin: Cộng điểm thủ công → addPointsByAdmin

SỬ DỤNG ĐIỂM
  → Đẩy top: processServicePayment (deductPoints) → apply pushed_at, listing_days, expires_at
  → Dịch vụ khác (ưu tiên, gia hạn): processServicePayment (cost từ serviceCosts)
  → Admin: Trừ điểm thủ công → subtractPointsByAdmin
```

---

## 5. Tham chiếu nhanh

| Nghiệp vụ | Route / Controller | Service / Model chính |
|-----------|--------------------|------------------------|
| Tạo/sửa tin | `boarding-house.store`, `boarding-house.update` | BoardingHouseController, StoreBoardingHouseRequest, BoardingHouse (CommonTrait created_by) |
| Đẩy top | `boarding-house.push` | BoardingHouseController@push → ServicePaymentService, SystemDefination::LISTING_DURATION_POINTS |
| Dừng đẩy top | `boarding-house.stop-push` | BoardingHouseController@stopPush |
| Nạp điểm (gói) | `point.top-up`, `point.process-top-up` | PointController, PaymentService, PointService::topUpPoints (sau payment completed) |
| Admin cộng/trừ điểm | `point.admin.adjust`, `point.admin.adjust.store` | PointController, PointService::addPointsByAdmin / subtractPointsByAdmin |
| Ví & lịch sử điểm | `point.wallet`, `point.transactions` | PointController, PointService::getBalance, getTransactionHistory |

Tài liệu kỹ thuật chi tiết: [DEVELOPMENT.md](DEVELOPMENT.md).
