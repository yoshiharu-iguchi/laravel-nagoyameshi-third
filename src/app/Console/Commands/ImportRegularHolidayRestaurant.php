<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRegularHolidayRestaurant extends Command
{
    protected $signature = 'import:regular_holiday_restaurant';
    protected $description = 'CSVファイルから regular_holiday_restaurant テーブルにデータをインポートする';

    public function handle()
    {
        $path = storage_path('app/regular_holiday_restaurants.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');

        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            if (count($row) < 5) continue; // データの列数が不足している場合スキップ

            DB::table('regular_holiday_restaurant')->insert([
                // $row[0] は id だが、auto-increment のため **省略**
                'restaurant_id' => $row[1],
                'regular_holiday_id' => $row[2],
                'created_at' => $row[3],
                'updated_at' => $row[4],
            ]);

            $count++;
        }

        fclose($csv);

        $this->info("インポート完了：{$count} 件のデータを追加しました。");
        return 0;
    }
}