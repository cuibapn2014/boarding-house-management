<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PointPackage;

class PointPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Gói 20k',
                'description' => 'Gói nạp điểm cơ bản',
                'price' => 20000,
                'points' => 20,
                'bonus_points' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gói 30k',
                'description' => 'Gói nạp điểm tiết kiệm',
                'price' => 30000,
                'points' => 30,
                'bonus_points' => 0,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Gói 50k',
                'description' => 'Gói nạp điểm phổ biến',
                'price' => 50000,
                'points' => 50,
                'bonus_points' => 5,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Gói 100k',
                'description' => 'Gói nạp điểm ưu đãi',
                'price' => 100000,
                'points' => 100,
                'bonus_points' => 10,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Gói 200k',
                'description' => 'Gói nạp điểm tiết kiệm',
                'price' => 200000,
                'points' => 200,
                'bonus_points' => 30,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Gói 500k',
                'description' => 'Gói nạp điểm cao cấp',
                'price' => 500000,
                'points' => 500,
                'bonus_points' => 100,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($packages as $package) {
            PointPackage::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
