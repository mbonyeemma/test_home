@extends('layouts.app')

@section('title', '| Edit Form')

@section('content')
<div class="container">
    <h2>Edit Form</h2>

    <form action="{{ route('forms.update', $form->form_id) }}" method="POST" class="form-horizontal">
        @csrf
        {{ method_field('PUT') }}

        <div class="form-group">
            <label class="col-sm-2 control-label">Form Name:</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{{ $form->name }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Form ID (unique):</label>
            <div class="col-sm-10">
                <input type="text" name="form_id" class="form-control" value="{{ $form->form_id }}" readonly>
            </div>
        </div>

        <h4>Edit Fields</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Field Type</th>
                        <th>Field Name</th>
                        <th>Default Value</th>
                        <th>Dropdown Options</th>
                        <th>Option</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->fields as $index => $field)
                    <tr>
                        <td>
                            <select name="fields[{{ $index }}][field_type]" class="form-control">
                                <option value="input" {{ $field->field_type == 'input' ? 'selected' : '' }}>Input</option>
                                <option value="dropdown" {{ $field->field_type == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                <option value="checkbox" {{ $field->field_type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="fields[{{ $index }}][field_name]" class="form-control" value="{{ $field->field_name }}" placeholder="Field Name">
                        </td>
                        <td>
                            <input type="text" name="fields[{{ $index }}][field_value]" class="form-control" value="{{ $field->field_value }}" placeholder="Default Value">
                        </td>
                        <td>
                            <input type="text" name="fields[{{ $index }}][dropdown_options]" class="form-control"
                                   placeholder="Comma-separated options"
                                   value="{{ is_array($field->dropdown_options) ? implode(',', $field->dropdown_options) : $field->dropdown_options }}">
                        </td>
                        <td>
                            <select name="fields[{{ $index }}][option]" class="form-control">
                                <option value="mandatory" {{ $field->option == 'mandatory' ? 'selected' : '' }}>Mandatory</option>
                                <option value="optional" {{ $field->option == 'optional' ? 'selected' : '' }}>Optional</option>
                            </select>
                        </td>
                        <td>
                            <select name="fields[{{ $index }}][status]" class="form-control">
                                <option value="enabled" {{ $field->status == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                <option value="disabled" {{ $field->status == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Update Form</button>
            </div>
        </div>
    </form>
</div>
@endsection
