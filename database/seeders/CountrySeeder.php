<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            [
                'name' => 'Cameroun',
                'slug' => 'cameroon',
                'img_path' => 'assets/images/country/cameroon.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Republique de l'Afrique centrale",
                'slug' => 'caf',
                'img_path' => 'assets/images/country/central-republic.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Tchad",
                'slug' => 'tchad',
                'img_path' => 'assets/images/country/tchad.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Guinee",
                'slug' => 'guinee',
                'img_path' => 'assets/images/country/guinea.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Gabon",
                'slug' => 'gabon',
                'img_path' => 'assets/images/country/gabon.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "Cote d'ivoire",
                'slug' => 'coast',
                'img_path' => 'assets/images/country/ivory-coast.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
