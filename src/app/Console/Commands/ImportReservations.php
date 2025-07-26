<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ImportReservations extends Command
{
    protected $signature = 'import:reservations';
    protected $description = 'CSVファイルから予約データをインポートする';

    public function handle()
    {
        $path = storage_path('app/reservations.csv');

        if (!file_exists($path)) {
            $this->error("CSVファイルが見つかりません: {$path}");
            return 1;
        }

        $csv = fopen($path, 'r');
        $count = 0;

        // ヘッダーがある場合は1行スキップ（なければコメントアウト）
        // fgetcsv($csv);

        while (($row = fgetcsv($csv)) !== false) {
            if (count($row) < 7) continue;

            $user_id = $row[3];

            // user_id が存在するか確認
            if (!User::find($user_id)) {
                Log::warning("スキップ：user_id {$user_id} は存在しません。");
                continue;
            }

            try {
                Reservation::create([
                    'reserved_datetime' => $row[1],
                    'number_of_people' => $row[2],
                    'restaurant_id' => $row[3],
                    'user_id' => $row[4],
                    'created_at' => $row[5],
                    'updated_at' => $row[6],
                ]);

                $count++;
            } catch (\Exception $e) {
                Log::error("エラー：予約の作成に失敗しました。user_id={$user_id}。詳細: " . $e->getMessage());
            }
        }

        fclose($csv);

        $this->info("インポート完了：{$count} 件の予約を追加しました。");

        return 0;
    }
}