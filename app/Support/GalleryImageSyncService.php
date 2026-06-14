<?php

namespace App\Support;

use App\Models\GalleryImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GalleryImageSyncService
{
    /**
     * @return array{found:int,added:int,updated:int,skipped:int}
     */
    public function sync(): array
    {
        if (! Schema::hasTable('gallery_images')) {
            return [
                'found' => 0,
                'added' => 0,
                'updated' => 0,
                'skipped' => 0,
            ];
        }

        $files = collect(File::files(public_path('images')))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true))
            ->sortBy(fn ($file) => strtolower($file->getFilename()))
            ->values();

        $existing = GalleryImage::query()
            ->get()
            ->keyBy(fn (GalleryImage $image) => ltrim((string) $image->image, '/'));

        $nextSortOrder = (int) GalleryImage::query()->max('sort_order');
        $added = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $imagePath = 'images/' . $filename;
            $title = $this->makeTitle($filename);
            $category = $this->detectCategory($filename);

            /** @var GalleryImage|null $existingImage */
            $existingImage = $existing->get($imagePath);

            if ($existingImage) {
                $payload = [];

                if ($existingImage->title !== $title) {
                    $payload['title'] = $title;
                }

                if ($existingImage->category !== $category) {
                    $payload['category'] = $category;
                }

                if ($payload !== []) {
                    $existingImage->update($payload);
                    $updated++;
                } else {
                    $skipped++;
                }

                continue;
            }

            $nextSortOrder++;

            GalleryImage::create([
                'image' => $imagePath,
                'title' => $title,
                'category' => $category,
                'sort_order' => $nextSortOrder,
                'is_active' => true,
            ]);

            $added++;
        }

        return [
            'found' => $files->count(),
            'added' => $added,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }

    public function makeTitle(string $filename): string
    {
        $name = Str::lower(pathinfo($filename, PATHINFO_FILENAME));

        return $this->titleMap()[$name]
            ?? Str::ucfirst(str_replace(['-', '_'], ' ', $name));
    }

    public function detectCategory(string $filename): string
    {
        $name = Str::lower(pathinfo($filename, PATHINFO_FILENAME));

        if ($this->containsAny($name, ['logu', 'logs', 'pvc-logi', 'restauracija'])) {
            return 'Logu restaurācija';
        }

        if ($this->containsAny($name, ['fasades', 'fasade', 'jumta', 'jumts', 'eksterjera'])) {
            return 'Fasādes un jumti';
        }

        if ($this->containsAny($name, ['renovacija', 'interjera', 'iekstdarbi', 'ieksdarbi', 'apdares', 'virtuves', 'istaba', 'koridors', 'demontaza', 'sienu-krasosana'])) {
            return 'Renovācija un iekšdarbi';
        }

        if ($this->containsAny($name, ['brugesana', 'labiekartosana', 'teritorijas', 'kapnu'])) {
            return 'Bruģēšana un labiekārtošana';
        }

        if ($this->containsAny($name, ['malkas', 'malka', 'skaldisana', 'krausana', 'piegade'])) {
            return 'Mobilā malkas skaldīšana';
        }

        return 'Citi darbi';
    }

    /**
     * @return array<string, string>
     */
    private function titleMap(): array
    {
        return [
            'fasada-mazgasana-pirms-pec' => 'Fasādes mazgāšana pirms un pēc',
            'fasades-apdares-darbi-01' => 'Fasādes apdares darbi',
            'fasades-mazgasana-darba-process' => 'Fasādes mazgāšanas process',
            'fasades-mazgasana-privatmaja-jurmala' => 'Privātmājas fasādes mazgāšana',
            'interjera-renovacija-01' => 'Interjera renovācija',
            'jumta-tirisana-latvija' => 'Jumta tīrīšana',
            'jumta-tirisana-pirms-pec' => 'Jumta tīrīšana pirms un pēc',
            'kapnu-izbuve-01' => 'Kāpņu izbūve',
            'koka-logu-restauracija-jurmala' => 'Koka logu restaurācija',
            'logu-restauracijas-darbi-latvija' => 'Koka logu restaurācijas process',
            'majas-eksterjera-atjaunosana' => 'Mājas eksterjera atjaunošana',
            'malkas-krausana' => 'Saskaldīta malka klientam',
            'malkas-skaldisana-1' => 'Mobilā malkas skaldīšana',
            'malkas-skaldisana-2' => 'Mobilās malkas skaldīšanas process',
            'malkas-skaldisana-3' => 'Saskaldīta malka klientam',
            'malkas-skaldisana-un-piegade' => 'Mobilā malkas skaldīšana un piegāde',
            'metala-jumts-pirms-tirisanas' => 'Metāla jumts pirms tīrīšanas',
            'privatmajas-renovacija-latvija' => 'Privātmājas renovācija',
            'profesionala-koka-logu-restauracija-jurmala' => 'Profesionāla koka logu restaurācija',
            'pvc-logi-02' => 'PVC logi',
            'pvc-logi-03' => 'PVC logi',
            'renovacijas-demontaza-01' => 'Renovācijas demontāžas darbi',
            'renovacijas-demontaza-02' => 'Renovācijas demontāžas process',
            'renovacijas-istaba-01' => 'Iekštelpu renovācija',
            'renovacijas-istaba-02' => 'Iekštelpu renovācija',
            'renovacijas-istaba-03' => 'Iekštelpu renovācija',
            'renovacijas-istaba-04' => 'Iekštelpu renovācija',
            'renovacijas-istaba-05' => 'Iekštelpu renovācija',
            'renovacijas-istaba-06' => 'Iekštelpu renovācija',
            'renovacijas-koridors-01' => 'Koridora renovācija',
            'renovacijas-koridors-02' => 'Koridora renovācija',
            'renovacijas-koridors-03' => 'Koridora renovācija',
            'sienu-krasosana-01' => 'Sienu krāsošana',
            'teritorijas-labiekartosana-01' => 'Teritorijas labiekārtošana',
            'teritorijas-labiekartosana-darbi' => 'Teritorijas labiekārtošanas darbi',
            'topcare-komanda' => 'Top Care Group komanda',
            'virtuves-renovacija-01' => 'Virtuves renovācija',
        ];
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
