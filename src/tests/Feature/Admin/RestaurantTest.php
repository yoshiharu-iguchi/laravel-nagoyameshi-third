<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_restaurants_index()
    {
        $response = $this->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_index()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_show()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_create()
    {
        $response = $this->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_create()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_store()
    {
        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant_data);

        unset($restaurant_data['category_ids'], $restaurant_data['regular_holiday_ids']);
        $this->assertDatabaseMissing('restaurants', $restaurant_data);

        foreach ($category_ids as $category_id) {
            $this->assertDatabaseMissing('category_restaurant', ['category_id' => $category_id]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseMissing('regular_holiday_restaurant', ['regular_holiday_id' => $regular_holiday_id]);
        }

        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_access_admin_restaurants_store()
    {
        $user = User::factory()->create();

        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->actingAs($user)->post(route('admin.restaurants.store'), $restaurant_data);

        unset($restaurant_data['category_ids'], $restaurant_data['regular_holiday_ids']);
        $this->assertDatabaseMissing('restaurants', $restaurant_data);

        foreach ($category_ids as $category_id) {
            $this->assertDatabaseMissing('category_restaurant', ['category_id' => $category_id]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseMissing('regular_holiday_restaurant', ['regular_holiday_id' => $regular_holiday_id]);
        }

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_admin_restaurants_store()
    {
        $admin = Admin::factory()->create();

        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト店',
            'description' => '説明文',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '1234567',
            'address' => 'テスト住所',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 40,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'), $restaurant_data);

        unset($restaurant_data['category_ids'], $restaurant_data['regular_holiday_ids']);
        $this->assertDatabaseHas('restaurants', $restaurant_data);

        $restaurant = Restaurant::latest('id')->first();

        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', ['restaurant_id' => $restaurant->id, 'category_id' => $category_id]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', ['restaurant_id' => $restaurant->id, 'regular_holiday_id' => $regular_holiday_id]);
        }

        $response->assertRedirect(route('admin.restaurants.index'));
    }

    public function test_admin_can_update_restaurant_with_relations()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $categories = Category::factory()->count(2)->create();
        $regular_holidays = RegularHoliday::factory()->count(2)->create();

        $data = [
            'name' => '更新店舗',
            'description' => '更新された説明',
            'lowest_price' => 2000,
            'highest_price' => 8000,
            'postal_code' => '7654321',
            'address' => '更新住所',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 100,
            'category_ids' => $categories->pluck('id')->toArray(),
            'regular_holiday_ids' => $regular_holidays->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $restaurant), $data);
        $response->assertRedirect(route('admin.restaurants.show', $restaurant));

        $this->assertDatabaseHas('restaurants', [
            'id' => $restaurant->id,
            'name' => '更新店舗',
        ]);
    }

    public function test_admin_can_delete_restaurant()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.restaurants.index'));
        $this->assertDatabaseMissing('restaurants', ['id' => $restaurant->id]);
    }
}