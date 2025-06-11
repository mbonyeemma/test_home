<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MobileAppRegistration;

class MobileAppRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $user = new MobileAppRegistration;
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->setPasswordAttribute($request->password);
            $user->hubid = $request->hubid;
            $user->telephone_number = $request->telephone_number;
            $user->driving_permit = $request->driving_permit;
            $user->defensive_driving = $request->defensive_driving;
            $user->bb_training = $request->bb_training;
            $user->hep_b_immunisation = $request->hep_b_immunisation;
            $user->isactive = 0;
            $user->save();
            
        $ret['status'] = 200;
        $ret['status_desc'] = 'The User Saved has been successfully, Awaiting Approval';
        return response()->json($ret);
    } catch (\Exception $e) {
        //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        $ret['status'] = 501;
        $ret['status_desc'] = $e->getMessage();
        return response()->json($ret);
    }
    }

    public function storeUser(Request $request)
    {
        //
        try {
            $user = new MobileAppRegistration;
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->setPasswordAttribute($request->password);
            $user->hubid = $request->hubid;
            $user->telephone_number = $request->telephone_number;
            $user->driving_permit = $request->driving_permit;
            $user->defensive_driving = $request->defensive_driving;
            $user->bb_training = $request->bb_training;
            $user->hep_b_immunisation = $request->hep_b_immunisation;
            $user->isactive = 0;
            $user->save();
            
        $ret['status'] = 200;
        $ret['status_desc'] = 'The User Saved has been successfully, Awaiting Approval';
        return response()->json($ret);
    } catch (\Exception $e) {
        //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        $ret['status'] = 501;
        $ret['status_desc'] = $e->getMessage();
        return response()->json($ret);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function show(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobileAppRegistration $mobileAppRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileAppRegistration  $mobileAppRegistration
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileAppRegistration $mobileAppRegistration)
    {
        //
    }
}
