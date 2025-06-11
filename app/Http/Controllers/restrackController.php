<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\User;
use Hash;
use \App\Models\LookupType as LookupType;
use \App\Models\Facility as Facility;
use \App\Models\Hub as Hub;
use \App\Models\Sample as Sample;
use \App\Models\Result as Result;
use \App\Models\Package as Package;
use \App\Models\TestType as TestType;
use \App\Models\PackageMovementEvent as PackageMovementEvent;
use \App\Models\PackageDetail as PackageDetail;
use \App\Models\PackageReceipt as PackageReceipt;
use \App\Models\UntrackedPackage as UntrackedPackage;
use \App\Models\Checklogin as Checklogin;
use \App\Models\NftActivities as NftActivities; // NFT 
use DB;

//Importing laravel-permission models
use App\Models\Role;
use App\Models\Permission;
use Validator;

//Enables us to output flash messaging
use Session;

class restrackController extends Controller
{

    public function __construct()
    {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    public function login(Request $request)
    {
        $username = $request['username'];
        $password = base64_decode($request['password']);
        //$password = $request['password'];
        $facilityid = $request['faicilityid'];
        if (Auth::attempt(array('username' => $username, 'password' => $password))) {
            // $user = User::where('id', '=', Auth::user()->id)->get();
            $user = User::where('id', '=', Auth::user()->id)->with('roles.permissions')->get();
            $user_arr = $user->toArray();
            //user array_values to specify array key (user) otherwise it will be set to 0 
            $re_arranged_user_arr = ['user' => array_values($user_arr)];
            //add the statu and description keys
            $re_arranged_user_arr['status'] = 200;
            $re_arranged_user_arr['status_desc'] = 'Successfully logged in';
            return response()->json($re_arranged_user_arr);
        } else {
            return response()->json(['status' => 501, 'status_desc' => 'Login failed, check your password and username']);
        }
    }

    public function packagesPerHub(Request $request)
    {

        try {
            $tracked_packages_per_hub = "SELECT 
            tt.name AS 'Sample Type',
            fh.name AS 'Hub Name',
            COUNT(pkm.barcode) AS 'Total Packages',
            SUM(pkm.numberofsamples) AS 'Total Samples',
            GROUP_CONCAT(DISTINCT fa.name SEPARATOR ', ') AS 'Facilities',
            COUNT(DISTINCT pkm.facilityid) AS 'Facilities Count',
            MAX(pkm.created_at) AS 'Recent_creation_date',
            MIN(pkm.created_at) AS 'Earliest_creation_date'
            FROM package pkm
            LEFT JOIN staff urs ON urs.id = pkm.created_by
            LEFT JOIN facility fa ON fa.id = pkm.facilityid
            LEFT JOIN facility fh ON fh.id = pkm.hubid
            LEFT JOIN testtypes tt ON tt.id = pkm.test_type 
            WHERE pkm.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 20 WEEK) AND NOW() AND tt.name IS NOT NULL 
            GROUP BY tt.name, fh.name, pkm.test_type, pkm.hubid
            ORDER BY tt.name, fh.name";

            $data = DB::select($tracked_packages_per_hub);
            $re_arranged_user_arr = ['Packages' => array_values($data)];
            $re_arranged_user_arr['status'] = 200;
            $re_arranged_user_arr['status_desc'] = 'Successfully Fetched Data';
            return response()->json($re_arranged_user_arr);
        } catch (\Exception $e) {
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function getLookUps($lookup_type)
    {
        $ret_arr = array();
        if ($lookup_type == 'test_types') {
            $ret_arr = ['test_types' => array_values(getTestTypes(1))];
        } elseif ($lookup_type == 'sample_types') {
            $ret_arr = getLookUps(30);
        }
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($ret_arr);
    }

    public function getLookUps_new($lookup_type)
    {
        $ret_arr = array();
        if ($lookup_type == 'test_types') {
            $ret_arr = ['test_types' => array_values(getTestTypes(1))];
        } elseif ($lookup_type == 'sample_types') {
            $ret_arr = getLookUps(30);
        }
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($ret_arr);
    }

    public function getPlace($place_type)
    {
        $ret_arr = array();
        if ($place_type == 'hubs') {
            $ret_arr = getAllHubs();
        } elseif ($place_type == 'poe_sites') {
            $ret_arr = getPoeSites();
        } elseif ($place_type == 'ref_labs') {
            $ret_arr = getReferenceLabs();
        } elseif ($place_type == 'ref_labs_and_hubs') {
            $ret_arr = getReferenceLabs_and_hubs();
        } elseif ($place_type == 'faciliities') {
            $ret_temp = getAllFacilities();
            $ret_arr_rerarrange = generateArrayForEach($ret_temp);
            $ret_arr  = ['facilities' => array_values($ret_arr_rerarrange)];
        } else {
            //do nothing
        }
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($ret_arr);
    }

    public function getFacilitiesWithTheirHupbs()
    {
        $ret_temp = getAllFacilitiesWitTheirhHups();
        $ret_arr_rerarrange = generateMultiKeyValue($ret_temp);

        $ret_arr  = ['facilities' => array_values($ret_arr_rerarrange)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($ret_arr);
    }

    public function facilitiesForHup($hub_id)
    {
        $ret_arr = array();
        $ret_arr = getFacilitiesforHubs(base64_decode($hub_id));
        //generate each facility as object
        $facility_objs = generateArrayForEach($ret_arr);
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($facility_objs);
    }

    public function getSamples()
    {
        $ret_arr = array();
        $ret_arr = \DB::select("SELECT id, barcode FROM samples WHERE created_at between (CURDATE() - INTERVAL 2 MONTH ) and (CURDATE() + 1 )");
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'data Successfully fetched';
        return response()->json($ret_arr);
    }

    public function createPackage(Request $request)
    {
        $ret = $messages = array();
        $post_data = $request;

        try {
            \DB::transaction(function () use ($request, $post_data) {
                /*$this->validate($request, [
                    'package' => 'required|exists:barcode'
                ]);*/
                $hub_id = getHubforFacility($post_data['facilityid']);
                $package = new Package;
                $package_arr = [
                    'barcode' => $post_data['barcode'],
                    'facilityid' => $post_data['facilityid'],
                    'hubid' => $hub_id,
                    //$post_data['hubid'],
                    'test_type' => $post_data['test_type'],
                    'sample_type' => $post_data['sample_type'],
                    'date_picked' => $post_data['date_picked'],
                    'created_by' => $post_data['user_id'],
                ];
                /* initial status should depend on type of user - poe, 0 (waiting pickup) otherwise 1 (in transit)
                */
                if (isPoeOrEocUser($post_data['user_id'])) {
                    $event_status = 0;
                } else {
                    $event_status = 1;
                }

                //Set final_destination based no whether the test_type's lab is known or not. final_destination' => $post_data['final_destination'],

                if (isset($post_data['final_destination']) && $post_data['final_destination'] != '') {
                    // \Log::info($post_data);
                    $package_arr['final_destination'] = $post_data['final_destination'];
                } else {
                    //get final destination based on sample_type
                    $t_type = TestType::findOrFail($post_data['test_type']);
                    $package_arr['final_destination'] = $t_type->ref_lab;
                    //set the final_destiation in the post data
                    $post_data['final_destination'] =  $t_type->ref_lab;
                }

                if (isset($post_data['children']) && $post_data['children'] != '') {
                    $package->type = 2;
                    $package_arr['type'] = 2;
                    //This is a repacking of existing packages
                    $event_status = 5;
                } else {
                    $package_arr['type'] = 1;
                }
                if (isset($post_data['samples']) && $post_data['samples'] != '') {
                    $package_arr['numberofsamples'] = count($post_data['samples']);
                    $package_arr['is_batch'] = 0;
                } else {
                    $package_arr['numberofsamples'] = $post_data['number_of_samples'];
                    $package_arr['is_batch'] = 1;
                }
                // needed especially where package was not
                if (isset($post_data['status']) && $post_data['status'] != '') {
                    $event_status = $post_data['status'];
                }
                $package_arr['is_tracked_from_facility'] = 1;

                //$package->save(); 
                //if(!Package::where('barcode','=',$post_data['barcode'])->first()){
                //validate samples
                $validator = Validator::make(
                    $package_arr,
                    [
                        'barcode' => 'required|unique:package'
                    ],
                    [
                        'barcode.required' => 'The barcode is required ',
                        'barcode.unique' => 'The barcode is already used ',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    $return_msg = array();
                    foreach ($messages->all() as $message) {
                        array_push($return_msg, $message);
                    }
                    // dd($return_msg);
                    $error_messages[] = $return_msg;
                } else {
                    //dd('created object');
                    $package = Package::create($package_arr);

                    //now save the event
                    $event = $this->createEvent($post_data, $package->id, $event_status);
                    $package->latest_event_id = $event->id;
                    if ($package->final_destination == 0) {
                        $package->final_destination = 2490;
                    }

                    $package->save();
                    //update the children with the status of their parent
                    if (isset($post_data['children']) && $post_data['children'] != '') {
                        $this->setParentForChildrenPackages($post_data['children'], $package->id, $event->id);
                    }
                    //in case ther are any samples, save them
                    if (isset($post_data['samples']) && $post_data['samples'] != '') {
                        //save each sample
                        $this->createSamples($post_data, $package->id, $event->id, $hub_id);
                    }
                }

                // }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'The package has been successfully captured';
            return response()->json($ret);
        } catch (\Exception $e) {
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    private static function createEvent($post_data, $package_id, $status = 0)
    {
        $event = new PackageMovementEvent;
        $event->package_id = $package_id;
        $event->source = $post_data['facilityid'];
        $event->destination =  $post_data['final_destination'];
        $event->longitude =  $post_data['longitude'];
        $event->latitude =  $post_data['latitude'];
        $event->place_name =  $post_data['place_name'];
        $event->status = $status;
        $event->category_id = $post_data['test_type'];
        $event->location = $post_data['facilityid'];
        $event->created_by = $post_data['user_id'];
        // \Log::info($event);
        $event->save();
        return $event;
    }

    public function updadePackageStatus(Request $request)
    {

        $ret_arr = array();
        try {

            \DB::transaction(function () use ($request, $ret_arr) {
                //\Log::info($request);

                foreach ($request['barcodes'] as $barcode) {

                    $package = Package::where('barcode', '=', $barcode)->first();

                    $request['source'] = $package->facilityid;
                    if (isset($request['destination']) && $request['destination'] != '') {
                        $request['final_destination'] = $request['destination'];
                    } else {
                        $request['final_destination'] = $package->final_destination;
                    }

                    $request['test_type'] = $package->test_type;
                    $event = $this->createEvent($request, $package->id, $request['status']);
                    // dd($event);
                    //if the status is 2 (delivered) and the event location is the package's final destination, set the deliverer of the package
                    $update_str = '';
                    if ($request['status']  == 2 && $request['facilityid'] == $package->final_destination) {
                        $update_str .= " , delivered_on = '" . $event->created_at . "', delivered_by = " . $request['user_id'];
                    }
                    if ($request['status']  == 3 && $request['facilityid'] == $package->final_destination) {
                        $update_str .= " , received_at_destination_on = '" . $event->created_at . "', received_by = " . $request['user_id'];
                    }
                    // Refer package
                    if ($request['status']  == 4) {
                        $update_str .= " , delivered_by = 0, delivered_on = NULL, received_at_destination_on = NULL, 
                            received_by = 0,final_destination = " . $request['destination'];
                        //get user's ref_lab and change package's final destination
                        //if user type is 12
                    }
                    //get all children of the packag and update and parent package
                    if ($package->type == 2) {
                        $query = "UPDATE package SET latest_event_id = " . $event->id . $update_str . " WHERE parent_id = " . $package->id . " OR id = " . $package->id;
                    } else {
                        $package_update_query = "UPDATE package SET latest_event_id = " . $event->id . $update_str . " WHERE id = " . $package->id;
                        \DB::unprepared($package_update_query);
                        $query = "UPDATE samples SET latest_event_id = " . $event->id . " WHERE package_id = " . $package->id;
                    }
                    //\Log::info($query);
                    \DB::unprepared($query);
                }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'Package status updated successfully';
            return response()->json($ret);
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function updadePackageStatus_new(Request $request)
    {
        $ret_arr = array();
        try {
            \DB::transaction(function () use ($request, $ret_arr) {
                //\Log::info($request);
                foreach ($request['barcodes'] as $barcode) {
                    $package = Package::where('id', '=', $barcode)->first();
                    $request['source'] = $package->facilityid;
                    if (isset($request['destination']) && ($request['destination'] != '' || $request['destination'] == '000')) {
                        $request['final_destination'] = $request['destination'];
                    } else {
                        $request['final_destination'] = $package->final_destination;
                    }

                    if (isset($request['final_destination']) && $request['final_destination'] != '') {
                        $package->final_destination = $request['final_destination'];
                        $package->save();
                    }

                    $request['test_type'] = $package->test_type;
                    $event = $this->createEvent($request, $package->id, $request['status']);

                    //if the status is 2 (delivered) and the event location is the package's final destination, set the deliverer of the package
                    $update_str = '';
                    if ($request['status']  == 2 && $request['facilityid'] == $package->final_destination) {
                        $update_str .= " , delivered_on = '" . $event->created_at . "', delivered_by = " . $request['user_id'];
                    }
                    if ($request['status']  == 3 && $request['facilityid'] == $package->final_destination) {
                        $update_str .= " , received_at_destination_on = '" . $event->created_at . "', received_by = " . $request['user_id'];
                    }
                    // Refer package
                    if ($request['status']  == 4) {
                        $update_str .= " , delivered_by = 0, delivered_on = NULL, received_at_destination_on = NULL, 
                            received_by = 0,final_destination = " . $request['destination'];
                        //get user's ref_lab and change package's final destination
                        //if user type is 12
                    }
                    //get all children of the packag and update and parent package
                    if ($package->type == 2) {
                        $query = "UPDATE package SET latest_event_id = " . $event->id . $update_str . " WHERE parent_id = " . $package->id . " OR id = " . $package->id;
                    } else {
                        $package_update_query = "UPDATE package SET latest_event_id = " . $event->id . $update_str . " WHERE id = " . $package->id;
                        \DB::unprepared($package_update_query);
                        $query = "UPDATE samples SET latest_event_id = " . $event->id . " WHERE package_id = " . $package->id;
                    }
                    //\Log::info($query);
                    \DB::unprepared($query);
                }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'Package status updated successfully';
            return response()->json($ret);
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    private function createSamples($post_data, $package_id, $event_id, $hub_id)
    {
        foreach ($post_data['samples'] as $sample) {
            $sample_obj = new Sample;
            $sample_obj->barcode = $sample['barcode'];
            $sample_obj->facilityid = $post_data['facilityid'];
            $sample_obj->hubid = $hub_id;
            $sample_obj->package_id = $package_id;
            $sample_obj->test_type = $sample['test_type'];
            $sample_obj->sample_type = $sample['sample_type'];
            $sample_obj->suspected_disease = $sample['suspected_disease'];
            $sample_obj->surveillance_code = $sample['surveillance_code'];
            $sample_obj->date_picked = $post_data['date_picked'];
            $sample_obj->numberofsamples = 1;
            $sample_obj->latest_event_id = $event_id;
            $sample_obj->save();
        }
        return 1;
    }

    private function setParentForChildrenPackages($children_package_barcodes, $package_id, $event_id)
    {
        foreach ($children_package_barcodes as $barcode) {
            $child_package = Package::where('barcode', '=', $barcode)->firstOrFail();
            $child_package->parent_id = $package_id;
            $child_package->latest_event_id = $event_id;
            $child_package->save();
        }
    }


    public function getPackagesBeyondDate($provided_date)
    {
        // dd("asdasda");
        // SELECT id, barcode FROM package WHERE delivered_on IS NULL AND created_at > '2018-05-29 13:02:44'
        $query = "SELECT pk.id, pk.barcode, pk.numberofsamples, pk.created_at, 
        pk.facilityid, fl.name, tt.name AS 'Test_Type' FROM package pk LEFT JOIN facility fl ON fl.id = pk.facilityid 
        LEFT JOIN testtypes tt ON tt.id = pk.test_type WHERE pk.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE())";
        // -- LEFT JOIN testtypes tt ON tt.id = pk.test_type WHERE pk.delivered_on IS NULL AND pk.created_at between (CURDATE() - INTERVAL 2 MONTH ) and (CURDATE() + 1 )";
        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function getPackagesPerDate_new($provided_date)
    {


        // SELECT id, barcode FROM package WHERE delivered_on IS NULL AND created_at > '2018-05-29 13:02:44'
        $query = "SELECT 
        pk.id, pk.barcode, pk.created_at, pk.facilityid, fa.name, pk.numberofsamples,
        IF(pk.status = 1, 'INTRANSIT', 
        IF(pk.status = 2, 'DELIVERED', 
        IF(pk.status = 3,'RECEIVED', 
        IF(pk.status = 4, 'INTRANSIT', 'INTRANSIT')))) as STATUS 
        FROM package pk LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )";
        // FROM package pk LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) = '" . $provided_date . "'";
        // (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )
        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function getPackagesPerDate_new_id($provided_date)
    {
        // dd("sdfsdfd");
        // SELECT id, barcode FROM package WHERE delivered_on IS NULL AND created_at > '2018-05-29 13:02:44'
        $query = "SELECT 
        pk.id, pk.barcode, pk.created_at, pk.facilityid, fa.name, pk.numberofsamples,
        IF(pk.status = 1, 'INTRANSIT', 
        IF(pk.status = 2, 'DELIVERED', 
        IF(pk.status = 3,'RECEIVED', 
        IF(pk.status = 4, 'INTRANSIT', 'INTRANSIT')))) as STATUS 
        FROM package pk 
        LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )";
        // LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) = '" . $provided_date . "'";
        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function getPackagesPerDate($provided_date)
    {

        // SELECT id, barcode FROM package WHERE delivered_on IS NULL AND created_at > '2018-05-29 13:02:44'
        // $query = "SELECT id, barcode, created_at FROM package WHERE delivered_on IS NULL AND DATE(created_at) = '" . $provided_date . "'";
        $query = "SELECT 
        pk.id, pk.barcode, pk.created_at, pk.facilityid, fa.name, pk.numberofsamples,
        IF(pk.status = 1, 'INTRANSIT', 
        IF(pk.status = 2, 'DELIVERED', 
        IF(pk.status = 3,'RECEIVED', 
        IF(pk.status = 4, 'INTRANSIT', 'INTRANSIT')))) as STATUS 
        FROM package pk 
        LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) between (CURDATE() - INTERVAL 2 WEEK ) and (CURDATE() + 1 )";
        // -- LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND DATE(pk.created_at) = '" . $provided_date . "'";

        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function getPackagesPerDate_byId($id)
    {
        // SELECT id, barcode FROM package WHERE delivered_on IS NULL AND created_at > '2018-05-29 13:02:44'
        // $query = "SELECT id, barcode, created_at FROM package WHERE delivered_on IS NULL AND DATE(created_at) = '" . $provided_date . "'";
        $query = "SELECT 
        pk.id, pk.barcode, pk.created_at, pk.facilityid, fa.name, pk.numberofsamples,
        IF(pk.status = 1, 'PICKED', 
        IF(pk.status = 2, 'DELIVERED', 
        IF(pk.status = 3,'RECEIVED', 
        IF(pk.status = 4, 'INTRANSIT', 'INTRANSIT')))) as STATUS 
        FROM package pk 
        LEFT JOIN facility fa ON pk.facilityid = fa.id WHERE pk.delivered_on IS NULL AND pk.id > '" . $id . "'";

        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function getPackagesDetailsByBarcode($searchString)
    {
        $packages = Package::with('facility', 'facility.hub')
            ->orderBy('package.created_at', 'DESC');

        $packages = $packages->orWhere(function ($q) use ($searchString) {
            $q->where('package.barcode', 'like', '%' . $searchString . '%');
        })->leftjoin('facility as fl', 'fl.id', '=', 'package.hubid')
            ->leftjoin('testtypes as tt', 'tt.id', '=', 'package.test_type')->first();
        // dd($packages);
        //         $query = "SELECT pk.id, pk.barcode, pk.facilityid, pk.hubid, pk.test_type, pk.`status`, 
        // pk.numberofsamples, pk.is_batch, fl.name AS 'Facility Name', pk.created_at, hb.name AS 'Hub Name' FROM package pk 
        // LEFT JOIN facility fl ON pk.facilityid = fl.id
        // LEFT JOIN facility hb ON hb.id = pk.hubid
        // LEFT JOIN testtypes tt ON tt.id = pk.test_type 
        // WHERE pk.barcode LIKE '%CPHL201%' ORDER BY pk.created_at DESC
        //      SELECT id, barcode, created_at FROM package WHERE delivered_on IS NULL AND DATE(created_at) = '" . $provided_date . "'";
        // $db_data = \DB::select($query);
        $ret_arr = ['package' => $packages];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    /*List based functionality*/
    public function getPackages($categrory, $cat_id = 0)
    {

        //api/restrack/get/packages/by/{cat}/id/{}
        //api/restrack/get/packages/for/all
        //api/restrack/get/packages/for/user/id/20
        //api/restrack/get/packages/for/hub/id/3
        //api/restrack/get/packages/for/facility/id/8
        //api/restrack/get/packages/for/all/id/
        // api/restrack/get/packages/for/single/id/UNHLS2020-8014
        $cond = '';
        if ($categrory == 'facility') {
            $cond .= ' AND p.facility = ' . $cat_id;
        } elseif ($categrory == 'hub') {
            $cond .= ' AND p.hubid = ' . $cat_id;
        } elseif ($categrory == 'user') {
            /*$cond .= ' AND p.final_destination <> pme.location AND pme.created_by = '.$cat_id." AND pme.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )";*/
        }
        if ($categrory == 'single') {
            $query = "SELECT id, barcode FROM package WHERE barcode = '" . $cat_id . "'";
        } elseif ($categrory == 'all') {
            $query = "SELECT id, barcode FROM package WHERE created_at between (CURDATE() - INTERVAL 2 MONTH ) and (CURDATE() + INTERVAL 1 DAY)";
        } elseif ($categrory == 'all_undelivered') {
            $query = "SELECT id, barcode, created_at FROM package WHERE delivered_on IS NULL AND created_at between (CURDATE() - INTERVAL 2 MONTH ) and (CURDATE() + 1 )";
        } elseif ($categrory == 'user') {
            //change

            // $query = "SELECT p.barcode,sf.name as source_facility, fd.name as final_destination, ef.name as last_location, p.latest_event_id from packagemovement_events pme
            // INNER JOIN package p ON p.latest_event_id = pme.id
            // INNER JOIN facility ef ON(pme.location = ef.id)
            // INNER JOIN facility sf ON(p.facilityid = sf.id)
            // INNER JOIN facility fd ON(p.final_destination = fd.id)
            // WHERE pme.status < 2 AND pme.created_by = " . $cat_id . " AND  pme.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + INTERVAL 1 DAY)";
            $query = "SELECT p.id, p.barcode,sf.name as source_facility, fd.name as final_destination, ef.name as last_location, p.latest_event_id, tt.name as test_name, p.numberofsamples as numberofsamples from packagemovement_events pme
                INNER JOIN package p ON p.latest_event_id = pme.id
                INNER JOIN facility ef ON(pme.location = ef.id)
                INNER JOIN facility sf ON(p.facilityid = sf.id)
                LEFT JOIN facility fd ON(p.final_destination = fd.id)
                LEFT JOIN testtypes tt ON (p.test_type = tt.id)
                WHERE pme.status < 2 AND pme.created_by = " . $cat_id . " AND  pme.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + INTERVAL 1 DAY)";
        } else {
            $query = "SELECT p.barcode,sf.name as source_facility,fd.name as final_destination, ef.name as last_location FROM `package` p 
            INNER JOIN packagemovement_events pme ON (p.latest_event_id = pme.id)
            INNER JOIN facility ef ON(pme.location = ef.id)
            INNER JOIN facility sf ON(p.facilityid = sf.id)
            INNER JOIN facility fd ON(p.final_destination = fd.id)
            WHERE p.id > 0 " . $cond;
        }
        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }

    public function deliverResults(Request $request)
    {
        /*{"facilityid":20,"delivered_at":"2020-11-15 10:03:03","user_id":"30","results":["pt002","res154","ret587"]}
        */
        //\Log::info($request);
        $hub_id = getHubforFacility($request['facilityid']);
        $results_ids = $request['results'];
        for ($i = 0; $i < count($results_ids); $i++) {
            $result = new Result;
            $result->hubid = $hub_id;
            $result->facilityid = $request['facilityid'];
            $result->locator_id = $results_ids[$i];
            $result->delivered_at = $request['delivered_at'];
            $result->created_by = $request['user_id'];
            $result->save();
        }

        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Results delivered successfully';
        return response()->json($ret_arr);
    }

    public function changePassword(Request $request)
    {
        try {
            //compare the old password with existing password 
            if (Hash::check(base64_decode($request['old_password']), $user->password)) {
                // Right password
                $user->setPasswordAttribute(base64_decode($request['new_password']));
                $user->save();
                $ret_arr['status'] = 200;
                $ret_arr['status_desc'] = 'Password changed successfully';
                return response()->json($ret_arr);
            }
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function addMoreSamplesToPackage(Request $request)
    {
        try {
            $package = Package::where('barcode', '=', $request['barcode'])->first();
            foreach ($request['samples'] as $sample) {
                $sample_obj = new Sample;
                $sample_obj->barcode = $request['barcode'];
                $sample_obj->facilityid = $package->facilityid;
                $sample_obj->hubid = $package->hubid;
                $sample_obj->package_id = $package->id;
                $sample_obj->test_type = $sample['test_type'];
                $sample_obj->sample_type = $sample['sample_type'];
                $sample_obj->suspected_disease = $sample['suspected_disease'];
                $sample_obj->surveillance_code = $sample['surveillance_code'];
                $sample_obj->date_picked = $request['date_picked'];
                $sample_obj->numberofsamples = 1;
                $sample_obj->latest_event_id = $package->latest_event_id;
                $sample_obj->save();
            }
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
        $ret['status'] = 200;
        $ret['status_desc'] = 'Samples changed successfully';
        return response()->json($ret);
    }

    public function storeLocationLogin(Request $request)
    {
        try {
            $data_stored = $request;
            $slogin = new Checklogin();
            $slogin_array = [
                'facilityid' => $data_stored['facilityid'],
                'thedate' => $data_stored['thedate'],
                'hubid' => $data_stored['hubid'],
                'staffid' => $data_stored['userid'],
                'latitude' => $data_stored['latitude'],
                'longitude' => $data_stored['longitude'],
                'lastupdatedby' => $data_stored['lastupdatedby'],
                'staffid' => $data_stored['staffid'],
            ];

            $slogin = Checklogin::create($slogin_array);
            $ret['status'] = 200;
            $ret['status_OK'] = 'data Successfully saved';
            \Log::info("Saved Login Info... ");
        } catch (\Exception $e) {
            \Log::info("Failed...Saved Login Info... " + $e);
            // \Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
        return response()->json($ret);
    }

    public function getTotalNumberofFacilitiesAndTesttypes()
    {
        $fac_arr = array();
        $facility = Facility::get();
        $fac_arr = count($facility);

        $test_arr = array();
        $test_types = TestType::get();
        $test_arr = count($test_types);

        $fac_test  = ['facilities' => $fac_arr, 'test_types' => $test_arr];

        return response()->json($fac_test);
    }

    // NFT store functionality
    public function storeNftvariables(Request $request)
    {
        try {
            $store_data = $request;
            $nftdata = new NftActivities();

            $data_array = [
                'unique_id' => $store_data['unique_id'],
                'activity_start_date' => $store_data['activity_start_date'],
                'from_location_name' => $store_data['from_location_name'],
                'from_location_id' => $store_data['from_location_id'],
                'to_location_name' => $store_data['to_location_name'],
                'to_location_id' => $store_data['to_location_id'],
                'sample_description' => $store_data['sample_description'],
                'status' => $store_data['status'],
                'riders_name' => $store_data['riders_name'],
                'delivered_on' => $store_data['delivered_on'],
                'entered_by' => $store_data['entered_by'],
            ];

            $nftdata = NftActivities::create($data_array);
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
        $ret['status'] = 200;
        $ret['status_OK'] = 'data Successfully saved';

        return response()->json($ret);
    }

    public function updateNftvariable(Request $request)
    {
        $ret_arr = array();
        try {
            \DB::transaction(function () use ($request, $ret_arr) {
                //\Log::info($request);

                $nft_update = NftActivities::where('unique_id', '=', $request['unique_id'])->first();

                if ($nft_update) {
                    $nft = NftActivities::findOrFail($nft_update['id']);
                    $nft->unique_id = $request['unique_id'];
                    $nft->from_location_name = $request['from_location_name'];
                    $nft->from_location_id = $request['from_location_id'];
                    $nft->to_location_name = $request['to_location_name'];
                    $nft->to_location_id = $request['to_location_id'];
                    $nft->sample_description = $request['sample_description'];
                    $nft->status = $request['status'];
                    $nft->riders_name = $request['riders_name'];
                    $nft->delivered_on = $request['delivered_on'];
                    $nft->entered_by = $request['entered_by'];
                    $nft->save();
                } else {

                    $store_data = $request;
                    $nftdata = new NftActivities();
                    $data_array = [
                        'unique_id' => $store_data['unique_id'],
                        'activity_start_date' => $store_data['activity_start_date'],
                        'from_location_name' => $store_data['from_location_name'],
                        'from_location_id' => $store_data['from_location_id'],
                        'to_location_name' => $store_data['to_location_name'],
                        'to_location_id' => $store_data['to_location_id'],
                        'sample_description' => $store_data['sample_description'],
                        'status' => $store_data['status'],
                        'riders_name' => $store_data['riders_name'],
                        'delivered_on' => $store_data['delivered_on'],
                        'entered_by' => $store_data['entered_by'],
                    ];

                    $nftdata = NftActivities::create($data_array);
                }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'NFT updated successfully';
            return response()->json($ret);
        } catch (\Exception $e) {
            //\Log::info($e);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function printQr()
    {
        $id = 2490;
        // $id = 900519;
        $facility = Facility::find($id);
        return view('facility.playstoreqr', compact('facility'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function receiveSmallPackage(Request $request)
    {

        $form_data = $request->all();
        /*$form_data = [
            "barcode" => "RC024625",
            "user_id" => 1,
            "numberofsamples" => 4,
            "is_tracked_from_facility" => 0,
            "transfer_to" => 2490,
            "ref_lab_id" => 2490,
            "is_to_be_transfered" => 0,
            "receipt_date" => '',
        ];*/
        // dd($form_data);

        $ret_arr = array('delivered_on' => '', 'date_picked' => '');
        $small_package = Package::where('barcode', '=', $form_data['barcode'])->first();

        if ($small_package->exists()) {

            try {
                \DB::transaction(function ($request_obj) use ($form_data, $ret_arr, $small_package) {
                    $package_receipt = new PackageReceipt;
                    $package_receipt->packageid = $small_package->id;
                    $package_receipt->packagetype = $form_data['user_id'];
                    $package_receipt->received_by = $form_data['user_id'];
                    $package_receipt->previous_status = $small_package->status;
                    $package_receipt->numberofsamples = $form_data['numberofsamples'] == '' ? 0 : $form_data['numberofsamples'];
                    $package_receipt->created_by = $form_data['user_id'];
                    $package_receipt->save();

                    //mark package as received at CPHL
                    $small_package->status = 3;
                    $small_package->is_tracked_from_facility = $form_data['is_tracked_from_facility'];
                    if ($form_data['receipt_date'] != '') {
                        $small_package->received_at_destination_on = $form_data['receipt_date'];
                    } else {
                        $small_package->received_at_destination_on = date('Y-m-d H:m:s');
                    }

                    $small_package->numberofsamples = $form_data['numberofsamples'] == '' ? 0 : $form_data['numberofsamples'];
                    $small_package->save();
                    if (!$small_package->first_received_at) {
                        $small_package->first_received_at = $form_data['ref_lab_id'];
                    }
                    $event = new PackageMovementEvent;
                    $event->package_id = $small_package->id;
                    $event->source = $form_data['ref_lab_id'];
                    $event->destination = $form_data['ref_lab_id'];
                    $event->status = 3;
                    $event->location = $form_data['ref_lab_id'];
                    $event->category_id = $small_package->test_type;
                    //$event->longitude 
                    //$event->latitude
                    $event->place_name = 'CPHL';
                    $event->created_by = $form_data['user_id'];
                    if ($form_data['receipt_date'] != '') {
                        $event->created_at = $form_data['receipt_date'];
                    }
                    $event->save();
                    //update package with latest event_id
                    $small_package->latest_event_id = $event->id;

                    if ($form_data['is_to_be_transfered']) {
                        //create transfer event
                        $ref_event = new PackageMovementEvent;
                        $ref_event->package_id = $small_package->id;
                        $ref_event->source = $form_data['ref_lab_id'];
                        $ref_event->destination = $form_data['transfer_to'];
                        $ref_event->status = 4;
                        $ref_event->location = $form_data['ref_lab_id'];
                        $ref_event->category_id = $small_package->test_type;
                        //$ref_event->longitude 
                        //$ref_event->latitude
                        $ref_event->place_name = 'CPHL';
                        $ref_event->created_by = $form_data['user_id'];

                        $ref_event->save();
                        //update package with latest event_id
                        $small_package->latest_event_id = $ref_event->id;
                    }
                    //update package 
                    $small_package->save();
                });
                $ret_arr['status'] = 200;
                $ret_arr['status_desc'] = 'Package received Successfully';
                $ret_arr['delivered_on'] = $small_package->delivered_on;
                $ret_arr['date_picked'] = date('Y-m-d H:i:s', strtotime($small_package->created_at));
                return response()->json($ret_arr);
            } catch (\Exception $e) {
                \Log::info($e);
                $ret_arr['status'] = 501;
                $ret_arr['status_desc'] = $e->getMessage();
                $ret_arr['delivered_on'] = '';
                $ret_arr['date_picked'] = '';
                return response()->json($ret_arr);
            } //close try catch

        } else {
            $ret_arr['status'] = 501;
            $ret_arr['status_desc'] = 'The package was not found';
            $ret_arr['delivered_on'] = '';
            $ret_arr['date_picked'] = '';
            return response()->json($ret_arr);
        }
        //close if else package is retrieved
        //return redirect()->route('reception.list')->with('success','Package successfully received.');
    }

    public function processReceipt(Request $request)
    {

        $form_data = $request->all();
        $form_data['user_id'] = Auth::user()->id;
        \DB::transaction(function ($request_obj) use ($form_data) {
            $packages = $form_data['packages'];
            try {
                foreach ($packages as $package) {

                    $small_package = Package::findOrFail($package['small_package_id']);
                    //create record for package receipt
                    $package_receipt = new PackageReceipt;
                    $package_receipt->packageid = $package['small_package_id'];
                    $package_receipt->packagetype = $small_package->type;
                    $package_receipt->received_by = $form_data['user_id'];
                    $package_receipt->previous_status = $small_package->status;
                    $package_receipt->numberofsamples = $package['number_of_samples'];
                    $package_receipt->created_by = $form_data['user_id'];
                    $package_receipt->save();

                    //mark package as received at CPHL
                    $small_package->status = 3;
                    $small_package->is_tracked_from_facility = $package['is_tracked_from_facility'];
                    $small_package->save();
                }
                //mark the big package as received
                $big_package = Package::findOrFail($form_data['big_package_id']);
                $big_package->status = 3;
                $big_package->save();

                //now save the
                $event = new PackageMovementEvent;
                $event->package_id = $big_package->id;
                $event->source = 2490;
                $event->destination = 2490;
                $event->status = 3;
                $event->location = 2490;
                $event->category_id = $big_package->test_type;
                //$event->longitude 
                //$event->latitude

                $event->place_name = 'CPHL';
                $event->created_by = $form_data['user_id'];

                $event->save();

                //update package with latest event_id
                $big_package->latest_event_id = $event->id;

                if ($form_data['is_to_be_transfered']) {
                    //create transfer event
                    $ref_event = new PackageMovementEvent;
                    $ref_event->package_id = $package->id;
                    $ref_event->source = 2490;
                    $ref_event->destination = 2490;
                    $ref_event->status = 4;
                    $ref_event->location = 2490;
                    $ref_event->category_id = $big_package->test_type;
                    //$ref_event->longitude 
                    //$ref_event->latitude
                    $ref_event->place_name = 'CPHL';
                    $ref_event->created_by = $form_data['user_id'];

                    $ref_event->save();
                    //update package with latest event_id
                    $big_package->latest_event_id = $ref_event->id;
                }
                //update package 
                $big_package->save();
            } catch (\Exception $e) {

                print_r('faild to save' . $e);
                exit;

                //->with('flash_message', 'failed');
            }
        });
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'package received Successfully';
        return response()->json($ret_arr);
        //dd('asd');
        //return redirect()->route('reception.list')->with('success','Package successfully received.');
        //exit;
    }

    public function createPackageFromOtherSystems(Request $request)
    {
        $ret = $messages = array();
        $post_data = $request;

        try {
            \DB::transaction(function () use ($request, $post_data) {
                /*$this->validate($request, [
                    'package' => 'required|exists:barcode'
                ]);*/
                $post_data['test_type'] = getTestIdFromName($post_data['samples'][0]['test_type_code']);
                $post_data['user_id'] = 0;

                if ($post_data['facility_identifier'] != '') {
                    $facility_id = getFacilityIdFromDHIS2Code($post_data['facility_identifier']);
                    $post_data['final_destination'] = 10000;
                    $post_data['token'] = 1;
                } else {
                    $facility_id = $post_data['facilityid'];
                }

                $hub_id = getHubforFacility($facility_id);

                $package = new Package;
                $package_arr = [
                    'barcode' => $post_data['package_identifier'],
                    'facilityid' => $facility_id,
                    'hubid' => $hub_id,
                    //$post_d]ata['hubid'],
                    'test_type' => $post_data['test_type'],
                    'sample_type' => $post_data['sample_type'],
                    'date_picked' => $post_data['date_picked'],
                    'created_by' => $post_data['user_id'],
                    'system_id' => $post_data['token'],
                ];
                /* initial status should depend on type of user - poe, 0 (waiting pickup) otherwise 1 (in transit)
                */


                // dd("asdasdas");
                if ($post_data['user_id'] == '') {
                    $post_data['facilityid'] = $facility_id;
                    $post_data['user_id'] = 0;
                    $event_status = 0;
                } else {
                    if (isPoeOrEocUser($post_data['user_id'])) {
                        $event_status = 0;
                    } else {
                        $event_status = 1;
                    }
                }
                //Set final_destination based no whether the test_type's lab is known or not. final_destination' => $post_data['final_destination'],

                if (isset($post_data['final_destination']) && $post_data['final_destination'] != '') {
                    // \Log::info($post_data);
                    $package_arr['final_destination'] = $post_data['final_destination'];
                } else {
                    //get final destination based on sample_type
                    $t_type = TestType::findOrFail($post_data['test_type']);
                    $package_arr['final_destination'] = $t_type->ref_lab;
                    //set the final_destiation in the post data
                    $post_data['final_destination'] =  $t_type->ref_lab;
                }

                if (isset($post_data['children']) && $post_data['children'] != '') {
                    $package->type = 2;
                    $package_arr['type'] = 2;
                    //This is a repacking of existing packages
                    $event_status = 5;
                } else {
                    $package_arr['type'] = 1;
                }
                if (isset($post_data['samples']) && $post_data['samples'] != '') {
                    $package_arr['numberofsamples'] = count($post_data['samples']);
                    $package_arr['is_batch'] = 0;
                } else {
                    $package_arr['numberofsamples'] = $post_data['number_of_samples'];
                    $package_arr['is_batch'] = 1;
                }
                // needed especially where package was not
                if (isset($post_data['status']) && $post_data['status'] != '') {
                    $event_status = $post_data['status'];
                }
                $package_arr['is_tracked_from_facility'] = 1;

                //$package->save(); 
                //if(!Package::where('barcode','=',$post_data['barcode'])->first()){
                //validate samples
                // dd($post_data['package_identifier']);
                $validator = Validator::make(
                    $package_arr,
                    [
                        'barcode' => 'required|unique:package'
                    ],
                    [
                        'barcode.required' => 'The barcode is required ',
                        'barcode.unique' => 'The barcode is already used ',
                    ]
                );
                // if ($validator->fails()) {
                //     $messages = $validator->errors();
                //     $return_msg = array();
                //     foreach ($messages->all() as $message) 
                //     {
                //         array_push($return_msg, $message);
                //     }
                //     dd($return_msg);
                //     $error_messages[] = $return_msg;
                // } else 
                // {
                // dd("created object");
                $exist_package = Package::where('barcode', '=', $post_data['package_identifier'])->first();

                if ($exist_package != null) {
                    $package = $exist_package;
                } else {
                    $package = Package::create($package_arr);
                }
                // dd($exist_package);

                //now save the event
                $event = $this->createEvent($post_data, $package->id, $event_status);
                $package->latest_event_id = $event->id;
                if ($package->final_destination == 0) {
                    $package->final_destination = 2490;
                }

                $package->save();

                //update the children with the status of their parent
                if (isset($post_data['children']) && $post_data['children'] != '') {
                    $this->setParentForChildrenPackages($post_data['children'], $package->id, $event->id);
                }
                //in case ther are any samples, save them
                if (isset($post_data['samples']) && $post_data['samples'] != '') {
                    //save each sample
                    $this->createSamplesForOtherSystems($post_data, $package->id, $event->id, $hub_id);
                    //$this->createSamples($post_data, $package->id, $event->id, $hub_id);
                }
                // }

                // }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'The package has been successfully captured';
            return response()->json($ret);
        } catch (\Exception $e) {
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    private function createSamplesForOtherSystems($post_data, $package_id, $event_id, $hub_id)
    {
        foreach ($post_data['samples'] as $sample) {
            // $exist_sample = Sample::where('barcode','=',$post_data['sample_identifier'])->first();
            // dd($exist_sample);
            // if($exist_sample != null)
            // {

            // }
            // else
            // {
            $sample_obj = new Sample;
            $sample_obj->barcode = $sample['sample_identifier'];
            $sample_obj->facilityid = $post_data['facilityid'];
            $sample_obj->hubid = $hub_id;
            $sample_obj->package_id = $package_id;
            if ($sample['test_type_code'] != '') {
                $test_type = getTestIdFromName($sample['test_type_code']);
            } else {
            }
            $sample_obj->test_type = $test_type;
            // $sample_obj->sample_type = $sample['sample_type'];
            // $sample_obj->suspected_disease = $sample['suspected_disease'];
            // $sample_obj->surveillance_code = $sample['surveillance_code'];
            $sample_obj->date_picked = $post_data['date_picked'];
            $sample_obj->numberofsamples = 1;
            $sample_obj->latest_event_id = $event_id;
            $sample_obj->save();
        }
        // }
        return 1;
    }

    public function createPackage_new(Request $request)
    {
        $ret = $messages = array();
        $post_data = $request;
        // dd($post_data['user_id']);

        try {
            \DB::transaction(function () use ($request, $post_data) {
                /*$this->validate($request, [
                    'package' => 'required|exists:barcode'
                ]);*/
                $hub_id = getHubforFacility($post_data['facilityid']);
                $package = new Package;
                $package_arr = [
                    'barcode' => $post_data['barcode'],
                    'facilityid' => $post_data['facilityid'],
                    'hubid' => $hub_id,
                    //$post_data['hubid'],
                    'test_type' => $post_data['test_type'],
                    'sample_type' => $post_data['sample_type'],
                    'date_picked' => $post_data['date_picked'],
                    'created_by' => $post_data['user_id'],
                ];
                /* initial status should depend on type of user - poe, 0 (waiting pickup) otherwise 1 (in transit)
                */
                if (isPoeOrEocUser($post_data['user_id'])) {
                    $event_status = 0;
                } else {
                    $event_status = 1;
                }

                //Set final_destination based no whether the test_type's lab is known or not. final_destination' => $post_data['final_destination'],
                if (isset($post_data['final_destination']) && $post_data['final_destination'] != '') {
                    // \Log::info($post_data);
                    // $package_arr['final_destination'] = $post_data['final_destination'];
                } else {
                    //get final destination based on sample_type
                    $t_type = TestType::findOrFail($post_data['test_type']);
                    // $package_arr['final_destination'] = $t_type->ref_lab;
                    //set the final_destiation in the post data
                    // $post_data['final_destination'] =  $t_type->ref_lab;
                }

                if (isset($post_data['children']) && $post_data['children'] != '') {
                    $package->type = 2;
                    $package_arr['type'] = 2;
                    //This is a repacking of existing packages
                    $event_status = 5;
                } else {
                    $package_arr['type'] = 1;
                }
                if (isset($post_data['samples']) && $post_data['samples'] != '') {
                    $package_arr['numberofsamples'] = count($post_data['samples']);
                    $package_arr['is_batch'] = 0;
                } else {
                    $package_arr['numberofsamples'] = $post_data['number_of_samples'];
                    $package_arr['is_batch'] = 1;
                }
                // needed especially where package was not
                if (isset($post_data['status']) && $post_data['status'] != '') {
                    $event_status = $post_data['status'];
                }
                $package_arr['is_tracked_from_facility'] = 1;
                // $package->save(); 
                if (!Package::where('barcode', '=', $post_data['barcode'])->first()) {
                    //validate samples

                    //dd('created object');
                    $package = Package::create($package_arr);
                    //now save the event
                    $event = $this->createEvent($post_data, $package->id, $event_status);
                    $package->latest_event_id = $event->id;
                    // if ($package->final_destination == 0) {
                    //     $package->final_destination = 2490;
                    // }

                    $package->save();
                    //update the children with the status of their parent
                    if (isset($post_data['children']) && $post_data['children'] != '') {
                        $this->setParentForChildrenPackages($post_data['children'], $package->id, $event->id, $post_data);
                    }
                    //in case ther are any samples, save them
                    if (isset($post_data['samples']) && $post_data['samples'] != '') {
                        //save each sample
                        $this->createSamples($post_data, $package->id, $event->id, $hub_id);
                    }
                }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'The package has been successfully captured';
            return response()->json($ret);
        } catch (\Exception $e) {
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function createPackage_new_old(Request $request)
    {
        $ret = $messages = array();
        $post_data = $request;
        // dd($post_data['user_id']);

        try {
            \DB::transaction(function () use ($request, $post_data) {
                /*$this->validate($request, [
                    'package' => 'required|exists:barcode'
                ]);*/
                $hub_id = getHubforFacility($post_data['facilityid']);
                $package = new Package;
                $package_arr = [
                    'barcode' => $post_data['barcode'],
                    'facilityid' => $post_data['facilityid'],
                    'hubid' => $hub_id,
                    //$post_data['hubid'],
                    'test_type' => $post_data['test_type'],
                    'sample_type' => $post_data['sample_type'],
                    'date_picked' => $post_data['date_picked'],
                    'created_by' => $post_data['user_id'],
                ];
                /* initial status should depend on type of user - poe, 0 (waiting pickup) otherwise 1 (in transit)
                */
                if (isPoeOrEocUser($post_data['user_id'])) {
                    $event_status = 0;
                } else {
                    $event_status = 1;
                }

                //Set final_destination based no whether the test_type's lab is known or not. final_destination' => $post_data['final_destination'],

                if (isset($post_data['final_destination']) && $post_data['final_destination'] != '') {
                    // \Log::info($post_data);
                    // $package_arr['final_destination'] = $post_data['final_destination'];
                } else {
                    //get final destination based on sample_type
                    $t_type = TestType::findOrFail($post_data['test_type']);
                    // $package_arr['final_destination'] = $t_type->ref_lab;
                    //set the final_destiation in the post data
                    // $post_data['final_destination'] =  $t_type->ref_lab;
                }

                if (isset($post_data['children']) && $post_data['children'] != '') {
                    $package->type = 2;
                    $package_arr['type'] = 2;
                    //This is a repacking of existing packages
                    $event_status = 5;
                } else {
                    $package_arr['type'] = 1;
                }
                if (isset($post_data['samples']) && $post_data['samples'] != '') {
                    $package_arr['numberofsamples'] = count($post_data['samples']);
                    $package_arr['is_batch'] = 0;
                } else {
                    $package_arr['numberofsamples'] = $post_data['number_of_samples'];
                    $package_arr['is_batch'] = 1;
                }
                // needed especially where package was not
                if (isset($post_data['status']) && $post_data['status'] != '') {
                    $event_status = $post_data['status'];
                }
                $package_arr['is_tracked_from_facility'] = 1;
                // $package->save(); 
                if (!Package::where('barcode', '=', $post_data['barcode'])->first()) {
                    //validate samples
                    $validator = Validator::make(
                        $package_arr,
                        [
                            'barcode' => 'required|unique:package'
                        ],
                        [
                            'barcode.required' => 'The barcode is required ',
                            'barcode.unique' => 'The barcode is already used ',
                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->errors();
                        $return_msg = array();
                        foreach ($messages->all() as $message) {
                            array_push($return_msg, $message);
                        }
                        // dd($return_msg);
                        $error_messages[] = $return_msg;
                    } else {
                        //dd('created object');
                        $package = Package::create($package_arr);
                        //now save the event
                        $event = $this->createEvent($post_data, $package->id, $event_status);
                        $package->latest_event_id = $event->id;
                        // if ($package->final_destination == 0) {
                        //     $package->final_destination = 2490;
                        // }

                        $package->save();
                        //update the children with the status of their parent
                        if (isset($post_data['children']) && $post_data['children'] != '') {
                            $this->setParentForChildrenPackages($post_data['children'], $package->id, $event->id, $post_data);
                        }
                        //in case ther are any samples, save them
                        if (isset($post_data['samples']) && $post_data['samples'] != '') {
                            //save each sample
                            $this->createSamples($post_data, $package->id, $event->id, $hub_id);
                        }
                    }
                }
            });
            $ret['status'] = 200;
            $ret['status_desc'] = 'The package has been successfully captured';
            return response()->json($ret);
        } catch (\Exception $e) {
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            $ret['status'] = 501;
            $ret['status_desc'] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function getPackages_perhub()
    {


        $query = "SELECT p.id, p.barcode,sf.name as source_facility, fd.name as final_destination, ef.name as last_location, p.latest_event_id, tt.name as test_name, p.numberofsamples as numberofsamples from packagemovement_events pme
                INNER JOIN package p ON p.latest_event_id = pme.id
                INNER JOIN facility ef ON(pme.location = ef.id)
                INNER JOIN facility sf ON(p.facilityid = sf.id)
                LEFT JOIN facility fd ON(p.final_destination = fd.id)
                LEFT JOIN testtypes tt ON (p.test_type = tt.id)
                WHERE pme.status < 2 AND pme.created_by = " . $cat_id . " AND  pme.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + INTERVAL 1 DAY)";

        $db_data = \DB::select($query);
        $ret_arr = ['samples' => array_values($db_data)];
        $ret_arr['status'] = 200;
        $ret_arr['status_desc'] = 'Packages fetched successfully';
        return response()->json($ret_arr);
    }
}
