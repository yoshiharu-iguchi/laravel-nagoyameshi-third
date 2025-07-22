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
        fgetcsv($csv); // ヘッダー行を読み飛ばす

        $count = 0;
        $terms = [];

        while (($row = fgetcsv($csv)) !== false) {
            // 1列目が利用規約本文である前提
            $terms[] = [
                'content' => $row[0],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $count++;
        }

        fclose($csv);

        if (!empty($terms)) {
            Term::insert($terms); // 一括インポート
        }

        $this->info("インポート完了：{$count} 件の利用規約を追加しました。");

        return 0;
    }
}
