@extends('layouts.app')

@section('title', '| Create New Form')

@section('content')
<div class="container-fluid" style="margin-left: 10%; margin-right: 10%;">
    <h2>Create New Form</h2>
    <form action="{{ route('forms.store') }}" method="POST" class="">
        @csrf

        <div class="form-group">
            <label>Form Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Form ID (unique):</label>
            <input type="text" name="form_id" class="form-control" required>
        </div>

        <h4>Add Fields</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="fieldsTable">
                <thead>
                    <tr>
                        <th>Field Type</th>
                        <th>Field Name</th>
                        <th>Default Value</th>
                        <th>Dropdown Options</th>
                        <th>Option</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="fields">
                    <!-- JS will append field rows here -->
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-success" onclick="addField()">+ Add Field</button>
            <button type="submit" class="btn btn-primary">Save Form</button>
        </div>
    </form>
</div>

<script>
let counter = 0;
function addField() {
    const fieldHtml = `
        <tr>
            <td>
                <select name="fields[${counter}][field_type]" class="form-control">
                    <option value="input">Input</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="checkbox">Checkbox</option>
                </select>
            </td>
            <td>
                <input type="text" name="fields[${counter}][field_name]" class="form-control" placeholder="Field Name">
            </td>
            <td>
                <input type="text" name="fields[${counter}][field_value]" class="form-control" placeholder="Default Value">
            </td>
            <td>
                <input type="text" name="fields[${counter}][dropdown_options]" class="form-control" placeholder="Comma-separated options for dropdown">
            </td>
            <td>
                <select name="fields[${counter}][option]" class="form-control">
                    <option value="mandatory">Mandatory</option>
                    <option value="optional">Optional</option>
                </select>
            </td>
            <td>
                <select name="fields[${counter}][status]" class="form-control">
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)">Remove</button>
            </td>
        </tr>`;
    document.getElementById('fields').insertAdjacentHTML('beforeend', fieldHtml);
    counter++;
}

function removeField(button) {
    button.closest('tr').remove();
}
</script>
@endsection
