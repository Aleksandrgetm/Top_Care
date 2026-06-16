<?php

namespace App\Support;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ProductImageThumbnailService
{
    private const THUMBNAIL_SIZE = 600;

    public function storeUploadedImage(UploadedFile $image): array
    {
        $originalPath = $image->store('products', 'public');

        try {
            $thumbnailPath = $this->generateThumbnailFromPath($originalPath);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($originalPath);

            throw $exception;
        }

        return [
            'image_path' => $originalPath,
            'thumbnail_path' => $thumbnailPath,
        ];
    }

    public function generateForProductImage(ProductImage $productImage, bool $force = false): ?string
    {
        if (! $productImage->image_path) {
            return null;
        }

        $disk = Storage::disk('public');
        $existingThumbnailPath = $productImage->thumbnail_path;

        if (
            ! $force
            && $existingThumbnailPath
            && $disk->exists($existingThumbnailPath)
        ) {
            return $existingThumbnailPath;
        }

        if ($force && $existingThumbnailPath) {
            $disk->delete($existingThumbnailPath);
        }

        $thumbnailPath = $this->generateThumbnailFromPath($productImage->image_path, $existingThumbnailPath);

        $productImage->forceFill([
            'thumbnail_path' => $thumbnailPath,
        ])->save();

        return $thumbnailPath;
    }

    private function generateThumbnailFromPath(string $originalPath, ?string $preferredThumbnailPath = null): string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($originalPath)) {
            throw new RuntimeException("Original image does not exist: {$originalPath}");
        }

        $absoluteOriginalPath = $disk->path($originalPath);
        $imageInfo = @getimagesize($absoluteOriginalPath);

        if ($imageInfo === false) {
            throw new RuntimeException("Unable to read image dimensions: {$originalPath}");
        }

        [$width, $height, $imageType] = $imageInfo;
        $sourceImage = $this->createImageResource($absoluteOriginalPath, $imageType);

        if (! $sourceImage) {
            throw new RuntimeException("Unsupported image type for thumbnail generation: {$originalPath}");
        }

        $thumbnailImage = imagecreatetruecolor(self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE);

        if ($thumbnailImage === false) {
            imagedestroy($sourceImage);

            throw new RuntimeException('Unable to allocate thumbnail image resource.');
        }

        $this->prepareCanvas($thumbnailImage, $imageType);

        $cropSize = min($width, $height);
        $sourceX = (int) floor(($width - $cropSize) / 2);
        $sourceY = (int) floor(($height - $cropSize) / 2);

        imagecopyresampled(
            $thumbnailImage,
            $sourceImage,
            0,
            0,
            $sourceX,
            $sourceY,
            self::THUMBNAIL_SIZE,
            self::THUMBNAIL_SIZE,
            $cropSize,
            $cropSize
        );

        $thumbnailPath = $preferredThumbnailPath ?: $this->thumbnailPathFor($originalPath, $imageType);
        $absoluteThumbnailPath = $disk->path($thumbnailPath);
        $thumbnailDirectory = dirname($absoluteThumbnailPath);

        if (! is_dir($thumbnailDirectory) && ! mkdir($thumbnailDirectory, 0755, true) && ! is_dir($thumbnailDirectory)) {
            imagedestroy($thumbnailImage);
            imagedestroy($sourceImage);

            throw new RuntimeException("Unable to create thumbnail directory: {$thumbnailDirectory}");
        }

        $this->saveThumbnail($thumbnailImage, $absoluteThumbnailPath);

        imagedestroy($thumbnailImage);
        imagedestroy($sourceImage);

        return $thumbnailPath;
    }

    private function createImageResource(string $absoluteOriginalPath, int $imageType): mixed
    {
        return match ($imageType) {
            IMAGETYPE_JPEG => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($absoluteOriginalPath) : false,
            IMAGETYPE_PNG => function_exists('imagecreatefrompng') ? @imagecreatefrompng($absoluteOriginalPath) : false,
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absoluteOriginalPath) : false,
            default => false,
        };
    }

    private function prepareCanvas(mixed $thumbnailImage, int $imageType): void
    {
        if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($thumbnailImage, false);
            imagesavealpha($thumbnailImage, true);
            $transparent = imagecolorallocatealpha($thumbnailImage, 0, 0, 0, 127);
            imagefilledrectangle($thumbnailImage, 0, 0, self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE, $transparent);
        } else {
            $background = imagecolorallocate($thumbnailImage, 255, 255, 255);
            imagefilledrectangle($thumbnailImage, 0, 0, self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE, $background);
        }
    }

    private function saveThumbnail(mixed $thumbnailImage, string $absoluteThumbnailPath): void
    {
        $extension = strtolower(pathinfo($absoluteThumbnailPath, PATHINFO_EXTENSION));

        $saved = match ($extension) {
            'webp' => function_exists('imagewebp') ? @imagewebp($thumbnailImage, $absoluteThumbnailPath, 82) : false,
            'png' => @imagepng($thumbnailImage, $absoluteThumbnailPath, 6),
            'jpg', 'jpeg' => @imagejpeg($thumbnailImage, $absoluteThumbnailPath, 84),
            default => false,
        };

        if (! $saved) {
            throw new RuntimeException("Unable to save thumbnail: {$absoluteThumbnailPath}");
        }
    }

    private function thumbnailPathFor(string $originalPath, int $imageType): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'] ?? 'products';
        $filename = $pathInfo['filename'] ?? Str::random(20);
        $extension = function_exists('imagewebp')
            ? 'webp'
            : match ($imageType) {
                IMAGETYPE_PNG => 'png',
                default => 'jpg',
            };

        return "{$directory}/thumbnails/{$filename}.{$extension}";
    }
}
