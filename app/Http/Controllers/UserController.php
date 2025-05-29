<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;
use Hash;

//Importing laravel-permission models
use App\Models\Role;
use App\Models\Permission;
use \App\Models\Staff as Staff;
//Enables us to output flash messaging
use Session;

class UserController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
    //Get all users and pass it to the view
        $users = User::all(); 
        return view('users.index')->with('users', $users);
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
    	//Get all roles and pass it to the view
        $roles = Role::whereNotIn('id', [20,17,15,14,13,12,5])->get();
		//get all hubs
		$hubs = getAllHubs();
        $ips = getAllIps();
		$healthregions = getAllHealthRgions();
        return view('users.create', ['roles'=>$roles, 'hubs'=>$hubs, 'ips'=>$ips, 'healthregions' => $healthregions]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
    //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
		try {
				$user = new User;
				$user->email = $request->email;
				$user->name = $request->name;
				$user->setPasswordAttribute($request->password);
				$user->hubid = $request->hubid;
                $user->organisation_id = $request->organisation_id;
				$user->healthregionid = $request->healthregionid;
				$user->username = $request->username;
				$user->save();
				//$user = User::create($request->only('email', 'name', 'password','hubid','healthregionid','username')); 
				$user->roles()->attach($request['roles']);
				 return redirect()->route('users.show', array('id' => $user->id))->with('flash_message',
				 'User successfully added.');
			}catch (\Exception $e) {
				print_r($e);
				exit;
			}
    }

        /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function storeMobileAppUser(Request $request) {
        //Validate name, email and password fields
            $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6|confirmed'
            ]);
            try {
                    $user = new User;
                    $user->email = $request->email;
                    $user->name = $request->name;
                    $user->setPasswordAttribute($request->password);
                    $user->hubid = $request->hubid;
                    $user->organisation_id = $request->organisation_id;
                    $user->healthregionid = $request->healthregionid;
                    $user->username = $request->username;
                    $user->save();
                    //$user = User::create($request->only('email', 'name', 'password','hubid','healthregionid','username')); 
                    $user->roles()->attach($request['roles']);
                     return redirect()->route('users.show', array('id' => $user->id))->with('flash_message',
                     'User successfully added.');
                }catch (\Exception $e) {
                    print_r($e);
                    exit;
                }
        }


    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        $user = User::findOrFail($id); //Find post of id = $id
        return view ('users.show', compact('user')); 
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        //$roles = Role::get(); //Get all roles
        $roles = Role::whereNotIn('id', [20,17,15,14,13,12,5])->get();
		$hubs = getAllHubs();
        $ips = getAllIps();
		$healthregions = getAllHealthRgions();
        return view('users.edit', compact('user', 'roles','hubs','healthregions','ips')); //pass user and roles data to view

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6|confirmed'
        ]);
       // $input = $request->only(['name', 'email', 'password']); 
        $roles = $request['roles']; //Retreive all roles
		$user->email = $request->email;
		$user->name = $request->name;
		if(!empty($request->password)){
			$user->setPasswordAttribute($request->password);
		}
		if(!empty($request->hubid)){
			$user->hubid = $request->hubid;
		}
        if(!empty($request->hubid)){
            $user->organisation_id = $request->organisation_id;
        }
        
		if(!empty($request->healthregionid)){
			$user->healthregionid = $request->healthregionid;
		}
		if(!empty($request->username)){
			$user->username = $request->username;
		}
		$user->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        }        
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
            
		return redirect()->route('users.show', array('id' => $user->id))->with('flash_message',
             'User successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */


    public function destroy($id) {
    //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }
	
	public function resetpassword($userid){
		$userid= $userid;
		
		return view('users.resetpassword')->with('userid',$userid);
	}
	public function saveresetpassword(Request $request) {
		
		$user = User::findOrFail($request->userid);
		//if posted user_id is equal to logged in user_id, 
        //user is changing their own password
        if($user->id == \Auth::user()->id){
            //compare the old password with existing password 
            if(Hash::check($request->oldpassword,$user->password)) {
                // Right password
            } else {
                // redirect user to reset password page
                return redirect()->route('user.resetpassword', array('id' => $user->id))->with('flash_message',
                 'invalid old password.');
            }
        }
        
        $user->setPasswordAttribute($request->password);
        $user->save();
        //if user has staff, redirect to staff
        $staff = Staff::where('user_id', '=',$user->id)->first(); 
        if($staff){
             return redirect()->route('staff.show', array('id' => $staff->id))->with('flash_message',
         'You have successfully changed the password.');
        }else{
            return redirect()->route('users.show', array('id' => $user->id))->with('flash_message',
         'You have successfully changed the password.');
        }
        
		
	}
}
