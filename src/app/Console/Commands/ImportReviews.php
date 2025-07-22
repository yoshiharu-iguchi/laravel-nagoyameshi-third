<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;
use Carbon\Carbon;
use Exception;

class ImportReviews extends Command
{
    protected $signature = 'import:reviews';
    protected $description = 'CSVファイルからreviewsテーブルにデータをインポート';

    public function handle()
    {
        $path = storage_path('app/reviews.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');
        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            if (count($row) < 4) continue;

            // created_atが存在し、日時として有効かチェック
            try {
                $created_at = isset($row[4]) && strtotime($row[4]) !== false
                    ? Carbon::parse($row[4])
                    : now();
            } catch (Exception $e) {
                $created_at = now(); // パース失敗時は現在時刻を使用
            }

            Review::create([
                'content'       => $row[0],
                'score'         => (int)$row[1],
                'restaurant_id' => (int)$row[2],
                'user_id'       => (int)$row[3],
                'created_at'    => $created_at,
                'updated_at'    => now(),
            ]);

            $count++;
        }

        fclose($csv);
        $this->info("インポート完了：{$count} 件のデータを追加しました。");
        return 0;
    }
}
