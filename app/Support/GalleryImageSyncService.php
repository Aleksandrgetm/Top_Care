<?php

namespace App\Support;

use App\Models\GalleryImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GalleryImageSyncService
{
    /**
     * @return array{found:int,added:int,skipped:int}
     */
    public function sync(): array
    {
        if (! Schema::hasTable('gallery_images')) {
            return [
                'found' => 0,
                'added' => 0,
                'skipped' => 0,
            ];
        }

        $files = collect(File::files(public_path('images')))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true))
            ->sortBy(fn ($file) => strtolower($file->getFilename()))
            ->values();

        $existing = GalleryImage::query()
            ->pluck('image')
            ->filter()
            ->map(fn (string $path) => ltrim($path, '/'))
            ->all();

        $existingMap = array_fill_keys($existing, true);
        $nextSortOrder = (int) GalleryImage::query()->max('sort_order');
        $added = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $imagePath = 'images/' . $filename;

            if (isset($existingMap[$imagePath])) {
                $skipped++;
                continue;
            }

            $nextSortOrder++;

            GalleryImage::create([
                'image' => $imagePath,
                'title' => $this->makeTitle($filename),
                'category' => $this->detectCategory($filename),
                'sort_order' => $nextSortOrder,
                'is_active' => true,
            ]);

            $existingMap[$imagePath] = true;
            $added++;
        }

        return [
            'found' => $files->count(),
            'added' => $added,
            'skipped' => $skipped,
        ];
    }

    private function makeTitle(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $normalized = str_replace(['-', '_'], ' ', Str::lower($name));

        return Str::ucfirst($normalized);
    }

    private function detectCategory(string $filename): string
    {
        $name = Str::lower(pathinfo($filename, PATHINFO_FILENAME));

        if ($this->containsAny($name, ['logu', 'logs', 'restauracija'])) {
            return 'Logu restaurācija';
        }

        if ($this->containsAny($name, ['fasades', 'fasade', 'jumta', 'jumts'])) {
            return 'Fasādes un jumti';
        }

        if ($this->containsAny($name, ['renovacija', 'iekstdarbi', 'ieksdarbi', 'apdares', 'virtuves', 'istaba', 'koridors'])) {
            return 'Renovācija un iekšdarbi';
        }

        if ($this->containsAny($name, ['brugesana', 'labiekartosana', 'teritorijas', 'kapnu'])) {
            return 'Bruģēšana un labiekārtošana';
        }

        if ($this->containsAny($name, ['malkas', 'malka', 'skaldisana'])) {
            return 'Mobilā malkas skaldīšana';
        }

        return 'Citi darbi';
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
