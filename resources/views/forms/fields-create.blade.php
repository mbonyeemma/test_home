@extends('layouts.app')

@section('title', '| Add Fields')

@section('content')
    <div class="container">
        <h2 class="page-header">Add Fields to Form: <strong>{{ $form->name }}</strong> ({{ $form->form_id }})</h2>

        {{-- Success Messages --}}
        @if (session('pre_success'))
            <div class="alert alert-success">
                Draft field created. Proceed to approve via
                <a href="{{ route('field-changes.index') }}" class="text-primary">Pending Field Changes</a>.
            </div>
        @elseif (session('post_success'))
            <div class="alert alert-success">{{ session('post_success') }}</div>
        @endif

        {{-- Add New Fields --}}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Add New Fields</strong>
            </div>
            <div class="panel-body">
                <form action="{{ route('forms.fields.store', $form->form_id) }}" method="POST" class="form-horizontal">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="fieldsTable">
                            <thead>
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

                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success" onclick="addField()">+ Add Field</button>
                            <button type="submit" class="btn btn-primary">Save Fields</button>
                            <a href="{{ route('field-changes.index') }}" class="btn btn-default pull-right">
                                Pending Field Changes -
                                @if (isset($pendingCount) && $pendingCount > 0)
                                    <span class="badge badge-danger">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Existing Fields --}}
        <div class="panel panel-info">
            <div class="panel-heading">
                <strong>Existing Fields</strong>
            </div>
            <div class="panel-body">
                @if ($form->fields->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Label</th>
                                    <th>Name</th>
                                    <th>Default</th>
                                    <th>Options</th>
                                    <th>Option</th>
                                    <th>Status</th>
                                    <th>Approval Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($form->fields as $field)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($field->field_type) }}</td>
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
                                        <td>{{ ucfirst($field->approval_status) }}</td>
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

    {{-- JavaScript --}}
    <script>
        var counter = 0;

        function addField() {
            var html = `
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
            <td><input name="fields[${counter}][field_value]" class="form-control" placeholder="Default Value"></td>
            <td><input name="fields[${counter}][dropdown_options]" class="form-control" placeholder="Comma-separated options"></td>
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

        function removeField(button) {
            button.closest('tr').remove();
        }
    </script>
@endsection
