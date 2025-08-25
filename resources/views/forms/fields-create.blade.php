@extends('layouts.app')

@section('title', '| Add Fields')

@section('content')
    <div class="container">
        <div class="page-header" style="border-left: 4px solid {{ $form->color ?? '#3498db' }}; padding-left: 15px;">
            <h2>Add Fields to Form: <strong>{{ $form->name }}</strong> ({{ $form->form_id }})</h2>
        </div>

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
        <div class="panel panel-primary" style="border-color: {{ $form->color ?? '#3498db' }};">
            <div class="panel-heading" style="background-color: {{ $form->color ?? '#3498db' }}; border-color: {{ $form->color ?? '#3498db' }};">
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
                            <button type="submit" class="btn btn-primary" style="background-color: {{ $form->color ?? '#3498db' }}; border-color: {{ $form->color ?? '#3498db' }};">Save Fields</button>
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
        <div class="panel panel-info" style="border-color: {{ $form->color ?? '#3498db' }};">
            <div class="panel-heading" style="background-color: {{ $form->color ?? '#3498db' }}; border-color: {{ $form->color ?? '#3498db' }}; color: white;">
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
                                        <td>
                                            @if ($field->approval_status === 'approved')
                                                <span class="label label-success">Approved</span>
                                            @elseif ($field->approval_status === 'pending')
                                                <span class="label label-warning">Pending</span>
                                            @else
                                                <span class="label label-default">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-xs">Edit</a>
                                            <a href="#" class="btn btn-danger btn-xs">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No fields added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        let fieldCount = 0;

        function addField() {
            const tbody = document.getElementById('fields');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="fields[${fieldCount}][field_type]" class="form-control" onchange="toggleDropdownOptions(${fieldCount})">
                        <option value="input">Input</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="fields[${fieldCount}][field_label]" class="form-control" placeholder="Field Label" required>
                </td>
                <td>
                    <input type="text" name="fields[${fieldCount}][name]" class="form-control" placeholder="Field Name" required>
                </td>
                <td>
                    <input type="text" name="fields[${fieldCount}][field_value]" class="form-control" placeholder="Default Value">
                </td>
                <td>
                    <input type="text" name="fields[${fieldCount}][dropdown_options]" class="form-control dropdown-options-${fieldCount}" placeholder="Comma-separated options" style="display: none;">
                </td>
                <td>
                    <select name="fields[${fieldCount}][option]" class="form-control">
                        <option value="optional">Optional</option>
                        <option value="mandatory">Mandatory</option>
                    </select>
                </td>
                <td>
                    <select name="fields[${fieldCount}][status]" class="form-control">
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-xs" onclick="removeField(this)">Remove</button>
                </td>
            `;
            tbody.appendChild(row);
            fieldCount++;
        }

        function removeField(button) {
            button.closest('tr').remove();
        }

        function toggleDropdownOptions(fieldIndex) {
            const fieldType = document.querySelector(`select[name="fields[${fieldIndex}][field_type]"]`).value;
            const dropdownOptions = document.querySelector(`.dropdown-options-${fieldIndex}`);
            
            if (fieldType === 'dropdown') {
                dropdownOptions.style.display = 'block';
            } else {
                dropdownOptions.style.display = 'none';
            }
        }

        // Add initial field
        document.addEventListener('DOMContentLoaded', function() {
            addField();
        });
    </script>

    <style>
        .page-header {
            background: linear-gradient(135deg, {{ $form->color ?? '#3498db' }}20, {{ $form->color ?? '#3498db' }}10);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .panel-primary > .panel-heading {
            color: white;
        }
        
        .btn-primary:hover {
            background-color: {{ $form->color ?? '#3498db' }}dd !important;
            border-color: {{ $form->color ?? '#3498db' }}dd !important;
        }
    </style>
@endsection
