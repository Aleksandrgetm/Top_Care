<?php

namespace App\Console\Commands;

use App\Models\DeliveryPoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncOmnivaDeliveryPoints extends Command
{
    protected $signature = 'delivery:sync-omniva';

    protected $description = 'Sync active Latvia Omniva parcel machines into delivery_points table';

    public function handle(): int
    {
        $url = 'https://www.omniva.ee/locations.json';

        $response = Http::acceptJson()
            ->timeout(20)
            ->withHeaders([
                'User-Agent' => config('app.name', 'TopCare') . ' Delivery Point Sync',
            ])
            ->get($url);

        if (! $response->successful()) {
            $this->error('Omniva locations request failed.');

            return self::FAILURE;
        }

        $records = collect($response->json())
            ->filter(fn (array $item) => ($item['A0_NAME'] ?? null) === 'LV' && ($item['TYPE'] ?? null) === '0')
            ->values();

        $activeExternalIds = [];

        foreach ($records as $record) {
            $externalId = $this->externalId($record);
            $activeExternalIds[] = $externalId;

            DeliveryPoint::query()->updateOrCreate(
                [
                    'provider' => 'omniva',
                    'external_id' => $externalId,
                ],
                [
                    'name' => trim((string) ($record['NAME'] ?? 'Omniva pakomāts')),
                    'country' => $record['A0_NAME'] ?? 'LV',
                    'city' => $this->city($record),
                    'address' => $this->address($record),
                    'postal_code' => $record['ZIP'] ?? null,
                    'latitude' => $record['Y_COORDINATE'] ?? null,
                    'longitude' => $record['X_COORDINATE'] ?? null,
                    'raw_data' => $record,
                    'is_active' => true,
                ],
            );
        }

        DeliveryPoint::query()
            ->where('provider', 'omniva')
            ->whereNotIn('external_id', $activeExternalIds)
            ->update(['is_active' => false]);

        $this->info('Omniva delivery points synced: ' . count($activeExternalIds));

        return self::SUCCESS;
    }

    private function externalId(array $record): string
    {
        return md5(implode('|', [
            'omniva',
            $record['NAME'] ?? '',
            $record['ZIP'] ?? '',
            $record['A5_NAME'] ?? '',
            $record['A7_NAME'] ?? '',
        ]));
    }

    private function city(array $record): ?string
    {
        foreach (['A3_NAME', 'A2_NAME', 'A1_NAME'] as $key) {
            if (filled($record[$key] ?? null)) {
                return trim((string) $record[$key]);
            }
        }

        return null;
    }

    private function address(array $record): ?string
    {
        $parts = collect([
            $record['A5_NAME'] ?? null,
            $record['A7_NAME'] ?? null,
        ])->filter(fn ($part) => filled($part));

        return $parts->isNotEmpty() ? $parts->implode(' ') : null;
    }
}
