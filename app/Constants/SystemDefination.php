<?php
namespace App\Constants;

class SystemDefination {
    const BOARDING_HOUSE_STATUS = [
        'available' => 'Còn trống',
        'rented' => 'Đã cho thuê'
    ];

    const BOARDING_HOUSE_CATEGORY = [
        'Căn hộ/Chung cư' => 'Căn hộ/Chung cư',
        'Nhà ở' => 'Nhà ở',
        'Văn phòng/Mặt bằng' => 'Văn phòng/Mặt bằng',
        'Phòng trọ' => 'Phòng trọ',
        'Ký túc xá' => 'Ký túc xá',
        'Sleepbox' => 'Sleepbox'
    ];

    const BOARDING_HOUSE_FURNITURE_STATUS = [
        'empty' => 'Phòng trống',
        'basic' => 'Nội thất cơ bản',
        'full' => 'Full nội thất'
    ];

    const APPOINTMENT_STATUS = [
        'WAITING_CONFIRM' => 'Chờ xác nhận',
        'CONFIRMED' => 'Đã xác nhận',
        'CANCELED' => 'Đã huỷ',
    ];
}