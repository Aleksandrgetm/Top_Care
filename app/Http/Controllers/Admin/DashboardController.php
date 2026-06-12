<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterItem;
use App\Models\Service;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $servicesCount = Service::count();
        $beforeAfterCount = BeforeAfterItem::count();

        $latestUpdate = collect([
            Service::max('updated_at'),
            BeforeAfterItem::max('updated_at'),
        ])->filter()->map(
            fn ($value) => $value instanceof CarbonInterface ? $value : Carbon::parse($value)
        )->sortDesc()->first();

        return view('admin.dashboard', [
            'adminProps' => [
                'user' => [
                    'name' => auth()->user()?->name,
                    'email' => auth()->user()?->email,
                ],
                'stats' => [
                    [
                        'label' => 'Services',
                        'value' => $servicesCount,
                        'caption' => 'Published service categories',
                    ],
                    [
                        'label' => 'Pirms/Pec',
                        'value' => $beforeAfterCount,
                        'caption' => 'Before and after projects',
                    ],
                    [
                        'label' => 'Last update',
                        'value' => $latestUpdate?->format('d.m.Y H:i') ?? 'No updates yet',
                        'caption' => 'Latest content change',
                    ],
                ],
            ],
        ]);
    }
}
