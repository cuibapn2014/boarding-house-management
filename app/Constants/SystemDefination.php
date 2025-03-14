<?php
namespace App\Constants;

class SystemDefination {
    const BOARDING_HOUSE_STATUS = [
        'available' => 'Còn trống',
        'rented' => 'Đã cho thuê'
    ];

    const BOARDING_HOUSE_CATEGORY = [
        'KTX' => 'Ký túc xá',
        'SLEEPBOX' => 'Sleepbox',
        'Phòng' => 'Phòng',
        'Nhà nguyên căn' => 'Nhà nguyên căn'
    ];

    const APPOINTMENT_STATUS = [
        'WAITING_CONFIRM' => 'Chờ xác nhận',
        'CONFIRMED' => 'Đã xác nhận',
        'CANCELED' => 'Đã huỷ',
    ];
}