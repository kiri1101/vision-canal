<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'uuid' => Str::uuid(),
                'user_id' => 1,
                'name' => 'Solomon Che',
                'phone' => '699883345',
                'region' => 'Littoral',
                'town' => 'Douala',
                'street' => 'Bonaberi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'user_id' => 1,
                'name' => 'Solomon Che',
                'phone' => '699883345',
                'region' => 'Littoral',
                'town' => 'Douala',
                'street' => 'Bonaberi',
                'created_at' => now()->addMinutes(20),
                'updated_at' => now()->addMinutes(20)
            ],
            [
                'uuid' => Str::uuid(),
                'user_id' => 1,
                'name' => 'Solomon Che',
                'phone' => '699883345',
                'region' => 'Littoral',
                'town' => 'Douala',
                'street' => 'Bonaberi',
                'created_at' => now()->addDays(2),
                'updated_at' => now()->addDays(2)
            ]
        ]);
    }
}
