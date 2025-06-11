<?php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use App\Models\Role as Role;
use App\User as User;

class TestController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {

        $admin = new Role();
        $admin->name         = 'tyr';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();        
    }

    public function atachrole() {
        $user = User::where('username', '=', 'jude')->first();
        $admin = Role::where('name', '=', 'owner')->first();
        // role attach alias
        $user->attachRole($admin); // parameter can be an Role object, array, or id

        // or eloquent's original technique
        //$user->roles()->attach($admin->id); // id only
    }
}