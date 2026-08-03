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
    protected $description = 'Run seeders only on first execution, then just run migrations on subsequent runs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            // First, ensure migrations table exists and run migrations
            $this->info('Ejecutando migraciones...');
            $this->call('migrate', ['--force' => true]);

            // Check if seeder_executions table exists
            if (!DB::connection()->getSchemaBuilder()->hasTable('seeder_executions')) {
                $this->error('Tabla seeder_executions no encontrada después de migraciones.');
                return self::FAILURE;
            }

            // Check if seeders have been successfully executed before
            $hasExecutedSeeders = DB::table('seeder_executions')
                ->where('seeder_class', 'DatabaseSeeder')
                ->exists();

            if (!$hasExecutedSeeders) {
                $this->info('Primera ejecución de seeders detectada.');

                // Mark seeders as being executed (to prevent re-runs on failure)
                DB::table('seeder_executions')->insertOrIgnore([
                    'seeder_class' => 'DatabaseSeeder',
                    'executed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Run seeders
                $this->info('Ejecutando seeders...');
                $this->call('db:seed', ['--force' => true]);

                $this->info('✓ Seeders ejecutados correctamente.');
            } else {
                $this->info('Seeders ya fueron ejecutados. Omitiendo...');
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
