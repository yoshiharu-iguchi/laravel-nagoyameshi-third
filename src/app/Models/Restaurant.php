<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function regularHolidays()
    {
        return $this->belongsToMany(RegularHoliday::class,'regular_holiday_restaurant')->withTimestamps();
    }
}
