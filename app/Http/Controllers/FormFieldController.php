<?
namespace App\Http\Controllers;

use App\FieldChange;
use App\FormFields;
use App\Forms;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    // Show edit form for a field
    public function edit($id)
    {
        $field = FormFields::with('forms')->findOrFail($id);
        return view('form_fields.edit', compact('field'));
    }

    // Update a specific field
    public function update_bk(Request $request, $id)
    {
        $field = FormFields::findOrFail($id);
        $form = Forms::where('form_id', $field->forms->form_id)->firstOrFail();

        $request->validate([
            'field_label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'field_type' => 'required|in:input,dropdown,checkbox',
            'option' => 'nullable|in:mandatory,optional',
            'status' => 'nullable|in:enabled,disabled',
        ]);

        if ($form->publish_status === 'approved') {

        }

        $field->update([
            'field_type' => $request->field_type,
            'field_label' => $request->field_label,
            'name' => $request->name,
            'field_value' => $request->field_value,
            'option' => $request->option ?? 'optional',
            'status' => $request->status ?? 'enabled',
            'dropdown_options' => $request->field_type === 'dropdown'
                ? explode(',', $request->dropdown_options ?? '')
                : null,
        ]);

        return redirect()
            ->route('forms.fields.create', $field->forms->form_id)
            ->with('success', 'Field updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $field = FormFields::findOrFail($id);
        $form = $field->forms; // Use relation instead of fetching again

        $request->validate([
            'field_label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'field_type' => 'required|in:input,dropdown,checkbox',
            'option' => 'nullable|in:mandatory,optional',
            'status' => 'nullable|in:enabled,disabled',
        ]);

        // Gather field data
        $fieldData = [
            'field_type' => $request->field_type,
            'field_label' => $request->field_label,
            'name' => $request->name,
            'field_value' => $request->field_value ?? null,
            'option' => $request->option ?? 'optional',
            'status' => $request->status ?? 'enabled',
            'dropdown_options' => $request->field_type === 'dropdown'
                ? explode(',', $request->dropdown_options ?? '')
                : null,
        ];

        // Check if Maker-Checker is needed
        if ($form->publish_status === 'approved') {
            // Create pending update request
            FieldChange::create([
                'form_field_id' => $field->id,
                'form_id' => $form->id,
                'maker_id' => auth()->id(),
                'action' => 'update',
                'field_data' => $fieldData,
                'approval_status' => 'pending',
            ]);

            return redirect()
                ->route('forms.fields.create', $form->form_id)
                ->with('success', 'Field update submitted for approval.');
        }

        // Direct update for draft
        $field->update(array_merge($fieldData, [
            'checker_id' => auth()->id(),
            'approval_status' => 'approved',
            'checked_at' => now(),
        ]));

        return redirect()
            ->route('forms.fields.create', $form->form_id)
            ->with('success', 'Field updated successfully.');
    }


    // Delete a specific field
    public function destroy_bk($id)
    {
        $field = FormFields::findOrFail($id);
        $formId = $field->forms->form_id;

        $field->delete();

        return redirect()
            ->route('forms.fields.create', $formId)
            ->with('success', 'Field deleted successfully.');
    }

    public function destroy($id)
    {
        $field = FormFields::findOrFail($id);
        $form = $field->forms; // Assuming relationship exists: form()

        if ($form->publish_status === 'approved') {
            // Form is published — use Maker-Checker flow

            // Create a pending deletion request
            FieldChange::create([
                'form_field_id' => $field->id,
                'form_id' => $form->id,
                'maker_id' => auth()->id(),
                'action' => 'delete',
                'field_data' => $field->toArray(), // store current field snapshot
                'approval_status' => 'pending',
            ]);

            return redirect()
                ->route('forms.fields.create', $form->form_id)
                ->with('success', 'Field deletion submitted for approval.');
        }

        // Form is in draft — delete immediately
        $formId = $form->form_id;
        $field->delete();

        return redirect()
            ->route('forms.fields.create', $formId)
            ->with('success', 'Field deleted successfully.');
    }

}
