<?php
namespace App\Http\Controllers;

use Auth;
use Session;
use \App\Models\User as User;

class DataMigrationController extends Controller {
	public function setStaffPassword(){
		//DELETE FROM staff WHERE id NOT IN (SELECT MAX(id) FROM staff GROUP BY firstname,lastname)
		/*	
			1 - 15 -sample_transporter
			2 - Hub Cordinator
			3 - ?????? Ref Lab User
			4 - Driver - 14 -driver	
			5 - EOC - 13 -eoc
			6 - POE -poe_user
		*/
		//DELETE all staff users and recreate them 
		$query = "DELETE from users WHERE id IN(SELECT user_id FROM role_user WHERE role_id IN(13,14,15,16,17))";
		\DB::unprepared($query);
		//now get all staff user and recreate them
		$staff_members  = \DB::SELECT("SELECT * FROM staff");
		$normalTimeLimit = ini_get('max_execution_time');

	    // Set new limit
	    ini_set('max_execution_time', 600); 
		foreach ($staff_members as $staff) {
			 

		    //other code

		    // Restore default limit
		    
			$username = strtolower($staff->firstname.$staff->lastname);
			//get user with this username
			$user = User::Where('username', '=', $username)
						  ->orWhere('username', '=', $staff->telephonenumber)->first();
						
			if(!$user){
				$user = new User;
				$user->email = $staff->id.'@dev.com';								
				$roles = array();
				if($staff->type == 1){
					$roles = array(15);
				}elseif($staff->type == 2){
					$roles = array(5);					
				}elseif($staff->type ==4){
					$roles = array(14);
				}elseif($staff->type ==5){
					$roles = array(13);
				}elseif($staff->type ==6){
					$roles = array(17);
				}

				$user->name = $staff->firstname.' '.$staff->lastname;
				$pass = $staff->code != ''? $staff->code:'password';
				$user->setPasswordAttribute($pass);
				//if(property_exists($staff,'hubid')){
					$user->hubid = $staff->hubid;
				//}				
				$user->username = $staff->telephonenumber != '' ?$staff->telephonenumber:$username;		
				$user->save();	
				$user->roles()->attach($roles);
			}
			//set the user id on staff
			\DB::unprepared("UPDATE staff SET user_id = ".$user->id." WHERE id = ".$staff->id);
			
		}
		echo "done";
		ini_set('max_execution_time', $normalTimeLimit);
	}

	public function setPackageCreatorasUser(){
		$normalTimeLimit = ini_get('max_execution_time');

	    // Set new limit
	    ini_set('max_execution_time', 600);
		$query = "UPDATE `package` p INNER JOIN staff s SET p.created_by = s.user_id WHERE p.created_by = s.id";
		\DB::unprepared($query);
		ini_set('max_execution_time', $normalTimeLimit);
		dd('done');
	}
	public function setUnTrackedCreatedasUser(){
		$normalTimeLimit = ini_get('max_execution_time');

	    // Set new limit
	    ini_set('max_execution_time', 600);
		$query = "UPDATE package p INNER JOIN untracked_packages u SET p.created_by = u.created_by WHERE p.barcode = u.barcode";
		\DB::unprepared($query);
		ini_set('max_execution_time', $normalTimeLimit);
		dd('done');
	}
	public function setParentForPackage(){
		$normalTimeLimit = ini_get('max_execution_time');

	    // Set new limit
	    ini_set('max_execution_time', 600);
		$query = "UPDATE packagedetail pd INNER JOIN package p SET p.parent_id = pd.big_barcodeid WHERE p.id = small_barcodeid";
		\DB::unprepared($query);
		ini_set('max_execution_time', $normalTimeLimit);
		dd('done');
	}

	public function setIsBacth(){
		$samples = \DB::select('SELECT package_id FROM samples GROUP BY package_id');
		foreach ($samples as $sample) {
			\DB::unprepared('UPDATE package SET is_batch = 0 WHERE id = '.$sample->package_id);
		}
		dd('all set');
	}
}
