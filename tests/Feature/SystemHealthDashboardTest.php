<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // Giả sử model User của admin

class SystemHealthDashboardTest extends TestCase
{
    use RefreshDatabase; // Reset DB sau mỗi lần test

    public function test_admin_can_view_system_health_dashboard()
    {
        // 1. Arrange: Tạo 1 user admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Act: Đóng vai admin gọi vào route system health
        $response = $this->actingAs($admin)->get(route('system-health.index'));

        // 3. Assert: Kiểm tra HTTP 200 và view chứa các keyword quan trọng
        $response->assertStatus(200);
        $response->assertViewIs('backend.admin.system-health.index');
        $response->assertSee('Pending Queues');
        $response->assertSee('Failed Jobs');
        $response->assertSee('Google Gemini API');
    }

    public function test_normal_user_cannot_view_dashboard()
    {
        // Kiểm tra phân quyền
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('system-health.index'));

        // Phải bị chặn (403 Forbidden hoặc redirect 302)
        $response->assertStatus(403);
    }
}
