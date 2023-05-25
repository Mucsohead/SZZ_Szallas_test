<?php

namespace Database\Seeders;

use App\Models\Companies;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompaniesTestCSV extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear companies table
        Companies::truncate();

        // Get the absolute path of the csv file
        $csvPath = database_path('testCompanyDB.csv');

        // Check the file
        if(!file_exists($csvPath)){
            Log::error('testCompanyDB.csv missing.');
            return;
        }

        $copyQuery = "copy companies from '$csvPath' DELIMITER ';' CSV HEADER";

        // Run the COPY command
        if(!DB::statement($copyQuery)){
            Log::error('Failed to copy the CSV rows.');
            return;
        }

        $setAutoIncQuery = "SELECT setval('\"companies_companyId_seq\"', (SELECT MAX(\"companyId\") FROM companies))";

        //Set companyId column autoincrement value to the max Id
        if(!DB::statement($setAutoIncQuery)){
            Log::error('Failed to set companyId auto increment.');
        }
    }
}
