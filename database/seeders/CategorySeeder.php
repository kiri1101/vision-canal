<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Access',
                'fee' => 5000,
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Evasion',
                'fee' => 10000,
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Access plus',
                'fee' => 17000,
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Evasion plus',
                'fee' => 22500,
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Tout Canal',
                'fee' => 45000,
                'is_special' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
