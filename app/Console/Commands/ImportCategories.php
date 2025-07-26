<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ImportCategories extends Command
{
    protected $signature = 'import:categories';

    protected $description = 'Import categories from a CSV file';

    public function handle()
    {
        $path = storage_path('app/categories.csv');

        if (!file_exists($path)) {
            $this->error("CSVファイルが見つかりません: {$path}");
            return 1;
        }

        $csv = array_map('str_getcsv', file($path));

        foreach ($csv as $row) {
            if (count($row) < 3) {
                $this->warn("スキップ：不完全な行");
                continue;
            }

            Category::create([
                'id'         => $row[0],
                'name'       => $row[1],
                'created_at' => $row[2],
                'updated_at' => $row[3] ?? now(),
            ]);
        }

        $this->info("カテゴリのインポートが完了しました！");
        return 0;
    }
}