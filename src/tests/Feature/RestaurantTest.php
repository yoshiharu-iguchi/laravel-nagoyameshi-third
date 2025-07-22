<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    // 未ログインのユーザーは会員側の店舗一覧ページにアクセスできる
    public function test_guest_can_access_restaurants_index()
    {
        $response = $this->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    // ログイン済みの一般ユーザーは会員側の店舗一覧ページにアクセスできる
    public function test_user_can_access_restaurants_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    // ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない
    public function test_admin_cannot_access_restaurants_index()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.index'));
        $response->assertRedirect(route('admin.home'));
    }

    // 未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_guest_can_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

    // ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_user_can_access_restaurant_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

    // ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない
    public function test_admin_cannot_access_restaurant_show()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.home'));
    }
}
