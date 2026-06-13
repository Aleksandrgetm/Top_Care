<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        if (GalleryImage::query()->exists()) {
            return;
        }

        $items = [
            [
                'title' => 'Koka logu restaurācija',
                'category' => 'Restaurācija',
                'image' => '/images/profesionala-koka-logu-restauracija-jurmala.jpeg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Renovācijas process objektā',
                'category' => 'Renovācija',
                'image' => '/images/privatmajas-renovacija-latvija.jpeg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Detalizēti restaurācijas darbi',
                'category' => 'Logi',
                'image' => '/images/koka-logu-restauracija-jurmala.jpeg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Darba process objektā',
                'category' => 'Process',
                'image' => '/images/logu-restauracijas-darbi-latvija.jpeg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Eksterjera atjaunošana',
                'category' => 'Eksterjers',
                'image' => '/images/majas-eksterjera-atjaunosana.jpeg',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Fasādes kopšana',
                'category' => 'Fasāde',
                'image' => '/images/fasades-mazgasana-privatmaja-jurmala.jpeg',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Ikdienas darba process',
                'category' => 'Ikdiena',
                'image' => '/images/fasades-mazgasana-darba-process.png',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Teritorijas sakārtošana',
                'category' => 'Labiekārtošana',
                'image' => '/images/teritorijas-labiekartosana-darbi.jpeg',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Mobilā malkas skaldīšana',
                'category' => 'Papildu pakalpojumi',
                'image' => '/images/malkas-skaldisana-un-piegade.jpeg',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Komanda objektos',
                'category' => 'Top Care Group',
                'image' => '/images/topcare-komanda.png',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            GalleryImage::create($item);
        }
    }
}
