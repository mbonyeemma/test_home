<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
//Importing laravel-permission models
use \App\Models\Role;
use \App\Models\Permission;

use Session;

class RoleController extends Controller {

    public function __construct() {
       // $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       // $roles = Role::all();//Get all roles
		$roles = Role::with('perms')->get();
        return view('roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::all();//Get all permissions

        return view('roles.create', ['permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
    //Validate name and permissions field
        $this->validate($request, [
            'name'=>'required|unique:roles|max:100',
            'permissions' =>'required',
            ]
        );
		try {
		   \DB::transaction(function() use($request){
			//try to save
			$name = $request['name'];
			$role = new Role();
			$role->display_name = $name;
			$role->name = generateSlug($name, '_');
			$role->save();
			//sync the permissions for the role
			$role->perms()->sync($request['permissions']);
		   });
		   return redirect()->route('roles.index')->with('flash_message',
					 'Permission added!');	
		}catch (\Exception $e) {
			print_r($e->getMessage());
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
		$role_permissions = $role->perms()->get();
        return view('roles.edit', compact('role', 'permissions','role_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $role = Role::findOrFail($id);//Get role with the given id
    	//Validate name and permission fields
        $this->validate($request, [
            'name'=>'required|max:100|unique:roles,name,'.$id,
            'permissions' =>'required',
        ]);
		//store all role data excecpt permissions arryay
        //$input = $request->except(['permissions']);        
        $role->display_name = $request->name;
		$role->name = generateSlug($request->name, '_');
		$role->save();
		
		$permissions = $request['permissions'];
        $existing_permissions_on_role = Permission::all();//Get all permissions

        foreach ($existing_permissions_on_role as $permission) {
           // $role->revokePermissionTo($p); //Remove all permissions associated with role
		   $role->perms()->detach($permission);
        }
		//exit;		
		//sync the new permissions to the role
		$role->perms()->sync($request['permissions']);
        return redirect()->route('roles.index')->with('flash_message',
             'Role'. $role->name.' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('flash_message',
             'Role deleted!');

    }
}