<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class setPackageCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkg:set_package_category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates package category - the sample type';

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
        /*$query = "select p.id, pd.small_barcodeid, pd.big_barcodeid, s.test_type from packagedetail pd 
INNER JOIN package p on (p.id = pd.small_barcodeid)
INNER JOIN samples s on (p.id = s.barcodeid)";
        $packages = \DB::select($query);
        foreach ($packages as $package) {
            \DB::statement('UPDATE package set test_type = '.$package->test_type.' WHERE id = '.$package->small_barcodeid.'
                OR id = '.$package->big_barcodeid);
           
        }*/

        //\DB::statement('UPDATE package p INNER JOIN samples s ON (p.id =  s.barcodeid) SET p.test_type = s.test_type');
        
    }
}
