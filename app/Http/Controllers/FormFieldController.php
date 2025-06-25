<?
namespace App\Http\Controllers;

use App\FormFields;
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
    public function update(Request $request, $id)
    {
        $field = FormFields::findOrFail($id);

        $request->validate([
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:input,dropdown,checkbox',
            'option' => 'nullable|in:mandatory,optional',
            'status' => 'nullable|in:enabled,disabled',
        ]);

        $field->update([
            'field_type' => $request->field_type,
            'field_name' => $request->field_name,
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

    // Delete a specific field
    public function destroy($id)
    {
        $field = FormFields::findOrFail($id);
        $formId = $field->forms->form_id;

        $field->delete();

        return redirect()
            ->route('forms.fields.create', $formId)
            ->with('success', 'Field deleted successfully.');
    }
}
