<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use App\Support\ProductImageThumbnailService;
use Illuminate\Console\Command;

class GenerateProductThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate-thumbnails {--force : Regenerate thumbnails even if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing thumbnails for product images';

    /**
     * Execute the console command.
     */
    public function handle(ProductImageThumbnailService $thumbnailService): int
    {
        $force = (bool) $this->option('force');
        $images = ProductImage::query()
            ->orderBy('product_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($images->isEmpty()) {
            $this->info('No product images found.');

            return self::SUCCESS;
        }

        $generated = 0;
        $skipped = 0;
        $failed = 0;

        $this->withProgressBar($images, function (ProductImage $image) use ($thumbnailService, $force, &$generated, &$skipped, &$failed): void {
            try {
                $existingThumbnailPath = $image->thumbnail_path;
                $thumbnailPath = $thumbnailService->generateForProductImage($image, $force);

                if (! $force && $existingThumbnailPath && $thumbnailPath === $existingThumbnailPath) {
                    $skipped++;

                    return;
                }

                $generated++;
            } catch (\Throwable $exception) {
                $failed++;
                $this->newLine();
                $this->error("Image #{$image->id} failed: {$exception->getMessage()}");
            }
        });

        $this->newLine(2);
        $this->info("Generated: {$generated}");
        $this->info("Skipped: {$skipped}");
        $this->info("Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
