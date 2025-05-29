<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Package as Package;
use \App\Models\PackageMovement as PackageMovement;
use \App\Models\PackageMovementEvent as PackageMovementEvent;
use \App\Models\Facility as Facility;
use DB;

class SampleMonitoringController extends Controller
{
    public function index(Request $request)
    {

        $hubs = array_merge_maintain_keys(array('' => 'Filter by hub'), getAllHubs());

        $searchString = $request->search;
        $hub_requests = $request->hubid;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
      //dd($hub_requests);

        $get_vl_tracker_codes = "SELECT dhis2_uid,code,count(*) as total,created_at,status FROM (
                    SELECT bf.dhis2_uid,vtc.code,vtc.created_at,vtc.status from vl_samples vls
                    INNER JOIN vl_tracking_codes vtc ON vtc.id = vls.tracking_code_id
                    INNER JOIN backend_facilities bf ON bf.id = vls.facility_id
                    ORDER BY vls.id DESC LIMIT 100000) as xy
                    where xy.status = 0
                    group by dhis2_uid, code,created_at,status";

        $codes = DB::connection('mysql2')->select($get_vl_tracker_codes);
        //Read each object and update the package in restrack
       // dd($codes);
        foreach($codes as $code)
        {
            //dd($code);
            if($code->dhis2_uid != '' && $code->code != 'None'){
                //dd($code);
                $package = Package::where('barcode', '=', $code->code)->first(); // find if barcode exists  
                    //dd($package);
                    if($package){
                        $store_package = Package::findOrFail($package['id']);
                        $store_package->status = 3;
                        $store_package->is_merged = 1;
                        $store_package->received_at_destination_on = $code->created_at;
                        $store_package->save();
                            if(!$store_package->first_received_at){
                                $store_package->first_received_at = 2490;
                            }
                        $event = new PackageMovementEvent;
                        $event->package_id = $store_package->id;
                        $event->source = 2490;
                        $event->destination = 2490;
                        $event->status = 3;
                        $event->location = 2490;
                        $event->category_id = $store_package->test_type;
                        $event->place_name = 'CPHL';
                        $event->created_by = 1494;
                        $event->created_at = $code->created_at;
                        $event->save();
                        //update package with latest event_id
                        $store_package->latest_event_id = $event->id;
                        $store_package->save();

                        // update VL status code
                        $update_status = "update vl_tracking_codes vlt set vlt.status = '1' where vlt.code = '".$code->code."'";

                        $status_code = DB::connection('mysql2')->select($update_status);
                    } else{            
                        $facilityid = Facility::where('dhis2_uid', '=', $code->dhis2_uid)->first();
                        //dd($facilityid);
                        if($facilityid){
                            $new_package = new Package;
                            $new_package->barcode = $code->code;
                            $new_package->facilityid = $facilityid->id;
                            $new_package->hubid = $facilityid->parentid;
                            $new_package->test_type = 1;
                            $new_package->delivered_on = $code->created_at;
                            $new_package->created_at = $code->created_at;
                            $new_package->type = 2;
                            $new_package->status = 3;
                            $new_package->is_merged = 1;
                            $new_package->final_destination = 2490;
                            $new_package->numberofsamples = $code->total;
                            $new_package->received_by = 0;
                            $new_package->is_tracked_from_facility = 0;
                            $new_package->save();

                            //now save the
                            $event = new PackageMovementEvent;
                            $event->package_id = $new_package->id;
                            $event->source = 2490;
                            $event->destination = 2490;
                            $event->status = 3;
                            $event->place_name = 'CPHL';
                            $event->location = 2490;
                            $event->category_id = 1;
                            //$event->longitude 
                            //$event->latitude
                            $event->place_name = 'CPHL';
                            $event->created_by = 1498; //sarah hangujja

                            $event->save();
                            //update package with latest event_id
                            $new_package->latest_event_id = $event->id;
                            $new_package->save();

                            $update_status = "update vl_tracking_codes vlt set vlt.status = '1' where vlt.code = '".$code->code."'";

                            $status_code = DB::connection('mysql2')->select($update_status);
                        }
                    }
                }
            }

        $packages = DB::table('package AS p')
                        ->select('p.id','p.is_merged', 'p.barcode','p.facilityid','fc.name as facilityname','fh.name as hub', 'fh.id', 'p.numberofsamples','p.created_at','pme.created_at as Event_date', 'tt.name as testtyps')
                        ->join('facility as fc', 'fc.id', '=', 'p.facilityid')
                        ->join('facility as fh', 'fh.id', '=', 'fc.parentid')
                        ->join('testtypes as tt', 'p.test_type', '=', 'tt.id')
                        ->join('packagemovement_events as pme','pme.id', '=', 'p.latest_event_id')
                        ->where('fc.parentid', '<', 10000)
                        ->where('p.is_merged', '=', 1)
                        ->orderby('p.created_at', 'desc')
                        ->paginate(15);

        if($searchString != ''){
            $packages = DB::table('package AS p')
                        ->select('p.id','p.is_merged', 'p.barcode','p.facilityid','fc.name as facilityname','fh.name as hub', 'fh.id', 'p.numberofsamples','p.created_at','pme.created_at as Event_date', 'tt.name as testtyps')
                        ->join('facility as fc', 'fc.id', '=', 'p.facilityid')
                        ->join('facility as fh', 'fh.id', '=', 'fc.parentid')
                        ->join('testtypes as tt', 'p.test_type', '=', 'tt.id')
                        ->join('packagemovement_events as pme','pme.id', '=', 'p.latest_event_id')
                        ->where('fc.parentid', '<', 10000)
                        ->where('p.is_merged', '=', 1)
                        ->where('p.barcode', '=', $searchString)
                        ->paginate(15);
        }
        if($date_from != '' && $date_to != ''){
            $packages = DB::table('package AS p')
                        ->select('p.id','p.is_merged', 'p.barcode','p.facilityid','fc.name as facilityname','fh.name as hub', 'fh.id', 'p.numberofsamples','p.created_at','pme.created_at as Event_date', 'tt.name as testtyps')
                        ->join('facility as fc', 'fc.id', '=', 'p.facilityid')
                        ->join('facility as fh', 'fh.id', '=', 'fc.parentid')
                        ->join('testtypes as tt', 'p.test_type', '=', 'tt.id')
                        ->join('packagemovement_events as pme','pme.id', '=', 'p.latest_event_id')
                        ->where('fc.parentid', '<', 10000)
                        ->where('p.is_merged', '=', 1)
                        ->whereBetween('p.created_at', [$date_from,  $date_to])
                        ->paginate(15);
        }


        // if($searchString != '')
        // {

        //     $packages_received = Package::where(function($q) use ($searchString){
        //         $q->where('barcode', 'like', '%' . $searchString . '%');
        //     })->paginate(15);

        //     if (count($packages_received) == 0) 
        //         {
        //             Session::flash('message', trans('No Match Found'));
        //         }
        // }else{
            
        //     $packages_received = Package::where('is_merged', '=', 1)->orderby('created_at', 'DESC')->paginate(15);
        // }
        
        return view('newdashboard.monitoring', compact('hubs','packages'));
    }

    public function updateTrackerCode()
    {
        
    }


    public function showSampleInCode($barcode)
    {
        $query = "SELECT vls.id, vls.form_number,vls.barcode,bf.facility,vls.patient_id FROM vl_samples vls
                INNER JOIN vl_tracking_codes vtc ON vtc.id = vls.tracking_code_id
                INNER JOIN backend_facilities bf ON bf.id = vls.facility_id
                LEFT JOIN vl_patients vp ON vp.id = vls.patient_id
                WHERE vtc.code = '$barcode' ";
        $result = DB::connection('mysql2')->select($query);

        return view('newdashboard.barcodesamples', compact('result'));
    }
}
