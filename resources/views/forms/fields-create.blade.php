@extends('layouts.app')

@section('title', '| Add Fields')

@section('content')
    <div class="container">
        <h2>Add Fields to Form: <strong>{{ $form->name }}</strong> ({{ $form->form_id }})</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add New Fields --}}
        <form action="{{ route('forms.fields.store', $form->form_id) }}" method="POST">
            @csrf

            <table class="table table-bordered" id="fieldsTable">
                <thead>
                    <tr>
                        <th>Field Type</th>
                        <th>Name</th>
                        <th>Default Value</th>
                        <th>Dropdown Options</th>
                        <th>Option</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="fields"></tbody>
            </table>

            <button type="button" class="btn btn-success" onclick="addField()">+ Add Field</button>
            <button type="submit" class="btn btn-primary">Save Fields</button>
        </form>

        <hr>

        {{-- Existing Fields --}}
        <h3>Existing Fields</h3>
        @if ($form->fields->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Default</th>
                        <th>Options</th>
                        <th>Option</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->fields as $field)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $field->field_type }}</td>
                            <td>{{ $field->field_name }}</td>
                            <td>{{ $field->field_value }}</td>
                            <td>
                                @if (is_array($field->dropdown_options))
                                    {{ implode(',', $field->dropdown_options) }}
                                @else
                                    {{ $field->dropdown_options }}
                                @endif
                            </td>
                            <td>{{ ucfirst($field->option) }}</td>
                            <td>{{ ucfirst($field->status) }}</td>
                            <td>
                                <a href="{{ route('form-fields.edit', $field->id) }}"
                                    class="btn btn-warning btn-xs">Edit</a>
                                <form action="{{ route('form-fields.destroy', $field->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Delete this field?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No fields found for this form.</p>
        @endif
    </div>

    <script>
        let counter = 0;

        function addField() {
            const html = `
        <tr>
            <td>
                <select name="fields[${counter}][field_type]" class="form-control">
                    <option value="input">Input</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="checkbox">Checkbox</option>
                </select>
            </td>
            <td><input name="fields[${counter}][field_name]" class="form-control" required></td>
            <td><input name="fields[${counter}][field_value]" class="form-control"></td>
            <td><input name="fields[${counter}][dropdown_options]" class="form-control" placeholder="Comma-separated"></td>
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
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)">Remove</button></td>
        </tr>`;
            document.getElementById('fields').insertAdjacentHTML('beforeend', html);
            counter++;
        }

        function removeField(btn) {
            btn.closest('tr').remove();
        }
    </script>
@endsection
