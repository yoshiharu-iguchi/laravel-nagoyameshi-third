<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegularHoliday;

class ImportRegularHolidays extends Command
{
    protected $signature = 'import:regularholidays';
    protected $description = 'Import regular holidays from CSV';

    public function handle()
    {
        $path = storage_path('app/regular_holidays.csv');

        if (!file_exists($path)) {
            $this->error("CSV file not found at: $path");
            return Command::FAILURE;
        }

        $rows = array_map('str_getcsv', file($path));

        foreach ($rows as $row) {
            if (empty($row[1])) continue;

            RegularHoliday::updateOrCreate(
                ['day' => $row[1]],
                ['day_index' => isset($row[2]) ? (int)$row[2] : null]
            );
        }

        $this->info("✅ Regular holidays imported successfully.");
        return Command::SUCCESS;
    }
}
