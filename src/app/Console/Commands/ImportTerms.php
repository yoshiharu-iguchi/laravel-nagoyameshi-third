<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Term;

class ImportTerms extends Command
{
    protected $signature = 'import:terms';
    protected $description = 'terms.csvから利用規約をインポートします';

    public function handle()
    {
        $path = storage_path('app/terms.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');

        // ヘッダー行がないためスキップしない（必要ならコメントアウトを外す）
        // fgetcsv($csv);

        $count = 0;
        $terms = [];

        while (($row = fgetcsv($csv)) !== false) {
            // データ列数チェック（最低4列必要）
            if (count($row) < 4) {
                $this->warn('データ行が不完全なためスキップされました: ' . implode(',', $row));
                continue;
            }

            $terms[] = [
                'content' => $row[1], // 2列目にHTML本文がある
                'created_at' => $row[2],
                'updated_at' => $row[3],
            ];

            $count++;
        }

        fclose($csv);

        if (!empty($terms)) {
            Term::truncate(); // 既存データを削除して上書き
            Term::insert($terms); // 一括インポート
        }

        $this->info("インポート完了：{$count} 件の利用規約を追加しました。");

        return 0;
    }
}
