<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:ensure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure all necessary database columns exist for the application to work properly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Ensuring database structure...');

        try {
            // Check and add missing columns to package table
            $this->ensurePackageTable();
            
            // Check and add missing columns to packagemovement_events table
            $this->ensurePackageMovementEventsTable();
            
            // Check and add missing columns to facility table
            $this->ensureFacilityTable();
            
            // Check and add missing columns to testtypes table
            $this->ensureTestTypesTable();

            $this->info('Database structure ensured successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error ensuring database structure: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function ensurePackageTable()
    {
        $this->info('Checking package table...');
        
        $columns = [
            'created_by' => 'unsignedBigInteger',
            'status' => 'integer',
            'latest_event_id' => 'unsignedBigInteger',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp'
        ];

        foreach ($columns as $column => $type) {
            if (!Schema::hasColumn('package', $column)) {
                $this->info("Adding column '{$column}' to package table...");
                Schema::table('package', function ($table) use ($column, $type) {
                    if ($type === 'unsignedBigInteger') {
                        $table->unsignedBigInteger($column)->nullable();
                    } elseif ($type === 'integer') {
                        $table->integer($column)->default(0);
                    } elseif ($type === 'timestamp') {
                        $table->timestamp($column)->nullable();
                    }
                });
            }
        }
    }

    private function ensurePackageMovementEventsTable()
    {
        $this->info('Checking packagemovement_events table...');
        
        $columns = [
            'created_by' => 'unsignedBigInteger',
            'status' => 'integer',
            'location' => 'unsignedBigInteger',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp'
        ];

        foreach ($columns as $column => $type) {
            if (!Schema::hasColumn('packagemovement_events', $column)) {
                $this->info("Adding column '{$column}' to packagemovement_events table...");
                Schema::table('packagemovement_events', function ($table) use ($column, $type) {
                    if ($type === 'unsignedBigInteger') {
                        $table->unsignedBigInteger($column)->nullable();
                    } elseif ($type === 'integer') {
                        $table->integer($column)->default(0);
                    } elseif ($type === 'timestamp') {
                        $table->timestamp($column)->nullable();
                    }
                });
            }
        }
    }

    private function ensureFacilityTable()
    {
        $this->info('Checking facility table...');
        
        if (!Schema::hasColumn('facility', 'name')) {
            $this->info("Adding column 'name' to facility table...");
            Schema::table('facility', function ($table) {
                $table->string('name')->nullable();
            });
        }
    }

    private function ensureTestTypesTable()
    {
        $this->info('Checking testtypes table...');
        
        if (!Schema::hasColumn('testtypes', 'name')) {
            $this->info("Adding column 'name' to testtypes table...");
            Schema::table('testtypes', function ($table) {
                $table->string('name')->nullable();
            });
        }
    }
}
