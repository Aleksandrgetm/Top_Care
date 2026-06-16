<?php

namespace App\Console\Commands;

use App\Models\DeliveryPoint;
use Illuminate\Console\Command;

class SyncDpdDeliveryPoints extends Command
{
    protected $signature = 'delivery:sync-dpd';

    protected $description = 'Sync DPD pickup points into delivery_points table using current fallback config';

    public function handle(): int
    {
        $records = collect(config('delivery_points.dpd_fallback', []));

        if ($records->isEmpty()) {
            $this->warn('No DPD fallback points configured.');

            return self::SUCCESS;
        }

        $activeExternalIds = [];

        foreach ($records as $record) {
            $externalId = (string) ($record['external_id'] ?? '');

            if ($externalId === '') {
                continue;
            }

            $activeExternalIds[] = $externalId;

            DeliveryPoint::query()->updateOrCreate(
                [
                    'provider' => 'dpd',
                    'external_id' => $externalId,
                ],
                [
                    'name' => $record['name'] ?? 'DPD Pickup',
                    'country' => $record['country'] ?? 'LV',
                    'city' => $record['city'] ?? null,
                    'address' => $record['address'] ?? null,
                    'postal_code' => $record['postal_code'] ?? null,
                    'latitude' => $record['latitude'] ?? null,
                    'longitude' => $record['longitude'] ?? null,
                    'raw_data' => $record,
                    'is_active' => true,
                ],
            );
        }

        DeliveryPoint::query()
            ->where('provider', 'dpd')
            ->whereNotIn('external_id', $activeExternalIds)
            ->update(['is_active' => false]);

        $this->info('DPD delivery points synced: ' . count($activeExternalIds));

        return self::SUCCESS;
    }
}
