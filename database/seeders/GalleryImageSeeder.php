<?php

namespace Database\Seeders;

use App\Support\GalleryImageSyncService;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        app(GalleryImageSyncService::class)->sync();
    }
}
