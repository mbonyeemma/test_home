<?php

namespace App\Http\Controllers;

use App\Forms;
use Illuminate\Http\Request;

class FormController extends Controller
{
    // Show create form UI
    public function create()
    {
        return view('forms.create');
    }

    // Store new form and fields
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'form_id' => 'required|string|max:255|unique:forms,form_id',
            'fields' => 'required|array|min:1',
        ]);

        $form = Forms::create([
            'name' => $request->name,
            'form_id' => $request->form_id,
        ]);

        foreach ($request->fields as $field) {
            $form->fields()->create([
                'field_type' => $field['field_type'],
                'field_name' => $field['field_name'],
                'field_value' => $field['field_value'] ?? null,
                'option' => $field['option'] ?? 'optional',
                'status' => $field['status'] ?? 'enabled',
                'dropdown_options' => $field['field_type'] === 'dropdown'
                    ? explode(',', $field['dropdown_options'] ?? '')
                    : null,
            ]);
        }

        return redirect()->route('forms.create')->with('success', 'Form created successfully.');
    }

    // Show all forms
public function index()
{
    $forms = Forms::latest()->get(); // or ->paginate(10)
    return view('forms.index', compact('forms'));
}

    // Show a single form and its fields
    public function show($form_id)
    {
        $form = Forms::with('fields')->where('form_id', $form_id)->firstOrFail();
        return view('forms.show', compact('form'));
    }

    // API endpoint for external systems to get form JSON
    public function api($form_id)
    {
        $form = Forms::with('fields')->where('form_id', $form_id)->firstOrFail();
        return response()->json($form);
    }

   public function edit($form_id)
{
    $form = Forms::with('fields')->where('form_id', $form_id)->firstOrFail();
    return view('forms.edit', compact('form'));
}

public function update(Request $request, $form_id)
{
    $form = Forms::where('form_id', $form_id)->firstOrFail();

    $request->validate([
        'name' => 'required|string|max:255',
        'fields' => 'required|array|min:1',
    ]);

    $form->update([
        'name' => $request->name,
    ]);

    // Delete old fields and re-insert updated
    $form->fields()->delete();

    foreach ($request->fields as $field) {
        $form->fields()->create([
            'field_type' => $field['field_type'],
            'field_name' => $field['field_name'],
            'field_value' => $field['field_value'] ?? null,
            'option' => $field['option'] ?? 'optional',
            'status' => $field['status'] ?? 'enabled',
            'dropdown_options' => $field['field_type'] === 'dropdown'
                ? explode(',', $field['dropdown_options'] ?? '')
                : null,
        ]);
    }

    return redirect()->route('forms.index')->with('success', 'Form updated successfully.');
}


// Delete the form
public function destroy($form_id)
{
    $form = Forms::where('form_id', $form_id)->firstOrFail();

    // Delete related fields
    $form->fields()->delete();

    // Delete form
    $form->delete();

    return redirect()->route('forms.index')
        ->with('success', 'Form deleted successfully.');
}
}
