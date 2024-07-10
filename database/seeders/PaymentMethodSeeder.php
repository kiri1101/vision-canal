<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Current Account',
                'short_code' => 'VISION',
                'img_path' => 'assets/images/logo.png',
                'img_alt' => 'User current account',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'MTN Mobile Money',
                'short_code' => 'MTN',
                'img_path' => 'assets/images/momo.png',
                'img_alt' => 'MTN MoMo payment method',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Orange Money',
                'short_code' => 'ORANGE',
                'img_path' => 'assets/images/om.png',
                'img_alt' => 'Orange money payment method',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
