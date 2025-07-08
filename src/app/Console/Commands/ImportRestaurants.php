<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class ImportRestaurants extends Command
{
    protected $signature = 'import:restaurants';

    protected $description = 'Import restaurants from a CSV file';

    public function handle()
    {
        $path = storage_path('app/restaurants.csv');

        if (!file_exists($path)) {
            $this->error("CSVファイルが見つかりません: {$path}");
            return 1;
        }

        $csv = array_map('str_getcsv', file($path));

        foreach ($csv as $row) {
            if (count($row) < 13) {
                $this->warn("スキップ：不完全な行");
                continue;
            }

            Restaurant::create([
                'id'               => $row[0],
                'name'             => $row[1],
                'image'            => $row[2],
                'description'      => $row[3],
                'lowest_price'     => $row[4],
                'highest_price'    => $row[5],
                'postal_code'      => $row[6],
                'address'          => $row[7],
                'opening_time'     => $row[8],
                'closing_time'     => $row[9],
                'seating_capacity' => $row[10],
                'created_at'       => $row[11],
                'updated_at'       => $row[12],
            ]);
        }

        $this->info("インポートが完了しました！");
        return 0;
    }
}
