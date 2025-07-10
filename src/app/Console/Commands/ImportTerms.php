<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Term;

class ImportTerms extends Command
{
    protected $signature = 'import:terms';
    protected $description = 'CSVファイルからtermsテーブルにデータをインポートする';

    public function handle()
    {
        $path = storage_path('app/terms.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');

        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            try {
                Term::create([
                    'content' => $row[1], // 2列目に利用規約の本文
                ]);
                $count++;
            } catch (\Exception $e) {
                $this->error("エラー（行 {$count}）: " . $e->getMessage());
            }
        }

        fclose($csv);

        $this->info("インポート完了：{$count} 件の利用規約を追加しました。");
        return 0;
    }
}