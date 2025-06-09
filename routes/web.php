<?php

use App\Http\Controllers\StaffController;

Auth::routes();

Route::group(['middleware' => 'cors'], function () {
	Route::any('/', 'NewDashboardController@index')->name('new');
});


Route::any('/samples/movement', 'KickStartController@index')->name('home');
Route::any('/monitor/samples', 'SampleMonitoringController@index')->name('monitor');
Route::get("/vl/data/{barcode}", "SampleMonitoringController@showSampleInCode");
Route::get("vl/data/{barcode}", array(
	"as"   => "vl.data",
	"uses" => "SampleMonitoringController@showSampleInCode"
));

Route::get('contact/list/{category}/{type}', 'ContactController@index');
Route::get('set_latest_event', 'KickStartController@setLatestEvent');
Route::get("/contact/all", array(
	"as"   => "contact.comprehensive_list",
	"uses" => "KickStartController@allContacts"
));

Route::get("/sampletracking/statistics", "SampleTrackingController@packageStatistics");
Route::get("/sampletracking/late_delivery", "SampleTrackingController@lateDelivery");
Route::get("/sampletracking/covid_stats/{status}", "SampleTrackingController@covid_stats");
Route::get("/sampletracking/trace_sample/{package}", "SampleTrackingController@trace_sample");
Route::get("/sampletracking/get_package_details/{package}", "SampleTrackingController@get_package_details");


// New dashboard Routes
Route::get("/tracking/facility_list/{hub}", "NewDashboardController@showFacilitiesVisitedByHub");
Route::any("/not_visited/facility/{hub}", "NewDashboardController@showFacilitesNotVisited");

Route::get("/volume/statistics", "NewDashboardController@totalNumberofSamplesDeliveredAtHub");
Route::get("/volume/cphl/statistics", "NewDashboardController@totalNumberofSamplesDeliveredAtCphl");

Route::get('regions/get_district_region/{regionid}', 'NewDashboardController@getDistrictsRegion')->name('region.get_district_region');
Route::get('district/get_district_hub/{districtid}', 'NewDashboardController@getDistrictHubs')->name('district.get_hub');


Route::group(['middleware' => 'auth'], function () {

	Route::get('user/resetpassword/{id}', array(
		'as' => 'user.resetpassword',
		'uses' => 'UserController@resetpassword'
	));
	Route::any('user/saveresetpassword', array(
		'as' => 'user.saveresetpassword',
		'uses' => 'UserController@saveresetpassword'
	));
	Route::get('reception/create_form', 'SampleReceptionController@create')->name('reception.create_form');

	Route::get('samples/get_district_hub/{facilityid}', 'SampleReceptionController@getDistrictHub')->name('reception.get_district_hub');

	Route::any('reception/create', array(
		'as' => 'reception.saveunscannedbarcode',
		'uses' => 'SampleReceptionController@saveunscannedbarcode'
	));
	Route::any('reception/list', array(
		'as' => 'reception.list',
		'uses' => 'SampleReceptionController@list'
	));
	Route::any('reception/receivesample/{id}', array(
		'as' => 'reception.receivesample',
		'uses' => 'SampleReceptionController@receiveSample'
	));
	Route::any('reception/receivesmallpackage', array(
		'as' => 'reception.receivesmallpackage',
		'uses' => 'SampleReceptionController@receiveSmallPackage'
	));

	Route::post("/reception/processreceipt", array(
		"as"   => "reception.processreceipt",
		"uses" => "SampleReceptionController@processReceipt"
	));
	Route::resource('users', 'UserController');
	Route::resource('samplereceiption', 'SampleReceiptionController');
	Route::get('message/list/{type}', 'MessageController@index')->name('messages');
	Route::resource('message', 'MessageController');
	Route::resource('roles', 'RoleController');
	Route::resource('permissions', 'PermissionController');
	Route::get('staff/new/{type}', 'StaffController@form');

	Route::get('staff/list/{type}', 'StaffController@index');

	Route::resource('staff', 'StaffController');

	Route::resource('sampletransporters', 'SampleTransporterController');

	Route::resource('sampletracking', 'SampleTrackingController');

	Route::get('equipment/down/hubid/{hubid?}/id/{id?}', array(
		'as' => 'equipment.breakdown',
		'uses' => 'EquipmentController@breakdownform'
	));
	Route::get('results/tracking', array(
		'as' => 'results.tracking',
		'uses' => 'SampleTrackingController@results'
	));
	Route::get('equipment/list/status/{id?}/', 'EquipmentController@elist');
	Route::post('equipment/bikesforhub', 'EquipmentController@bikesforhub');
	Route::post('staff/bikewithoutrider', 'StaffController@bikeWithoutRider');
	Route::post('equipment/hubbikes/hubid/{hubid}', 'EquipmentController@hubbikes');
	Route::get('equipment/list/service/{service?}/', 'EquipmentController@servicecont');
	Route::post('equipment/savebreakdown', 'EquipmentController@savebreakdown');
	Route::post('equipment/updatebreakdownstatus', 'EquipmentController@updatebreakdownstatus');
	Route::resource('equipment', 'EquipmentController');

	Route::resource('organization', 'OrganizationController');
	Route::get('dashboard/coordinator', array(
		'as' => 'dashboard.coordinator',
		'uses' => 'DashboardController@coordinator'
	));
	Route::resource('dashboard', 'DashboardController');

	Route::get("/hub/assignfacility", array(
		"as"   => "hub.assignfacility",
		"uses" => "HubController@assignfacility"
	));
	Route::get('download/hubinfo/{hubid}/type/{id?}', array(
		'as' => 'download.hubinfo',
		'uses' => 'DownloadController@hubinfo'
	));
	Route::post("/hub/massassignfacilities", array(
		"as"   => "hub.massassignfacilities",
		"uses" => "HubController@massassignfacilities"
	));

	Route::resource('hub', 'HubController');

	Route::get('healthunit/new/{type}', 'FacilityController@form');

	Route::get('healthunit/view/{type}', 'FacilityController@show');

	Route::get("facility/printqr/{id}", array(
		"as"   => "facility.printqr",
		"uses" => "FacilityController@printQr"
	));

	Route::resource('facility', 'FacilityController');

	Route::get('routingschedule/create/{hubid}', 'RoutingScheduleController@createform')->name('routingschedulecreate');
	Route::resource('routingschedule', 'RoutingScheduleController');

	Route::get("/dailyrouting/view/{date}/hubid/{hubid}", array(
		"as"   => "dailyrouting.view",
		"uses" => "DailyRoutingController@view"
	));
	Route::any("/dailyrouting/notvisited/status/{date}/", array(
		"as"   => "dailyrouting.notvisited",
		"uses" => "DailyRoutingController@notVisited"
	));
	Route::post("/dailyrouting/checkdatedata", array(
		"as"   => "dailyrouting.checkdatedata",
		"uses" => "DailyRoutingController@checkDateData"
	));
	Route::post(
		"/dailyrouting/facilitiesforhub",
		"DailyRoutingController@facilitiesForHub"
	);
	Route::post(
		"/dailyrouting/hubforfacilitityonly",
		"DailyRoutingController@hubForFacility"
	);
	Route::post(
		"/dailyrouting/hubforfacility",
		"DailyRoutingController@hubandDistrictForFacility"
	);
	Route::get("/dailyrouting/create/thedate/{thedate}/facilityid/{facilityid}/bikeid/{bikeid}/transporterid/{transporterid}", array(
		"as"   => "dailyrouting.createform",
		"uses" => "DailyRoutingController@createform"
	));

	Route::any("/dailyrouting/samplelist", array(
		"as"   => "dailyrouting.samplelist",
		"uses" => "DailyRoutingController@sampleList"
	));
	Route::any("/reports/hubsamples", array(
		"as"   => "reports.hubsamples",
		"uses" => "ReportController@hubSamples"
	));

	// Show all sample for all hubs
	Route::any("/hubsamples/all", array(
		"as"   => "all.sample",
		"uses" => "ReportController@showTotalSamples"
	));

	Route::any("/data/samples", array(
		"as"   => "hubsampl.data",
		"uses" => "ReportController@downloadFacilityData"
	));

	// Show all hub visits
	Route::any("/hubvisits", array(
		"as"   => "hub.visit",
		"uses" => "ReportController@showHubvisits"
	));

	Route::any("/hubvisit/download", array(
		"as"   => "hubvist.data",
		"uses" => "ReportController@downloadHubVisitData"
	));


	// Show Total samples for an IP in respective hubs
	Route::any("/ip/hubsamples", array(
		"as"   => "ip.hubsamples",
		"uses" => "DashboardController@showSamplesByHubIP"
	));

	Route::any("/IP/dashboard", array(
		"as"   => "ip.dashboard",
		"uses" => "DashboardController@monitorIpSamples"
	));

	Route::any("/dailyrouting/resultlist", array(
		"as"   => "dailyrouting.resultlist",
		"uses" => "DailyRoutingController@resultList"
	));
	Route::resource('labequipment', 'LabequipmentController');
	Route::get("/labequipment/list/status/{status}/", array(
		"as"   => "labequipment.list",
		"uses" => "LabequipmentController@elist"
	));
	Route::get('labequipment/down/hubid/{hubid?}/id/{id?}', array(
		'as' => 'labequipment.breakdown',
		'uses' => 'LabequipmentController@breakdownform'
	));
	Route::any("/samples/all/status/{status?}", array(
		"as"   => "samples.all",
		"uses" => "SampleTrackingController@all"
	));
	/*Route::any("/samples/processreceipt/p/{packageid?}/pm/{packagemovementid?}", array(
        "as"   => "samples.processreceipt",
        "uses" => "SampleTrackingController@processReceipt"
    ));*/
	Route::any("/samples/cphl/status/{status?}", array(
		"as"   => "samples.cphl",
		"uses" => "SampleTrackingController@cphl"
	));

	Route::post('/sampletracking/savereferral', array(
		'as' => 'sampletracking.savereferral',
		"uses" => "SampleTrackingController@saveReferral"
	));

	Route::resource('dailyrouting', 'DailyRoutingController');
	//contact routes
	Route::get('contact/new/category/{category}/type/{type}/obj/{obj?}', 'ContactController@form');
	Route::resource('contact', 'ContactController');
	//custom logout - redirect user to the login page - see controller for more
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
	Route::resource('infrastructure', 'InfrastructureController');
	Route::resource('meetingreport', 'MeetingReportController');
	Route::get('qr-code', function () {
		//echo  QrCode::generate(2);
		echo QrCode::size(399)->generate(50);
		// echo QrCode::size(399)->color(150,90,10)->backgroundColor(10,14,244)->generate(50);
		exit;
	});
	Route::get('test/{hubid?}', function ($hubid) {
		//$bikes = \App\Models\Equipment::where('hubid',$hubid)->whereDoesntHave('bikerider')->pluck("numberplate","id");
		//	print_r($bikes);
		//print_r(getUnassignedBikesforHub($hubid));
		//$bike_objects = \App\Models\Equipment::where('hubid',$hubid)->whereDoesntHave('bikerider')->pluck("numberplate","id");
		/*$facilities_objects = \App\Models\Facility::where('parentid', $hubid)->pluck("name","id");
    		//$html_options = getGenerateHtmlforAjaxSelect($facilities);
			$bikes = [];
			if(!empty($facilities_objects)){
				foreach($facilities_objects as $key => $value){
					array_push($bikes, ['id' => $key, 'plate' => $value]);
				}
			}
			print_r($bikes);*/
		$destinedforcphl = packageStats(5, 2);
		$receivedatcphl = packageStats(7, 2);
		$hubpackages = packageStats(1, 1);
		print_r(['destinedforcphl' => $destinedforcphl, 'receivedatcphl' => $receivedatcphl, 'hubpackages' => $hubpackages]);
		exit;
	});

	Route::get('testing/message', function () {
		//exit('in messages');
		//$receiver = \App\User::find(3); 
		//echo $receiver->id; exit;
		$messageData = [
			'content' => 'Another test33', // the content of the message
			'to_id' => 2, // Who should receive the message
			'from_id' => 3,
		];
		try {
			\App\Models\Message::createFromRequest($messageData);
			echo 'created';
			//exit;
		} catch (\Exception $e) {
			print_r($e);
			exit;
		}
	});


	Route::get("/notification/facilitiesnotvisited", array(
		"as"   => "notification.facilitiesnotvisited",
		"uses" => "NotificationsController@facilitiesNotVisited"
	));

	Route::resource('covid', 'CovidController');
	Route::post("/covid/process_list_filters", array(
		"as"   => "covid.process_list_filters",
		"uses" => "CovidController@index"
	));

	// Show facilities belonging to An IP
	Route::get('ip/facility', array(
		'as' => 'ip.facility',
		'uses' => 'DashboardController@showIpFacilites'
	));
});
Route::get('/settings', 'SettingsController@index')->name('settings');
Route::resource('signup', 'SignupController');

// Single Sign On routes
Route::get('/oauth/redirect', 'OAuthController@redirect');
Route::get('/oauth/callback', 'OAuthController@callback');
//Route::get('/oauth/refresh', 'OAuthController@refresh');

Route::any('/download_data/archive', 'NewDashboardController@getDownload')->name('download_data.archive');
//Route::get('/samples/events/start_date/{start_date?}/end_date/{end_date?}','eidrController@events');
Route::any('/data/archives', 'NewDashboardController@dataarchives')->name('monitor');
Route::get('staff/mobile/app', 'StaffController@index_mobile');
// under maintance
Route::get('/staff/approve/{id}', [StaffController::class, 'approve'])->name('staff.approve');
Route::post('/staff/reject', [StaffController::class, 'rejectWithReason'])->name('staff.rejectWithReason');

Route::get('approval-settings', 'ApprovalSettingController@index')->name('approval.index');
Route::post('approval-settings', 'ApprovalSettingController@store')->name('approval.store');
Route::post('approval-settings/{id}/update', 'ApprovalSettingController@update')->name('approval.update');

