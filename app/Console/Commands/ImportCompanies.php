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

        // ヘッダー行がある場合は読み飛ばす
        // $header = fgetcsv($csv);

        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            if (count($row) < 11) {
                $this->error("列数が不足しています（行 {$count}）");
                continue;
            }

            try {
                Company::create([
                    'name' => $row[1],
                    'postal_code' => $row[2],
                    'address' => $row[3],
                    'representative' => $row[4],
                    'establishment_date' => $row[5],
                    'capital' => $row[6],
                    'business' => $row[7],
                    'number_of_employees' => $row[8],
                    'created_at' => $row[9],
                    'updated_at' => $row[10],
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