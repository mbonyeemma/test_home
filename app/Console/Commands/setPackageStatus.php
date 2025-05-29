<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class setPackageStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkg:set_package_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the status of packages based on a specified date range';

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
      /*  //update miss assigned status values
        \DB::statement('update packagemovement_events set `status` =3 where status = 7');
        \DB::statement('update packagemovement_events set `status` =2 where status = 6');
        \DB::statement('update packagemovement_events set `status` =1 where status = 5');
        \DB::statement("update package p
LEFT OUTER JOIN packagedetail pd
  ON (pd.small_barcodeid = p.id)
  INNER JOIN packagemovement_events pme ON(p.id = pme.package_id)
  SET p.latest_event_id = pme.id
  WHERE pd.small_barcodeid IS NULL AND p.created_at between (CURDATE() - INTERVAL 5 MONTH ) and CURDATE()
  
  ");
        //get all big packages
        $query = "select id, created_at from package where type = 2 AND created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()";
        $packages = \DB::select($query);
        foreach ($packages as $package) {
            //get the latest event for this package
            $les = \DB::select("SELECT MAX(id) as le_id FROM packagemovement_events WHERE package_id = ".$package->id);
            // now get all packages on this package, if any, and add the latest_event_id
            if($les[0]->le_id){
                \DB::statement('UPDATE package set latest_event_id = '.$les[0]->le_id.' WHERE id = '.$package->id);
            
                $small_packages = \DB::select("SELECT small_barcodeid FROM packagedetail WHERE big_barcodeid =".$package->id);
                foreach ($small_packages as $small_package) {
                    \DB::statement('UPDATE package set latest_event_id = '.$les[0]->le_id.' WHERE id = '.$small_package->small_barcodeid);
                    //also update the samples in the small packages
                    \DB::statement('UPDATE samples set latest_event_id = '.$les[0]->le_id.' WHERE id = '.$small_package->small_barcodeid);
                }
            }
            
        }*/
    }
}
