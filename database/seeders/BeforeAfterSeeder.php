<?php

namespace Database\Seeders;

use App\Models\BeforeAfterItem;
use Illuminate\Database\Seeder;

class BeforeAfterSeeder extends Seeder
{
    public function run(): void
    {
        if (BeforeAfterItem::query()->exists()) {
            return;
        }

        $items = [
            [
                'title' => 'Fasādes mazgāšana',
                'description' => 'Privātmājas fasādes attīrīšana no netīrumiem un bioloģiskā aplikuma.',
                'image' => '/images/fasada-mazgasana-pirms-pec.png',
                'before_image' => null,
                'after_image' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Jumta tīrīšana',
                'description' => 'Jumta seguma attīrīšana no nosēdumiem, sūnām un uzkrātajiem netīrumiem.',
                'image' => '/images/jumta-tirisana-pirms-pec.png',
                'before_image' => null,
                'after_image' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            BeforeAfterItem::create($item);
        }
    }
}
