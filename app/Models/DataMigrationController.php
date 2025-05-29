 <?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
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
		foreach ($staff_members as $staff) {
			$username = strtolower($staff->firstname.$staff->lastname);
			//get user with this username
			$user = User::Where('user', '=', $username)->findOrFail();
			if(!$user){
				$user = new User;
				if(empty($staff->emailaddress)){
					$user->email = $staff->id.'@dev.com';
				}else{
					$user->email = $staff->emailaddress;
				}
				$roles = array();
				if($staff->type == 1){
					$roles = array(15);
				}elseif($staff->type == 2){
					if(Auth::user()->hubid == 2490){
						$roles = array(12);
					}else{
						$roles = array(5);
					}
					
				}elseif($staff->type ==4){
					$roles = array(14);
				}elseif($staff->type ==5){
					$roles = array(13);
				}elseif($staff->type ==6){
					$roles = array(17);
				}

				$user->name = $staff->firstname.' '.$staff->lastname;
				$user->setPasswordAttribute('password');
				$user->hubid = Auth::user()->hubid;
				$user->username = $username;		
				$user->save();	
				$user->roles()->attach($roles);
				//set the user id on staff
				\DB::unprepared("UPDATE staff SET user_id = ".$user->id." WHERE id = ".$staff->id);
			}
		}

	}
}
