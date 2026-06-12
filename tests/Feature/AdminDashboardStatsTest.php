<?php

namespace Tests\Feature;

use App\Models\BeforeAfterItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_counts_for_admin(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        Service::query()->create([
            'title' => 'Renovation',
            'slug' => 'renovation',
        ]);

        BeforeAfterItem::query()->create([
            'title' => 'Facade cleaning',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Services')
            ->assertSee('Pirms/Pec')
            ->assertSee('1', false);
    }
}
