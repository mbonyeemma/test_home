<?php

namespace App\Http\Controllers;

use App\FieldChange;
use App\FormFields;
use App\Forms;
use App\Models\Facility;
use Illuminate\Http\Request;

class FormController extends Controller
{
    // Show form creation page with listing
    public function create()
    {
        $formId = 'FORM-' . strtoupper(uniqid());
        $forms = Forms::with('facility')->latest()->get();
        $facilities = Facility::orderBy('id')->pluck('name', 'id');
       
        return view('forms.create', compact('formId', 'forms','facilities'))->with('formMode', 'create');
    }

    // Store new form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'form_id' => 'required|string|max:255|unique:forms,form_id',
            'formSubmissionUrl' => 'required|url',
            'facility_id' => 'nullable|exists:facility,id',
        ]);

        Forms::create([
            'name' => $request->name,
            'form_id' => $request->form_id,
            'form_submission_url' => $request->formSubmissionUrl,
            'facility_id' => $request->facility_id,
        ]);

        return redirect()->route('forms.create')->with('success', 'Form created successfully.');
    }

    // Show UI for adding fields to a form
    public function createFields($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        $pendingCount = FieldChange::where('approval_status', 'pending')->count();
        return view('forms.fields-create', compact('form', 'pendingCount'));
    }

    // Store fields for a form
    public function storeFields_bk(Request $request, $form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*.field_label' => 'required|string|max:255',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.field_type' => 'required|in:input,dropdown,checkbox',
            'fields.*.option' => 'nullable|in:mandatory,optional',
            'fields.*.status' => 'nullable|in:enabled,disabled',
        ]);

        foreach ($request->fields as $field) {
            $form->fields()->create([
                'field_type' => $field['field_type'],
                'field_label' => $field['field_label'],
                'name' => $field['name'],
                'field_value' => $field['field_value'] ?? null,
                'option' => $field['option'] ?? 'optional',
                'status' => $field['status'] ?? 'enabled',
                'dropdown_options' => $field['field_type'] === 'dropdown'
                    ? explode(',', $field['dropdown_options'] ?? '')
                    : null,
            ]);
        }

        return redirect()->route('forms.fields.create', $form->form_id)
                         ->with('success', 'Fields added successfully.');
    }

    public function storeFields(Request $request, $form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();

        $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*.field_label' => 'required|string|max:255',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.field_type' => 'required|in:input,dropdown,checkbox',
            'fields.*.option' => 'nullable|in:mandatory,optional',
            'fields.*.status' => 'nullable|in:enabled,disabled',
        ]);
        $message='';
        foreach ($request->fields as $field) {
            $data = [
                'field_type' => $field['field_type'],
                'field_label' => $field['field_label'],
                'name' => $field['name'],
                'field_value' => $field['field_value'] ?? null,
                'option' => $field['option'] ?? 'optional',
                'status' => $field['status'] ?? 'enabled',
                'dropdown_options' => $field['field_type'] === 'dropdown'
                    ? explode(',', $field['dropdown_options'] ?? '')
                    : null,
            ];

            if ($form->publish_status === 'approved') {
                // Require maker-checker
                $data['maker_id'] = auth()->id();
                $data['approval_status'] = 'pending';
                //Field Change
                 FieldChange::create([
                'form_field_id' => null,
                'form_id' => $form->id,
                'maker_id' => auth()->id(),
                'action' => 'create',
                'field_data' => $data,
            ]);
                $message = 'pre_success';
            } else {
                // Draft: auto-approve
                $data['approval_status'] = 'approved';
                $data['maker_id'] = auth()->id();
                $data['checker_id'] = auth()->id();
                $data['checked_at'] = now();
                $form->fields()->create($data);
                $message = 'post_success';
            }

        }

        return redirect()->route('forms.fields.create', $form->form_id)
                        ->with($message, 'Fields submitted successfully.');
    }

    public function approveField($id)
    {
        $change = FieldChange::findOrFail($id);

        if ($change->maker_id === auth()->id()) {
            return back()->with('error', 'You cannot approve your own change.');
        }

        if ($change->approval_status !== 'pending') {
            return back()->with('error', 'Already reviewed.');
        }

        $fieldData = $change->field_data;

        switch ($change->action) {
            case 'create':
                $field = FormFields::create(array_merge($fieldData, [
                    'form_id' => $change->form_id,
                    'maker_id' => $change->maker_id,
                    'checker_id' => auth()->id(),
                    'approval_status' => 'approved',
                    'checked_at' => now(),
                ]));
                $change->form_field_id = $field->id;
                break;

            case 'update':
                $change->field->update(array_merge($fieldData, [
                    'checker_id' => auth()->id(),
                    'approval_status' => 'approved',
                    'checked_at' => now(),
                ]));
                break;

            case 'delete':
                $change->field->delete();
                break;
        }

        $change->update([
            'approval_status' => 'approved',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        return back()->with('success', 'Change approved.');
    }

    public function rejectField($id)
    {
        $change = FieldChange::findOrFail($id);

        if ($change->maker_id === auth()->id()) {
            return back()->with('error', 'You cannot reject your own change.');
        }

        $change->update([
            'approval_status' => 'rejected',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        return back()->with('success', 'Change rejected.');
    }


   public function submitForApproval($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();

        if ($form->publish_status !== 'draft') {
            return redirect()->route('forms.create')->with('error_sa', 'Only draft forms can be submitted.');
        }

        $form->update([
            'publish_status' => 'pending_approval',
            'submitted_by' => auth()->id(),
        ]);

        return redirect()->route('forms.create')->with('success_sa', 'Form submitted for approval.');
    }

    public function approve($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();

        if ($form->publish_status !== 'pending_approval') {
            return redirect()->route('forms.create')->with('error_sa', 'Only pending forms can be approved.');
        }

        if ($form->submitted_by === auth()->id()) {
            return redirect()->route('forms.create')->with('error_sa', 'The maker cannot approve their own form.');
        }

        $form->update([
            'publish_status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('forms.create')->with('success_sa', 'Form approved successfully.');
    }


    // List all forms
    public function index()
    {
        $forms = Forms::with('fields')->latest()->get();

        return view('forms.index', [
            'formMode' => 'create',
            'forms' => $forms,
        ]);
    }

    // Show a single form with fields
    public function show($form_id)
    {
        $form = Forms::with('fields')->where('form_id', $form_id)->firstOrFail();
        return view('forms.show', compact('form'));
    }

    // Provide form JSON API
    public function api($form_id)
    {
        $form = Forms::with('fields')->where('form_id', $form_id)->firstOrFail();
        return response()->json($form);
    }

    // Edit form and load into view
    public function edit($form_id)
{
    $form = Forms::where('form_id', $form_id)->firstOrFail();
    $forms = Forms::latest()->get();
    $formId = $form->form_id; // keep for consistency
    $facilities = Facility::orderBy('id')->pluck('name', 'id');

    return view('forms.create', [ // or 'forms.index' depending on your structure
        'form' => $form,
        'formId' => $formId,
        'forms' => $forms,
        'formMode' => 'edit',
        'facilities' => $facilities
    ]);
}

   public function update(Request $request, $form_id)
{
    $form = Forms::where('form_id', $form_id)->firstOrFail();

    $request->validate([
        'name' => 'required|string|max:255',
        'formSubmissionUrl' => 'required|url',
        'facility_id' => 'nullable|exists:facility,id',
        // Remove 'fields' validation if you're not updating them here
    ]);

    $form->update([
        'name' => $request->name,
        'form_submission_url' => $request->formSubmissionUrl,
        'facility_id' => $request->facility_id,
    ]);

    return redirect()->route('forms.create')->with('success', 'Form updated successfully.');
}


    // Delete a form and its fields
    public function destroy($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();

        $form->fields()->delete();
        $form->delete();

        return redirect()->route('forms.create')->with('success', 'Form deleted successfully.');
    }
}
