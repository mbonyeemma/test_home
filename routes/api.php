<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\Api\FormApiController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function () {
	Route::get('/events/', 'eidrController@events');
	Route::get('/restrack/get/data_for/{lookup_type_name}', 'restrackController@getLookUps');
	Route::get('/restrack/get/place/{lookup_type_name}', 'restrackController@getPlace');
	Route::get('/restrack/get/hub_facilities/{id}', 'restrackController@facilitiesForHup');
	Route::get('/restrack/get/facilities_with_their_hubs/', 'restrackController@getFacilitiesWithTheirHupbs');
	Route::get('/restrack/get/samples', 'restrackController@getSamples');
	Route::get('/restrack/get/packages/for/{cat}/id/{id?}', 'restrackController@getPackages');
	Route::get('/restrack/get/delivered_packages/for/{cat}/id/{id?}', 'restrackController@getDeliveredPackages');
	Route::post('/restrack/update/package/status', 'restrackController@updadePackageStatus');
	Route::post('/restrack/login/', 'restrackController@login');
	Route::post('/restrack/create_package/', 'restrackController@createPackage'); //add noti
	Route::post('/restrack/deliver_results/', 'restrackController@deliverResults');// add noti
	Route::post('/restrack/change_password/', 'restrackController@changePassword');
	Route::post('/restrack/add_more_samples_to_package/', 'restrackController@addMoreSamplesToPackage');// add noti
	Route::post('/restrack/store_login_location/', 'restrackController@storeLocationLogin');
	Route::get('/restrack/get/number_of_facilities', 'restrackController@getTotalNumberofFacilitiesAndTesttypes');
	Route::get('/restrack/get/datepackages/{provided_date}', 'restrackController@getPackagesBeyondDate');
	Route::get('/restrack/get/datepackagesperday/{provided_date}', 'restrackController@getPackagesPerDate');
	//these routes are not really for api but for migration of the existing data
	Route::get('/set_staff_password', 'DataMigrationController@setStaffPassword');
	Route::get('/set_package_creator_as_user', 'DataMigrationController@setPackageCreatorasUser');
	Route::get('/set_untracked_package_creator_as_user', 'DataMigrationController@setUnTrackedCreatedasUser');
	Route::get('/set_parent_for_package', 'DataMigrationController@setParentForPackage');
	Route::get('/is_batch_or_individual_package', 'DataMigrationController@setIsBacth');
	Route::post('/restrack/receive_small_package', 'restrackController@receiveSmallPackage');
	Route::post('/restrack/create_external_package/', 'restrackController@createPackageFromOtherSystems');
	// Route::get('/restrack/receive_sample/', 'restrackController@receiveSample');

	Route::post('/restrack/nft_activities/', 'restrackController@storeNftvariables');  //NFT insert api
	Route::post('/restrack/nft_update_activities/', 'restrackController@updateNftvariable');  //NFT insert api
	Route::get("/restrack/printqr", array(
		"as"   => "facility.printqr",
		"uses" => "restrackController@printQr"
	));

	Route::post('/reception/receivelispackage', array(
		'as' => 'reception.receivesmallpackage',
		'uses' => 'SampleReceptionController@receiveSmallPackagex'
	));

	Route::get('/restrack/receive_sample/', 'SampleReceptionController@receiveSmallPackage');
	Route::get('/restrack_new/get/facilities_with_their_hubs/', 'restrackController@getFacilitiesWithTheirHupbs');
	Route::get('/restrack_new/get/packagetoreceive/{barcode}', 'restrackController@getPackagesDetailsByBarcode');
	Route::get('/restrack_new/get/data_for/{lookup_type_name}', 'restrackController@getLookUps_new');
	Route::get('/restrack_new/get/datepackagesperday/{provided_date}', 'restrackController@getPackagesPerDate_new');
	Route::post('/restrack_new/create_package/', 'restrackController@createPackage_new');
	Route::get('/restrack_new/get/datepackages/{provided_date}', 'restrackController@getPackagesBeyondDate');
	Route::post('/restrack_new/update/package/status', 'restrackController@updadePackageStatus_new');
	Route::get('/restrack/get/syncpackage/{id}', 'restrackController@getPackagesPerDate_byId');
	Route::post('/restrack_new/register_user/', 'MobileAppRegistrationController@storeUser');
	Route::get('/restrack_new/packages_per_hub/', 'restrackController@packagesPerHub');
	
	Route::get('/restrack_new/check_user_status/{username}', 'MobileAppRegistrationController@checkUserStatus');
	Route::get('/restrack_new/pending_registrations/', 'MobileAppRegistrationController@getPendingRegistrations');
	Route::post('/restrack_new/approve_user/{id}', 'MobileAppRegistrationController@approveUser');
	
	Route::post('/restrack_new/login/', 'restrackController@login');
	Route::post('/restrack_new/send_package_invitation/', 'restrackController@sendPackageInvitation');
	Route::post('/restrack_new/save_prepared_packages/', 'restrackController@savePreparedPackages');
	Route::get('/restrack_new/get_prepared_packages/user/{userId}', 'restrackController@getPreparedPackages');
	Route::get('/restrack_new/get_packages_awaiting_pickup/user/{userId}', 'restrackController@getPackagesAwaitingPickup');

	// Role switching API routes for mobile app
	Route::post('/role/switch', 'RoleSwitchController@switchRole');
	Route::get('/role/current', 'RoleSwitchController@getCurrentRole');
	Route::get('/role/user-roles', 'RoleSwitchController@getUserRoles');

	Route::get('/forms', 'FormApiController@index');
    Route::get('/forms/{form_id}', 'FormApiController@show');
    Route::post('/forms/save-data', 'FormApiController@saveData');
    Route::get('/forms/approved', 'FormApiController@index_status');
    Route::get('/forms/approved/{form_id}', 'FormApiController@show_status');
    Route::get('/forms/colors', 'FormApiController@colors');
});
