<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Term;

class ImportTerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:terms';
    protected $description = 'terms.csvから利用規約をインポートします';

    /**
     * The console command description.
     *
     * @var string
     */
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('app/terms.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが見つかりません: ' . $path);
            return 1;
        }

        $file = fopen($path, 'r');
        fgetcsv($file); // ヘッダーを読み飛ばす

        $terms = [];

        while (($row = fgetcsv($file)) !== false) {
            $terms[] = [
                'content' => $row[0],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($file);

        Term::insert($terms); // 一括インポート
        $this->info('利用規約をインポートしました。');

        return 0;
    }
}
