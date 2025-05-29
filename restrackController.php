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
        // \Log::info($request);
        // $decoded_data = ;
        $username = $request['username'];
        $password = base64_decode($request['password']);
        if (Auth::attempt(array('username' => $username, 'password' => $password))) {
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
        $ret_arr = getFacilitiesforHub(base64_decode($hub_id));
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


                    //if the status is 2 (delivered) and the event location is the package's final destination, set the deliverer of the package
                    $update_str = '';
                    if ($request['status']  == 2 && $request['facilityid'] == $package->final_destination) {
                        $update_str .= " , delivered_on = '" . $event->created_at . "', delivered_by = " . $request['user_id'];
                    }

                    // if($request['status']  == 1 && $request['facilityid'] == $package->final_destination){
                    //     $update_str .= " , delivered_on = '".$event->created_at."', delivered_by = ".$request['user_id'];
                    // }
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


    /*List based functionality*/
    public function getPackages($categrory, $cat_id = 0)
    {
        //api/restrack/get/packages/by/{cat}/id/{}
        //api/restrack/get/packages/for/all/id
        //api/restrack/get/packages/for/user/id/20
        //api/restrack/get/packages/for/hub/id/3
        //api/restrack/get/packages/for/facility/id/8
        //api/restrack/get/packages/for/all/id/
        // api/restrack/get/packages/for/single/id/UNHLS2020-8014
        // api/restrack/get/packages/for/single/id/UNHLS2020-8014
        // api/restrack/get/packages/for/pending_delivery/id/
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
            $query = "SELECT id, barcode FROM package WHERE  created_at  BETWEEN DATE_SUB(NOW(), INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES') . " DAY) AND NOW()";
        } elseif ($categrory == 'pending_delivery') {
            $query = "SELECT id, barcode FROM package WHERE created_at  BETWEEN DATE_SUB(NOW(), INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES_OFF_LINE_MODE') . " DAY) AND NOW() AND status < 2 or status = 4";
        } elseif ($categrory == 'user') {
            $query = "SELECT p.barcode,sf.name as source_facility,fd.name as final_destination, ef.name as last_location, p.latest_event_id from packagemovement_events pme
INNER JOIN package p ON p.latest_event_id = pme.id
INNER JOIN facility ef ON(pme.location = ef.id)
INNER JOIN facility sf ON(p.facilityid = sf.id)
INNER JOIN facility fd ON(p.final_destination = fd.id)
WHERE pme.status < 2 AND pme.created_by = " . $cat_id . " AND  pme.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )";
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
            $user = User::findOrFail($request['user_id']);
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
}
