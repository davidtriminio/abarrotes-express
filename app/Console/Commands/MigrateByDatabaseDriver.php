<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateByDatabaseDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-driver {--force : Run the command in production mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations using the current database driver (mysql or pgsql)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $driver = DB::connection()->getDriverName();

        if (! in_array($driver, ['mysql', 'pgsql'], true)) {
            $this->error("Driver de base de datos no soportado: {$driver}");

            return self::FAILURE;
        }

        $this->info("Ejecutando migraciones para {$driver}...");

        $exitCode = Artisan::call('migrate', array_filter([
            '--force' => (bool) $this->option('force'),
        ], static fn ($value) => $value !== false && $value !== null));

        $this->output->write(Artisan::output());

        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }
}
