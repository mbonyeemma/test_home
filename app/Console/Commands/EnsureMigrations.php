<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnsureMigrations extends Command
{
    protected $signature = 'migrations:ensure';
    
    protected $description = 'Ensure migrations placeholder';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Migrations ensured.');
    }
}

