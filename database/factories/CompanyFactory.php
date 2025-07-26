<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{

    public function definition():array
    {
        return [
            'name' => 'テスト',
            'postal_code' => '0000000',
            'address' => 'テスト',
            'representative' => 'テスト',
            'establishment_date' => 'テスト',
            'capital' => 'テスト',
            'business' => 'テスト',
            'number_of_employees' => 'テスト',
        ];
    }
}