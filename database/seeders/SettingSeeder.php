<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'home_page_video' => 'https://www.youtube.com/embed/7ElaIUQyGR4',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
