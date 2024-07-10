<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articles')->insert([
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product1.JPG',
                'title' => 'telecommande',
                'desc' => "La telecommande de mon decodeur CANAL+ est en panne, comment la remplacer",
                'prize' => 2000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product2.JPG',
                'title' => 'parafoudre',
                'desc' => "Protegez vos appareils de la foudre avec ce Parafoudre Canal TV.",
                'prize' => 2000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product3.JPG',
                'title' => 'decodeur canal',
                'desc' => "Ne rien placer sur son decodeur a l'exception du disque dur et ne jamais poser son decodeur sur un tissu ou un tapis",
                'prize' => 5000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product4.JPG',
                'title' => 'parabole canal',
                'desc' => "Ou trouver l'offre Parabole CANAL au meilleur prix? Chez Vision Canal",
                'prize' => 6000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product5.JPG',
                'title' => 'cable hdmi',
                'desc' => "Un cordon HDMI HQ de qualiter superieure vous assure une parfaite liaison optimale de la chaine de signal sur un ecran, notamment avec le decodeur",
                'prize' => 2000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product6.JPG',
                'title' => 'tete lnb',
                'desc' => "Decouvrez nos offres Tete LNB parabole universelle",
                'prize' => 2500,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product7.JPG',
                'title' => 'cable coaxial',
                'desc' => "Cables utiliser pour raccordez votre decodeur a une parabole",
                'prize' => 3500,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'img_path' => 'https://visionscanal.com/img/product8.JPG',
                'title' => 'chargeur',
                'desc' => "Chargeur Canal pas cher sur Vision Canal",
                'prize' => 4000,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
