<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportDataSeeder extends Seeder
{
    public function run()
    {
        $json = File::get(storage_path('app/db_export.json'));
        $data = json_decode($json, true);
        
        // Definir ordem das tabelas para respeitar dependências
        $orderedTables = [
            'users',
            'roles',
            // Adicione outras tabelas principais primeiro
            'role_user',
            // Adicione outras tabelas com chaves estrangeiras depois
        ];
        
        // Desabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Processar tabelas na ordem definida primeiro
        foreach ($orderedTables as $table) {
            if (isset($data[$table])) {
                $this->importTable($table, $data[$table]);
                // Remover a tabela processada do array
                unset($data[$table]);
            }
        }
        
        // Processar tabelas restantes que não foram especificadas na ordem
        foreach ($data as $table => $rows) {
            $this->importTable($table, $rows);
        }
        
        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Import completed successfully!');
    }
    
    private function importTable($table, $rows)
    {
        $this->command->info("Importing table: {$table}");
        
        // Verificar se a tabela existe no MySQL
        if (!Schema::hasTable($table)) {
            $this->command->warn("Table {$table} does not exist in MySQL. Skipping.");
            return;
        }
        
        // Limpar a tabela antes de importar
        try {
            DB::table($table)->truncate();
        } catch (\Exception $e) {
            $this->command->warn("Could not truncate {$table}: " . $e->getMessage());
        }
        
        // Se não há dados, pular
        if (empty($rows)) {
            $this->command->info("No data for table {$table}. Skipping.");
            return;
        }
        
        try {
            // Inserir em lotes pequenos para evitar problemas de memória
            foreach (array_chunk($rows, 50) as $chunk) {
                $formattedChunk = [];
                
                foreach ($chunk as $row) {
                    // Converter de objeto para array se necessário
                    if (is_object($row)) {
                        $row = (array)$row;
                    }
                    
                    $formattedChunk[] = $row;
                }
                
                DB::table($table)->insert($formattedChunk);
            }
            
            $this->command->info("Imported " . count($rows) . " rows into {$table}");
        } catch (\Exception $e) {
            $this->command->error("Error importing {$table}: " . $e->getMessage());
        }
    }
}
