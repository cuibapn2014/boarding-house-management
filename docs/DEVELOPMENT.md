# Hướng dẫn phát triển

## Convention code

- **PHP**: PSR-12; đặt tên rõ ràng (biến, hàm, class). Admin-only logic kiểm tra `auth()->user()->is_admin` hoặc `User#is_admin`.
- **Blade**: Dùng `@auth`, `@if(auth()->user()->is_admin)` cho phần chỉ admin; tránh logic phức tạp trong view.
- **JavaScript**: Tên hàm dễ đọc (vd: `performListingSearch`, `refreshActiveFilterCount`); hằng số viết hoa (vd: `API_PROVINCES`).

## Điểm (Points)

- Mọi thay đổi số dư đều qua **PointService** và ghi **PointTransaction** (balance_before, balance_after, description, metadata).
- Admin cộng/trừ: `addPointsByAdmin`, `subtractPointsByAdmin` — type `admin_add` / `admin_subtract`, metadata lưu `admin_id`, `admin_name`.
- Dịch vụ (đẩy top, v.v.): **ServicePaymentService**. Nếu user là admin thì `points_cost = 0`, không trừ điểm.

## Quản lý người dùng (User management)

- Trang **user-management** do `PageController@index` phục vụ; dữ liệu user load kèm `withCount('boardingHouses')` và `withSum` giao dịch điểm âm (tổng điểm đã dùng).
- **User** model có relation `boardingHouses()` (BoardingHouse `created_by` = user id).
- Card mỗi user hiển thị: **Tổng tin đã đăng** (số tin nhà trọ), **Tổng điểm đã dùng** (tổng |amount| giao dịch trừ điểm), **Tổng điểm hiện có** (`users.points`).

## Tin đăng (Boarding House)

- **Tin nháp**: `is_publish = false`; không giới hạn số lượng tin nháp.
- **Đẩy top**: Cập nhật `pushed_at`, `listing_days`, `expires_at`. Admin có thể **dừng đẩy top** bất kỳ tin nào (route `boarding-house.stop-push`).

## Ảnh

- Upload qua Cloudinary. Thumbnail danh sách dùng helper `getOptimizedThumbnailUrl($url, 400, 300)` (Cloudinary transform: crop, auto format/quality) để tối ưu hiển thị.

## Chạy dự án

```bash
php artisan serve
# Queue (nếu dùng)
php artisan queue:work
```

## Kiểm tra nhanh

- Đăng nhập user thường: tạo tin nháp, đăng tin, xem ví điểm.
- Đăng nhập admin (id=1): dùng đẩy top không trừ điểm, xem Lịch sử điểm (tất cả), Điều chỉnh điểm, Dừng đẩy top tin của user khác.
