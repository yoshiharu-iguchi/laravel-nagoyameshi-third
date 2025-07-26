<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    // 未ログインのユーザーは管理者側のカテゴリ一覧ページにアクセスできない
    public function test_guest_cannot_access_admin_categories_index()
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側のカテゴリ一覧ページにアクセスできない
    public function test_user_cannot_access_admin_categories_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者はカテゴリ一覧ページにアクセスできる
    public function test_admin_can_access_admin_categories_index()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    // 未ログインのユーザーはカテゴリを登録できない
    public function test_guest_cannot_access_admin_categories_store()
    {
        $category_data = ['name' => 'テスト'];

        $response = $this->post(route('admin.categories.store'), $category_data);
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('categories', $category_data);
    }

    // ログイン済みの一般ユーザーはカテゴリを登録できない
    public function test_user_cannot_access_admin_categories_store()
    {
        $user = User::factory()->create();
        $category_data = ['name' => 'テスト'];

        $response = $this->actingAs($user)->post(route('admin.categories.store'), $category_data);
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('categories', $category_data);
    }

    // ログイン済みの管理者はカテゴリを登録できる
    public function test_admin_can_access_admin_categories_store()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $category_data = ['name' => 'テスト'];
        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), $category_data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $category_data);
    }

    // 未ログインのユーザーはカテゴリを更新できない
    public function test_guest_cannot_access_admin_categories_update()
    {
        $category = Category::factory()->create();
        $new_data = ['name' => '更新カテゴリ'];

        $response = $this->patch(route('admin.categories.update', $category), $new_data);
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('categories', $new_data);
    }

    // 一般ユーザーはカテゴリを更新できない
    public function test_user_cannot_access_admin_categories_update()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $new_data = ['name' => '更新カテゴリ'];

        $response = $this->actingAs($user)->patch(route('admin.categories.update', $category), $new_data);
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('categories', $new_data);
    }

    // 管理者はカテゴリを更新できる
    public function test_admin_can_access_admin_categories_update()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();
        $new_data = ['name' => '更新カテゴリ'];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.categories.update', $category), $new_data);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $new_data);
    }

    // 未ログインユーザーは削除できない
    public function test_guest_cannot_access_admin_categories_destroy()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    // 一般ユーザーは削除できない
    public function test_user_cannot_access_admin_categories_destroy()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    // 管理者は削除できる
    public function test_admin_can_access_admin_categories_destroy()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
