<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'services.renew_subscription',
                'banner_path' => 'https://visionscanal.com/img/reabonnements.jpeg',
                'route_name' => 'renew.subscription',
                'alt' => 'Renew Subscription service profile picture',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'services.subscription',
                'banner_path' => 'https://visionscanal.com/img/abonnements.jpeg',
                'route_name' => 'subscription.new',
                'alt' => 'Subscription service profile picture',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'services.accessory',
                'banner_path' => 'https://visionscanal.com/img/shop.jpg',
                'route_name' => 'accessory',
                'alt' => 'Accessory service profile picture',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'services.support',
                'banner_path' => 'https://visionscanal.com/img/technic.JPG',
                'route_name' => 'support',
                'alt' => 'Technical support service profile picture',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
