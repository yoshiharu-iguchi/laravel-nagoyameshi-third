<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportRestaurantUser extends Command
{
    protected $signature = 'import:restaurant_user';
    protected $description = 'CSVファイルからrestaurant_userテーブルにデータをインポートする';

    public function handle()
    {
        $path = storage_path('app/restaurant_user.csv');

        if (!file_exists($path)) {
            $this->error("CSVファイルが見つかりません: $path");
            return 1;
        }

        $csv = fopen($path, 'r');

        // ヘッダーを読み飛ばす（必要に応じてコメントアウト）
        $header = fgetcsv($csv);

        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            // データの数が2列以外の場合スキップ
            if (count($row) < 2) continue;

            DB::table('restaurant_user')->insert([
                'restaurant_id' => $row[1],
                'user_id' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $count++;
        }

        fclose($csv);

        $this->info("インポート完了：{$count} 件のデータを追加しました。");

        return 0;
    }
}
