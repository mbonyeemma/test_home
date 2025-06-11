<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use \Entrust;
use \App\Models\Hub as Hub;
use \App\Models\Staff as Staff;
use File;
use \App\Models\Facility as Facility;
class DownloadController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function hubinfo($hubid, $type) {				
		
		$data_to_for_json = Staff::join('facility', 'facility.parentid',   '=', 'staff.hubid')
		->where('staff.hubid', '=', $hubid)->get(['staff.id as staffid','staff.firstname','staff.code', 'staff.lastname','staff.designation','staff.motorbikeid','staff.hubid','facility.id', 'facility.name'])->toArray(); 
		$file = 'hub_info.json';
		//exit;
		$data = json_encode($data_to_for_json);
      	$destinationPath = public_path()."/downloads/";
      	if (!is_dir($destinationPath)){  
			mkdir($destinationPath,0777,true);  
		}
      	File::put($destinationPath.$file,$data);
      	return response()->download($destinationPath.$file);
    }	
}