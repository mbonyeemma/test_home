<?php

namespace App\Http\Controllers;

use App\FieldChange;
use App\FormFields;
use App\Forms;
use App\Models\Facility;
use Illuminate\Http\Request;

class FormController extends Controller
{
    // Predefined color palette for forms
    private $formColors = [
        '#3498db', // Blue
        '#e74c3c', // Red
        '#2ecc71', // Green
        '#f39c12', // Orange
        '#9b59b6', // Purple
        '#1abc9c', // Turquoise
        '#e67e22', // Carrot
        '#34495e', // Dark Blue
        '#16a085', // Dark Turquoise
        '#8e44ad', // Dark Purple
        '#27ae60', // Dark Green
        '#c0392b', // Dark Red
    ];

    // Show form creation page with listing
    public function create()
    {
        $formId = 'FORM-' . strtoupper(uniqid());
        $forms = Forms::with('facility')->latest()->get();
        $facilities = Facility::orderBy('id')->pluck('name', 'id');
        $colors = $this->formColors;
       
        return view('forms.create', compact('formId', 'forms','facilities', 'colors'))->with('formMode', 'create');
    }

    // Store new form
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'form_id' => 'required|string|max:255|unique:forms,form_id',
            'formSubmissionUrl' => 'required|url',
            'facility_id' => 'nullable|exists:facility,id',
            'color' => 'nullable|string|max:7',
        ]);

        // Assign random color if not provided
        $color = $request->color ?: $this->formColors[array_rand($this->formColors)];

        Forms::create([
            'name' => $request->name,
            'form_id' => $request->form_id,
            'form_submission_url' => $request->formSubmissionUrl,
            'facility_id' => $request->facility_id,
            // 'color' => $color, // Temporarily commented out until database column is added
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

            // Create field change request for approval
            FieldChange::create([
                'form_field_id' => null, // Will be set after approval
                'form_id' => $form->id,
                'maker_id' => auth()->id(),
                'action' => 'create',
                'field_data' => $data,
                'approval_status' => 'pending',
            ]);
        }

        return redirect()->route('forms.fields.create', $form->form_id)
                         ->with('success', 'Field changes submitted for approval.');
    }

    public function approveField($id)
    {
        $fieldChange = FieldChange::findOrFail($id);
        
        if ($fieldChange->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Field change already processed.');
        }

        $fieldChange->update([
            'approval_status' => 'approved',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        // Create the actual field
        $form = Forms::findOrFail($fieldChange->form_id);
        $field = $form->fields()->create($fieldChange->field_data);
        
        // Update the field_change with the created field ID
        $fieldChange->update(['form_field_id' => $field->id]);

        return redirect()->back()->with('success', 'Field approved and created successfully.');
    }

    public function rejectField($id)
    {
        $fieldChange = FieldChange::findOrFail($id);
        
        if ($fieldChange->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Field change already processed.');
        }

        $fieldChange->update([
            'approval_status' => 'rejected',
            'checker_id' => auth()->id(),
            'checked_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Field change rejected.');
    }

    public function submitForApproval($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        
        $form->update([
            'publish_status' => 'pending',
            'submitted_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Form submitted for approval.');
    }

    public function approve($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        
        if ($form->publish_status !== 'pending') {
            return redirect()->back()->with('error', 'Form is not pending approval.');
        }

        $form->update([
            'publish_status' => 'published',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Form approved and published.');
    }

    public function index()
    {
        $forms = Forms::with('facility')->latest()->get();
        return view('forms.index', compact('forms'));
    }

    public function show($form_id)
    {
        $form = Forms::where('form_id', $form_id)->with('fields')->firstOrFail();
        return view('forms.show', compact('form'));
    }

    public function api($form_id)
    {
        $form = Forms::where('form_id', $form_id)->with('fields')->firstOrFail();
        return response()->json($form);
    }

    public function edit($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        $facilities = Facility::orderBy('id')->pluck('name', 'id');
        $colors = $this->formColors;
        return view('forms.edit', compact('form', 'facilities', 'colors'));
    }

    public function update(Request $request, $form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'formSubmissionUrl' => 'required|url',
            'facility_id' => 'nullable|exists:facility,id',
            'color' => 'nullable|string|max:7',
        ]);

        $form->update([
            'name' => $request->name,
            'form_submission_url' => $request->formSubmissionUrl,
            'facility_id' => $request->facility_id,
            'color' => $request->color ?: $form->color,
        ]);

        return redirect()->route('forms.create')->with('success', 'Form updated successfully.');
    }

    public function destroy($form_id)
    {
        $form = Forms::where('form_id', $form_id)->firstOrFail();
        $form->delete();

        return redirect()->route('forms.create')->with('success', 'Form deleted successfully.');
    }

    // Get available colors for forms
    public function getColors()
    {
        return response()->json($this->formColors);
    }
}
