@extends('layouts.app')

@section('title', '| Edit Field')

@section('content')
    <div class="container">
        <h2>Edit Field for Form: <strong>{{ $field->forms->name }}</strong> ({{ $field->forms->form_id }})</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Validation Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('form-fields.update', $field->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Field Type:</label>
                <select name="field_type" class="form-control" required>
                    <option value="input" {{ $field->field_type === 'input' ? 'selected' : '' }}>Input</option>
                    <option value="dropdown" {{ $field->field_type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                    <option value="checkbox" {{ $field->field_type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                </select>
            </div>

            <div class="form-group">
                <label>Field Name:</label>
                <input type="text" name="field_name" class="form-control" value="{{ $field->field_name }}" required>
            </div>

            <div class="form-group">
                <label>Default Value:</label>
                <input type="text" name="field_value" class="form-control" value="{{ $field->field_value }}">
            </div>

            <div class="form-group">
                <label>Dropdown Options (comma-separated):</label>
                <input type="text" name="dropdown_options" class="form-control"
                    value="{{ is_array($field->dropdown_options) ? implode(',', $field->dropdown_options) : $field->dropdown_options }}">
            </div>

            <div class="form-group">
                <label>Option:</label>
                <select name="option" class="form-control">
                    <option value="mandatory" {{ $field->option === 'mandatory' ? 'selected' : '' }}>Mandatory</option>
                    <option value="optional" {{ $field->option === 'optional' ? 'selected' : '' }}>Optional</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="enabled" {{ $field->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                    <option value="disabled" {{ $field->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Field</button>
            <a href="{{ route('forms.fields.create', $field->forms->form_id) }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
