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
                <label for="field_type">Field Type:</label>
                <select name="field_type" id="field_type" class="form-control" required>
                    <option value="input" {{ old('field_type', $field->field_type) === 'input' ? 'selected' : '' }}>Input
                    </option>
                    <option value="dropdown" {{ old('field_type', $field->field_type) === 'dropdown' ? 'selected' : '' }}>
                        Dropdown</option>
                    <option value="checkbox" {{ old('field_type', $field->field_type) === 'checkbox' ? 'selected' : '' }}>
                        Checkbox</option>
                </select>
            </div>

            <div class="form-group">
                <label for="field_label">Field Label:</label>
                <input type="text" name="field_label" id="field_label" class="form-control"
                    value="{{ old('field_label', $field->field_label ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="name">Field Name:</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $field->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="field_value">Default Value:</label>
                <input type="text" name="field_value" id="field_value" class="form-control"
                    value="{{ old('field_value', $field->field_value ?? '') }}">
            </div>

            <div class="form-group" id="dropdown-options-group"
                style="{{ old('field_type', $field->field_type) !== 'dropdown' ? 'display:none;' : '' }}">
                <label for="dropdown_options">Dropdown Options (comma-separated):</label>
                <input type="text" name="dropdown_options" id="dropdown_options" class="form-control"
                    value="{{ old('dropdown_options', is_array($field->dropdown_options) ? implode(',', $field->dropdown_options) : $field->dropdown_options) }}">
            </div>

            <div class="form-group">
                <label for="option">Option:</label>
                <select name="option" id="option" class="form-control">
                    <option value="mandatory" {{ old('option', $field->option) === 'mandatory' ? 'selected' : '' }}>
                        Mandatory</option>
                    <option value="optional" {{ old('option', $field->option) === 'optional' ? 'selected' : '' }}>Optional
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="enabled" {{ old('status', $field->status) === 'enabled' ? 'selected' : '' }}>Enabled
                    </option>
                    <option value="disabled" {{ old('status', $field->status) === 'disabled' ? 'selected' : '' }}>Disabled
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Field</button>
            <a href="{{ route('forms.fields.create', $field->forms->form_id) }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fieldTypeSelect = document.getElementById('field_type');
            const dropdownOptionsGroup = document.getElementById('dropdown-options-group');

            fieldTypeSelect.addEventListener('change', function() {
                if (this.value === 'dropdown') {
                    dropdownOptionsGroup.style.display = 'block';
                } else {
                    dropdownOptionsGroup.style.display = 'none';
                }
            });
        });
    </script>
@endpush
