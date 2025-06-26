<?php

namespace App\Http\Controllers;

use App\Forms;
use Illuminate\Http\Request;

class FormController extends Controller
{
    // Show form creation page with listing
    public function create()
    {
        $formId = 'FORM-' . strtoupper(uniqid());
        $forms = Forms::latest()->get();

        return view('forms.create', compact('formId', 'forms'))->with('formMode', 'create');
    }

    // Store new form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'form_id' => 'required|string|max:255|unique:forms,form_id',
            'formSubmissionUrl' => 'required|url',
        ]);

        Forms::create([
            'name' => $request->name,
            'form_id' => $request->form_id,
            'form_submission_url' => $request->formSubmissionUrl,
        ]);

        return redirect()->route('forms.create')->with('success', 'Form created successfully.');
    }

    // Show UI for adding fields to a form
    public function createFields($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        return view('forms.fields-create', compact('form'));
    }

    // Store fields for a form
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

    return view('forms.create', [ // or 'forms.index' depending on your structure
        'form' => $form,
        'formId' => $formId,
        'forms' => $forms,
        'formMode' => 'edit'
    ]);
}

   public function update(Request $request, $form_id)
{
    $form = Forms::where('form_id', $form_id)->firstOrFail();

    $request->validate([
        'name' => 'required|string|max:255',
        'formSubmissionUrl' => 'required|url',
        // Remove 'fields' validation if you're not updating them here
    ]);

    $form->update([
        'name' => $request->name,
        'form_submission_url' => $request->formSubmissionUrl,
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
