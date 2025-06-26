@extends('layouts.app')

@section('title', '| Add Fields')

@section('content')
    <div class="container">
        <h2 class="mb-4">Add Fields to Form: <strong>{{ $form->name }}</strong> ({{ $form->form_id }})</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Card for Adding New Fields --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Add New Fields</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('forms.fields.store', $form->form_id) }}" method="POST">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered" id="fieldsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Field Type</th>
                                    <th>Label</th>
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
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-success" onclick="addField()">+ Add Field</button>
                        <button type="submit" class="btn btn-primary">Save Fields</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card for Existing Fields --}}
        <div class="card">
            <div class="card-header bg-info text-white">
                <strong>Existing Fields</strong>
            </div>
            <div class="card-body">
                @if ($form->fields->count())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Label</th>
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
                                        <td>{{ $field->field_label }}</td>
                                        <td>{{ $field->name }}</td>
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
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Delete this field?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No fields found for this form.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- JavaScript for adding/removing fields --}}
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
                <td><input name="fields[${counter}][field_label]" class="form-control" required placeholder="Field Label"></td>
                <td><input name="fields[${counter}][name]" class="form-control" required placeholder="Field Name"></td>
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
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)">Remove</button>
                </td>
            </tr>`;
            document.getElementById('fields').insertAdjacentHTML('beforeend', html);
            counter++;
        }

        function removeField(btn) {
            btn.closest('tr').remove();
        }
    </script>
@endsection
