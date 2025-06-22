<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_user_index()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }


    public function test_authenticated_user_cannot_access_admin_user_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertRedirect('/admin/login');
    }

    /**
     * 管理者は会員一覧ページにアクセスできる
     */
    public function test_authenticated_admin_can_access_user_index()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');
        $response->assertStatus(200);
    }

    /**
     * 未ログインのユーザーは詳細ページにアクセスできない
     */
    public function test_guest_cannot_access_admin_user_show()
    {
        $user = User::factory()->create();

        $response = $this->get("/admin/users/{$user->id}");
        $response->assertRedirect('/admin/login');
    }

    /**
     * 一般ユーザーは詳細ページにアクセスできない
     */
    public function test_authenticated_user_cannot_access_admin_user_show()
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $response = $this->actingAs($user)->get("/admin/users/{$target->id}");
        $response->assertRedirect('/admin/login');
    }

    /**
     * 管理者は詳細ページにアクセスできる
     */
    public function test_authenticated_admin_can_access_user_show()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}");
        $response->assertStatus(200);
    }
}
