<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_login_page(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_can_log_in_and_is_redirected_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@topcaregroup.lv',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($admin);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
