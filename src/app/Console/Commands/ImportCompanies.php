<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class ImportCompanies extends Command
{
    protected $signature = 'import:companies';
    protected $description = 'CSVファイルからcompaniesテーブルにデータをインポートする';

    public function handle()
    {
        $path = storage_path('app/companies.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');

        // ヘッダー行を読み飛ばす（不要ならコメントアウト）
        $header = fgetcsv($csv);

        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            try {
                Company::create([
                    'name' => $row[0],
                    'postal_code' => $row[1],
                    'address' => $row[2],
                    'representative' => $row[3],
                    'establishment_date' => $row[4],
                    'capital' => $row[5],
                    'business' => $row[6],
                    'number_of_employees' => $row[7],
                ]);
                $count++;
            } catch (\Exception $e) {
                $this->error("エラー（行 {$count}）: " . $e->getMessage());
            }
        }

        fclose($csv);

        $this->info("インポート完了：{$count} 件のデータを追加しました。");

        return 0;
    }
}
