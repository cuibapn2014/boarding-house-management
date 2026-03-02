# Hệ thống quản lý nhà trọ (Boarding House Management)

Ứng dụng web quản lý tin đăng nhà trọ: đăng tin, đẩy top, nạp điểm, thanh toán và quản trị người dùng.

## Yêu cầu hệ thống

- **PHP** >= 8.1  
- **Composer**  
- **MySQL** / MariaDB hoặc **SQLite**  
- **Node.js** (npm) — cho frontend nếu cần build

## Cài đặt nhanh

```bash
# Clone (nếu chưa có)
git clone <repo-url> .
cd boarding-house-management

# Cài dependency PHP
composer install

# Copy env và cấu hình
cp .env.example .env
php artisan key:generate

# Cấu hình .env: DB_*, CLOUDINARY_*, GOOGLE_*, SEPAY_*, TELEGRAM_* (tùy chọn)

# Chạy migration
php artisan migrate

# (Tùy chọn) Seed dữ liệu mẫu
php artisan db:seed

# Chạy server
php artisan serve
```

Truy cập: **http://localhost:8000** (sau khi đăng ký/đăng nhập sẽ chuyển tới `/boarding-house`).

## Cấu hình môi trường (.env)

| Biến | Mô tả |
|------|--------|
| `APP_NAME` | Tên ứng dụng |
| `DB_*` | Kết nối database |
| `CLOUDINARY_*` | Upload ảnh/video (Cloudinary) |
| `GOOGLE_CLIENT_*` | Đăng nhập Google (Socialite) |
| `SEPAY_*` | Cổng thanh toán SePay |
| `TELEGRAM_*` | Thông báo Telegram (tùy chọn) |
| `OPENAI_API_KEY` | Tối ưu nội dung / gợi ý tag (AI, tùy chọn) |

## Chức năng chính

### Người dùng thường

- **Tin đăng**: Tạo / sửa / xóa tin nhà trọ; **tin nháp không giới hạn**.
- **Ảnh**: Tải lên qua Cloudinary; hiển thị thumbnail tối ưu (resize, lazy load).
- **Đẩy tin lên top**: Dùng điểm để đẩy tin (10/15/30/60 ngày).
- **Ví điểm**: Nạp điểm, xem lịch sử giao dịch.
- **Cuộc hẹn**: Tạo cuộc hẹn xem phòng từ tin đăng.

### Admin (user `id = 1`)

- **Dịch vụ không giới hạn**: Đẩy top, gia hạn, v.v. không trừ điểm.
- **Lịch sử điểm (tất cả)**: Xem lịch sử sử dụng điểm của mọi người dùng, lọc theo user.
- **Điều chỉnh điểm**: Cộng/trừ điểm thủ công; mọi thao tác đều **ghi lại đầy đủ** (admin_id, lý do) trong lịch sử.
- **Dừng đẩy top**: Dừng đẩy top tin của bất kỳ tin đăng nào (bất kỳ user).
- **Quản lý người dùng**: Chỉnh sửa, khóa, gán gói (Free/Premium). Mỗi card user hiển thị: Tổng tin đã đăng, Tổng điểm đã dùng, Tổng điểm hiện có.

## Cấu trúc thư mục (phần chính)

```
app/
├── Http/Controllers/
│   ├── BoardingHouseController.php   # Tin đăng, đẩy top, dừng đẩy top
│   ├── PointController.php            # Ví điểm, admin: lịch sử + điều chỉnh điểm
│   └── ...
├── Services/
│   ├── PointService.php              # Nạp/trừ/hoàn điểm, admin cộng-trừ
│   ├── ServicePaymentService.php     # Thanh toán dịch vụ (admin miễn phí)
│   └── ...
├── Models/
│   ├── BoardingHouse.php
│   ├── PointTransaction.php          # Loại: top_up, deduction, refund, admin_add, admin_subtract
│   └── ...
resources/views/
├── apps/
│   ├── boarding-house/               # Danh sách, tạo, sửa, clone tin
│   └── point/                         # Ví, lịch sử, admin: lịch sử tất cả + điều chỉnh điểm
└── pages/
    └── user-management.blade.php     # Quản lý user: card (tin đã đăng, điểm đã dùng, điểm hiện có)
public/assets/js/apps/boarding_house/
├── script.js                          # Tìm kiếm, lọc, xóa, clone, dừng đẩy top
└── BoardingHouse.js                   # Load danh sách, modal cuộc hẹn, xóa tin
```

## Tài liệu bổ sung

- **[docs/DEVELOPMENT.md](docs/DEVELOPMENT.md)** — Hướng dẫn phát triển, convention, cách chạy test (nếu có).

## Công nghệ

- **Backend**: Laravel 10, PHP 8.1+  
- **Frontend**: Blade, Bootstrap 5, jQuery, Argon Dashboard  
- **Ảnh**: Cloudinary (upload + transform thumbnail)  
- **Thanh toán**: SePay  
- **Đăng nhập**: Session + Google OAuth (Socialite)

## License

MIT.
