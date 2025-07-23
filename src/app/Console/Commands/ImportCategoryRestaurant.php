<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportCategoryRestaurant extends Command
{
    protected $signature = 'import:category_restaurants';
    protected $description = 'CSVファイルからcategory_restaurantテーブルにデータをインポートする';

    public function handle()
    {
        $path = storage_path('app/category_restaurants.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');
        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            if (count($row) < 5) {
                continue; // 列数チェック
            }

            DB::table('category_restaurant')->insert([
                'restaurant_id' => $row[1],
                'category_id'   => $row[2],
                'created_at'    => $row[3],
                'updated_at'    => $row[4],
            ]);

            $count++;
        }

        fclose($csv);
        $this->info("インポート完了：{$count} 件のデータを追加しました。");

        return 0;
    }
}
