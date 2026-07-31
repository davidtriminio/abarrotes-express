<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedDatabaseOnce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run seeders only on first execution, then migrate on subsequent runs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            // Ensure the seeder_executions table exists
            if (!DB::connection()->getSchemaBuilder()->hasTable('seeder_executions')) {
                $this->info('Tabla seeder_executions no encontrada. Ejecutando migraciones primero...');
                $this->call('migrate', ['--force' => true]);
            }

            // Check if seeders have been executed before
            $hasExecutedSeeders = DB::table('seeder_executions')->exists();

            if (!$hasExecutedSeeders) {
                $this->info('Primera ejecución detectada. Ejecutando migraciones y seeders...');
                
                // Run migrations
                $this->call('migrate', ['--force' => true]);
                
                // Run seeders
                $this->call('db:seed', ['--force' => true]);
                
                // Mark that seeders have been executed
                DB::table('seeder_executions')->insert([
                    'seeder_class' => 'DatabaseSeeder',
                    'executed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info('✓ Seeders ejecutados correctamente.');
            } else {
                $this->info('Seeders ya fueron ejecutados. Solo ejecutando migraciones pendientes...');
                
                // Run only migrations
                $this->call('migrate', ['--force' => true]);
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
