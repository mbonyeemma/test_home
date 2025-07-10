<?php

namespace App\Http\Controllers;

use App\FieldChange;
use App\FormFields as AppFormFields;
use App\Models\FormFields;
use Illuminate\Http\Request;

class FieldChangeController extends Controller
{
    public function index()
    {
        $pendingChanges = FieldChange::with('form', 'maker')
            ->where('approval_status', 'pending')
            // ->where('maker_id', '!=', auth()->id()) // prevent viewing own
            ->get();
        return view('field_changes.index', compact('pendingChanges'));
    }

    public function approve($id)
    {
        $change = FieldChange::with('field')->findOrFail($id);

        // if ($change->maker_id === auth()->id()) {
        //    return back()->with('error', 'You cannot approve your own change.');
        // }

        if ($change->approval_status !== 'pending') {
            return back()->with('error', 'Change already reviewed.');
        }

        $data = $change->field_data;
        switch ($change->action) {
            case 'create':
                $field = AppFormFields::create(array_merge($data, [
                    'forms_id' => $change->form_id,
                    'maker_id' => $change->maker_id,
                    'checker_id' => auth()->id(),
                    'approval_status' => 'approved',
                    'checked_at' => now(),
                ]));
                $change->form_field_id = $field->id;
                break;

            case 'update':
                if ($change->field) {
                    $change->field->update(array_merge($data, [
                        'checker_id' => auth()->id(),
                        'approval_status' => 'approved',
                        'checked_at' => now(),
                    ]));
                }
                break;

            case 'delete':
                if ($change->field) {
                    $change->field->delete();
                }
                break;
        }

        $change->update([
            'approval_status' => 'approved',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        return back()->with('success', 'Field change approved.');
    }

    public function reject($id)
    {
        $change = FieldChange::findOrFail($id);

        // if ($change->maker_id === auth()->id()) {
        //     return back()->with('error', 'You cannot reject your own change.');
        // }

        $change->update([
            'approval_status' => 'rejected',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        return back()->with('success', 'Field change rejected.');
    }
}
