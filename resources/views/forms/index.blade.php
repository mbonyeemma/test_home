@extends('layouts.app')

@section('title', $formMode == 'edit' ? '| Edit Form' : '| Create New Form')

@section('content')
    <div class="container-fluid" style="margin-left: 5%; margin-right: 5%;">
        <h2>{{ $formMode == 'edit' ? 'Edit Form' : 'Create New Form' }}</h2>

        <form action="{{ $formMode == 'edit' ? route('forms.update', $form->form_id) : route('forms.store') }}" method="POST"
            class="form-horizontal">
            @csrf
            @if ($formMode == 'edit')
                {{ method_field('PUT') }}
            @endif

            <div class="form-group">
                <label>Form Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $form->name ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Form ID (unique):</label>
                <input type="text" name="form_id" class="form-control" value="{{ $form->form_id ?? '' }}"
                    {{ $formMode == 'edit' ? 'readonly' : 'required' }}>
            </div>

            <h4>{{ $formMode == 'edit' ? 'Edit Fields' : 'Add Fields' }}</h4>
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
                            @if ($formMode != 'edit')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="fields">
                        @if ($formMode == 'edit')
                            @foreach ($form->fields as $index => $field)
                                <tr>
                                    <td>
                                        <select name="fields[{{ $index }}][field_type]" class="form-control">
                                            <option value="input" {{ $field->field_type == 'input' ? 'selected' : '' }}>
                                                Input</option>
                                            <option value="dropdown"
                                                {{ $field->field_type == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                            <option value="checkbox"
                                                {{ $field->field_type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="fields[{{ $index }}][field_name]"
                                            class="form-control" value="{{ $field->field_name }}"></td>
                                    <td><input type="text" name="fields[{{ $index }}][field_value]"
                                            class="form-control" value="{{ $field->field_value }}"></td>
                                    <td><input type="text" name="fields[{{ $index }}][dropdown_options]"
                                            class="form-control"
                                            value="{{ is_array($field->dropdown_options) ? implode(',', $field->dropdown_options) : $field->dropdown_options }}">
                                    </td>
                                    <td>
                                        <select name="fields[{{ $index }}][option]" class="form-control">
                                            <option value="mandatory"
                                                {{ $field->option == 'mandatory' ? 'selected' : '' }}>Mandatory</option>
                                            <option value="optional" {{ $field->option == 'optional' ? 'selected' : '' }}>
                                                Optional</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="fields[{{ $index }}][status]" class="form-control">
                                            <option value="enabled" {{ $field->status == 'enabled' ? 'selected' : '' }}>
                                                Enabled</option>
                                            <option value="disabled" {{ $field->status == 'disabled' ? 'selected' : '' }}>
                                                Disabled</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @if ($formMode != 'edit')
                <button type="button" class="btn btn-success" onclick="addField()">+ Add Field</button>
            @endif

            <button type="submit" class="btn btn-primary">{{ $formMode == 'edit' ? 'Update Form' : 'Save Form' }}</button>
        </form>

        <hr>

        <h3>All Created Forms</h3>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($forms->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Form Name</th>
                            <th>Form ID</th>
                            <th>Fields</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $formItem)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $formItem->name }}</td>
                                <td>{{ $formItem->form_id }}</td>
                                <td>{{ $formItem->fields->count() }}</td>
                                <td>{{ $formItem->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('forms.show', $formItem->form_id) }}"
                                        class="btn btn-info btn-xs">View</a>
                                    <a href="{{ route('forms.edit', $formItem->form_id) }}"
                                        class="btn btn-warning btn-xs">Edit</a>
                                    <form method="POST" action="{{ route('forms.destroy', $formItem->form_id) }}"
                                        style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
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
            <p>No forms found.</p>
        @endif
    </div>

    @if ($formMode != 'edit')
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
            <td><input type="text" name="fields[${counter}][field_name]" class="form-control" placeholder="Field Name"></td>
            <td><input type="text" name="fields[${counter}][field_value]" class="form-control" placeholder="Default Value"></td>
            <td><input type="text" name="fields[${counter}][dropdown_options]" class="form-control" placeholder="Comma-separated options"></td>
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

            function removeField(btn) {
                btn.closest('tr').remove();
            }
        </script>
    @endif

@endsection
