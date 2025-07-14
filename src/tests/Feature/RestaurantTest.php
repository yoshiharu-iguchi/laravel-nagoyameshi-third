<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
<<<<<<< HEAD
=======
use App\Models\Restaurant;
>>>>>>> 39e557de3de77eb2f52c384e6553fc3834e47b8a
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function test_user_can_access_restaurants_index(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

    // ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない
    public function test_admin_cannot_access_restaurants_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin,'admin')->get(route('restaurants.index'));

        $response->assertRedirect(route('admin.home'));
    }
<<<<<<< HEAD
=======

    // 未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_guest_can_access_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.show',$restaurant));

        $response->assertStatus(200);
    }

    // ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_user_access_restaurants_show()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.show',$restaurant));

        $response->assertStatus(200);
    }
    // ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない
    public function test_admin_cannot_access_restaurants_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('magoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin,'admin')->get(route('restaurants.show',$restaurant));

        $response->assertRedirect(route('admin.home'));
    }
>>>>>>> 39e557de3de77eb2f52c384e6553fc3834e47b8a
}
