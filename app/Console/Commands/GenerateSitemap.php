<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        try {

            // Tạo sitemap mới
            $sitemap = Sitemap::create();

            // Thêm các URL tĩnh
            $sitemap->add(Url::create(route('home.index'))->setPriority(1.0)->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('privacy.index'))->setPriority(0.8)->setChangeFrequency('monthly'));
            $sitemap->add(Url::create(route('contact.index'))->setPriority(0.8)->setChangeFrequency('monthly'));

            // Thêm danh sách cho thuê
            $sitemap->add(Url::create(route('rentalHome.index'))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['Phòng']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['Phòng'], 'price' => ['1000000-3000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['Phòng'], 'price' => ['3000000-5000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['Phòng'], 'price' => ['5000000-7000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['KTX', 'SLEEPBOX']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['category' => ['KTX', 'SLEEPBOX'], 'price' => ['1000000-3000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['price' => ['1000000-3000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['price' => ['3000000-5000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));
            $sitemap->add(Url::create(route('rentalHome.index', ['price' => ['5000000-7000000']]))
                ->setPriority(0.9)
                ->setChangeFrequency('daily'));

            // Thêm từng phòng chi tiết từ cơ sở dữ liệu
            $rentals = \App\Models\BoardingHouse::all();
            foreach ($rentals as $rental) {
                $sitemap->add(
                    Url::create(route('rentalHome.show', ['id' => $rental->id, 'title' => $rental->slug]))
                        ->setPriority(0.7)
                        ->setChangeFrequency('weekly')
                );
            }

            // Lưu file sitemap.xml
            $sitemap->writeToFile(public_path('sitemap.xml'));

            $this->info('Sitemap has been generated successfully!');
        } catch (\Exception $e) {
            Log::error($e);
            $this->error("Failed generate sitemap: " . $e->getMessage());
        }
    }
}
