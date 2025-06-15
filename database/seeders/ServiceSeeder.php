<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['service_type' => 'دعم نفسي'],
            ['service_type' => 'فرص عمل'],
            ['service_type' => 'منازل'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate($service); // يمنع التكرار إذا تم تشغيله أكثر من مرة
        }
    }
}
