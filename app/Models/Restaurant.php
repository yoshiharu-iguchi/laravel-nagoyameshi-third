<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Restaurant extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'id', 'name', 'image', 'description',
        'lowest_price', 'highest_price',
        'postal_code', 'address',
        'opening_time', 'closing_time',
        'seating_capacity', 'created_at', 'updated_at',
    ];

    /**
     * カテゴリとの多対多リレーション
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * 定休日との多対多リレーション
     */
    public function regularHolidays()
    {
        return $this->belongsToMany(RegularHoliday::class, 'regular_holiday_restaurant')->withTimestamps();
    }

    /**
     * エイリアス：定休日（スネークケース）
     * Bladeテンプレートなどで `regular_holidays` としてアクセスできるようにする
     */
    public function regular_holidays()
    {
        return $this->regularHolidays();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorited_users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function ratingSortable($query,$direction) {
        return $query->withAvg('reviews','score')->orderBy('reviews_avg_score',$direction);
    }

    public function popularSortable($query,$direction) {
        return $query->withCount('reservations')->orderBy('reservations_count',$direction);
    }
}