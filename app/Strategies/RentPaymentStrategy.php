<?php

namespace App\Strategies;

use App\Models\BoardingHouse;
use App\Models\Appointment;
use App\Strategies\Contracts\PaymentStrategyInterface;

class RentPaymentStrategy implements PaymentStrategyInterface
{
    public function calculateAmount(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): float
    {
        return $boardingHouse?->price ?? 0.0;
    }

    public function generateDescription(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): string
    {
        $title = $boardingHouse?->title ?? 'phòng trọ';
        return "Tiền thuê phòng: {$title}";
    }

    public function generateMetadata(?BoardingHouse $boardingHouse = null, ?Appointment $appointment = null): array
    {
        $metadata = [];
        
        if ($boardingHouse) {
            $metadata['boarding_house_id'] = $boardingHouse->id;
            $metadata['boarding_house_title'] = $boardingHouse->title;
            $metadata['boarding_house_address'] = $boardingHouse->address;
        }

        return $metadata;
    }

    public function getPaymentType(): string
    {
        return \App\Models\Payment::TYPE_RENT;
    }
}
