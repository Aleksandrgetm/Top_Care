<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Support\GalleryImageSyncService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('gallery:sync', function (GalleryImageSyncService $syncService) {
    if (! Schema::hasTable('gallery_images')) {
        $this->error('Table "gallery_images" does not exist. Run migrations first.');
        return 1;
    }

    $result = $syncService->sync();

    $this->info("Found files: {$result['found']}");
    $this->info("Added: {$result['added']}");
    $this->info("Updated: {$result['updated']}");
    $this->info("Skipped duplicates: {$result['skipped']}");

    return 0;
})->purpose('Sync unique images from public/images into gallery_images');
