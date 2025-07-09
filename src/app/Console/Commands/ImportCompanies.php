<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class ImportCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:companies';
    protected $description = 'Import companies from a CSV file';

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
        $path = storage_path('app/companies.csv');

        if (!file_exists($path)) {
            $this->error('CSVファイルが存在しません: ' . $path);
            return 1;
        }

        $csv = fopen($path, 'r');
        $header = fgetcsv($csv); // 1行目のヘッダー読み込み

        while (($row = fgetcsv($csv)) !== false) {
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
        }

        fclose($csv);

        $this->info('インポートが完了しました。');

        return 0;
    }
}
