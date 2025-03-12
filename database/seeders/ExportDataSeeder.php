<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
        $data = [];

        foreach ($tables as $table) {
            if ($table->name != 'migrations' && $table->name != 'sqlite_sequence') {
                $data[$table->name] = DB::table($table->name)->get();
            }
        }

        File::put(storage_path('app/db_export.json'), json_encode($data));
        $this->command->info('Database exported to storage/app/db_export.json');
    }
}
