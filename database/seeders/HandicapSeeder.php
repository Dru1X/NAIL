<?php

namespace Database\Seeders;

use App\Models\Handicap;
use Illuminate\Database\Seeder;

class HandicapSeeder extends Seeder
{
    public function run(): void
    {
        // Clear out the handicaps table
        Handicap::truncate();

        // Import all handicap data from a CSV file
        $path = database_path('data/handicaps.csv');
        $file = fopen($path, 'r');

        try {
            $headers = fgetcsv($file);
            $data    = [];

            while ($line = fgetcsv($file)) {
                $data[] = array_combine($headers, $line);
            }

            Handicap::insert($data);
        } finally {
            fclose($file);
        }

    }
}
