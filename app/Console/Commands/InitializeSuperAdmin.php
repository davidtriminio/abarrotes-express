<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Helpers\DBDriver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitializeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the SuperAdmin user if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Initializing SuperAdmin user...');

        try {
            // Check if roles table exists
            if (!\Schema::hasTable('roles')) {
                $this->error('Roles table does not exist. Run migrations first.');
                return 1;
            }

            // Check if SuperAdmin user already exists
            $superAdminExists = DB::table('users')
                ->where('id', 1)
                ->exists();

            if ($superAdminExists) {
                $this->info('SuperAdmin user already exists.');
                return 0;
            }

            // Disable foreign key checks
            DBDriver::executeByDriver([
                'mysql' => function($conn) {
                    return $conn->statement('SET FOREIGN_KEY_CHECKS=0;');
                },
                'default' => function($conn) {
                    return null;
                },
            ]);

            // Create SuperAdmin user
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'SuperAdministrador',
                'email' => 'super@ae.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Re-enable foreign key checks
            DBDriver::executeByDriver([
                'mysql' => function($conn) {
                    return $conn->statement('SET FOREIGN_KEY_CHECKS=1;');
                },
                'default' => function($conn) {
                    return null;
                },
            ]);

            $this->info('SuperAdmin user created successfully.');

            // Assign SuperAdmin role
            $superAdmin = User::find(1);
            if ($superAdmin && !$superAdmin->hasRole('SuperAdmin')) {
                $superAdmin->assignRole('SuperAdmin');
                $this->info('SuperAdmin role assigned successfully.');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error initializing SuperAdmin: ' . $e->getMessage());
            return 1;
        }
    }
}

