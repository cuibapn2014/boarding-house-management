<?php

namespace App\Strategies;

use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Strategies\Contracts\PaymentStrategyInterface;

class BookingFeePaymentStrategy implements PaymentStrategyInterface
{
    protected const DEFAULT_BOOKING_FEE = 50000;

    public function calculateAmount(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): float
    {
        return self::DEFAULT_BOOKING_FEE;
    }

    public function generateDescription(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): string
    {
        return "Phí đặt lịch xem phòng";
    }

    public function generateMetadata(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): array
    {
        $metadata = [];
        
        if ($appointment) {
            $metadata['appointment_id'] = $appointment->id;
            $metadata['customer_name'] = $appointment->customer_name;
            $metadata['phone'] = $appointment->phone;
        }

        if ($boardingHouse) {
            $metadata['boarding_house_id'] = $boardingHouse->id;
            $metadata['boarding_house_title'] = $boardingHouse->title;
        }

        return $metadata;
    }

    public function getPaymentType(): string
    {
        return \App\Models\Payment::TYPE_BOOKING_FEE;
    }
}
