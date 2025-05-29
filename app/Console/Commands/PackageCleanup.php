<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PackageCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkg:set_package_clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deletes all packages without samples and details';

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
    public function handle(){
        //for all records created one day ago, clean up
        \DB::statement("DELETE FROM packagedetail WHERE small_barcodeid in (SELECT id FROM package WHERE numberofsamples = 0 and p.created_at < '2020-05-16 07:00:00')");
        \DB::statement("DELETE FROM samples WHERE barcodeid in (SELECT id FROM package WHERE numberofsamples = 0 and p.created_at < '2020-05-16 07:00:00')");
       
        $packages = \DB::select("SELECT id FROM package WHERE numberofsamples = 0 and p.created_at < '2020-05-16 07:00:00'");
        foreach ($packages as $package) {
            \DB::statement('DELETE FROM package WHERE id = '.$package->id);
           
        }
        
    }
}
