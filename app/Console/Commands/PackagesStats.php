<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PackagesStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkg:set_package_stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates package stats - sets no.packages and no. samples';

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
        //create samples for each small package that does not have any
       /* \DB::statement("INSERT INTO `samples` (barcodeid, samplename, hubid, facilityid, test_type, thedate, created_at, createdby,numberofsamples) 
        select pd.small_barcodeid, p.test_type as sampplename, p.hubid, p.facilityid, p.test_type, pd.created_at as thedate, pd.created_at, pd.created_by, 1 as numberofsamples from packagedetail pd
        INNER JOIN package p ON p.id = pd.small_barcodeid
        where small_barcodeid in (select id from package where numberofsamples = 0 and type = 1)");*/

        //update each package with the number of samples on samples table - small packages
       // \DB::statement("UPDATE package p INNER JOIN samples s SET p.numberofsamples = s.numberofsamples WHERE p.id = s.barcodeid");

        //for each big package, put number of samples and packages
        /*$query = "SELECT big_barcodeid, count(small_barcodeid) as numberofpackages, sum(t.numberofsamples) as total FROM 
        (select pd.big_barcodeid, small_barcodeid, s.numberofsamples from packagedetail pd 
        inner join samples s on pd.small_barcodeid = s.barcodeid
        GROUP BY pd.small_barcodeid,pd.big_barcodeid,s.numberofsamples) as t
        GROUP BY big_barcodeid";
        $packages = \DB::select($query);
        foreach ($packages as $package) {
            \DB::statement('UPDATE package set numberofpackages = '.$package->numberofpackages.',numberofsamples = '.$package->total.' WHERE id = '.$package->big_barcodeid);
           
        }*/
        
    }
}
