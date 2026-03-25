<?php

return [
    /**
     * Số ngày trước khi hết hạn đẩy top để hiển thị cảnh báo (trên danh sách và trang sửa).
     */
    'push_expiring_warn_days' => (int) env('BOARDING_HOUSE_PUSH_EXPIRING_DAYS', 3),
];
