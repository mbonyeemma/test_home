<?php

namespace App\Http\Controllers;

use App\ApprovalSetting;
use Illuminate\Http\Request;

class ApprovalSettingController extends Controller
{
    public function index()
    {
        $settings = ApprovalSetting::all();
        return view('approval_settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_of_approval' => 'required|integer|min:1',
        ]);

        ApprovalSetting::create($request->only('no_of_approval'));

        return redirect()->back()->with('success', 'Approval setting added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_of_approval' => 'required|integer|min:1',
        ]);

        $setting = ApprovalSetting::findOrFail($id);
        $setting->update($request->only('no_of_approval'));

        return redirect()->back()->with('success', 'Approval setting updated.');
    }
}