@extends('layouts.app')

@section('title', 'Forms')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Left column: Form creation -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ isset($formMode) && $formMode === 'edit' ? 'Edit Form' : 'Create New Form' }}</strong>
                    </div>
                    <div class="panel-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form
                            action="{{ isset($formMode) && $formMode === 'edit' ? route('forms.update', $form->form_id) : route('forms.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($formMode) && $formMode === 'edit')
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="name">Form Name:</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $form->name ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="facility_id">Facility</label>
                                <select name="facility_id" id="facility_id" class="form-control">
                                    <option value="">-- Select Facility --</option>
                                    @foreach ($facilities as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ isset($form) && $form->facility_id == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="formSubmissionUrl">Form Submission URL:</label>
                                <input type="url" name="formSubmissionUrl" id="formSubmissionUrl" class="form-control"
                                    value="{{ old('formSubmissionUrl', $form->formSubmissionUrl ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="color">Form Color:</label>
                                <div class="color-picker">
                                    @foreach ($colors as $color)
                                        <div class="color-option">
                                            <input type="radio" name="color" id="color_{{ $loop->index }}" 
                                                   value="{{ $color }}" 
                                                   {{ (isset($form) && $form->color == $color) ? 'checked' : '' }}
                                                   {{ (!isset($form) && $loop->first) ? 'checked' : '' }}>
                                            <label for="color_{{ $loop->index }}" 
                                                   style="background-color: {{ $color }}; border: 2px solid {{ $color == '#ffffff' ? '#ccc' : $color }};">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Icon Picker -->
                            @include('components.icon-picker', ['selectedIcon' => $form->icon ?? 'file'])

                            <div class="form-group">
                                <label for="form_id">Form ID:</label>
                                <input type="text" name="form_id" id="form_id" class="form-control"
                                    value="{{ old('form_id', $form->form_id ?? $formId) }}"
                                    {{ isset($formMode) && $formMode === 'edit' ? 'readonly' : 'readonly' }}>
                            </div>

                            <button type="submit"
                                class="btn btn-{{ isset($formMode) && $formMode === 'edit' ? 'warning' : 'primary' }} btn-block">
                                {{ isset($formMode) && $formMode === 'edit' ? 'Update Form' : 'Create Form' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column: Form listing -->
            <div class="col-md-8">
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>All Created Forms</strong></div>
                    @if (session('success_sa'))
                        <div class="alert alert-success">{{ session('success_sa') }}</div>
                    @elseif (session('error_sa'))
                        <div class="alert alert-danger">{{ session('error_sa') }}</div>
                    @endif
                    <div class="panel-body">
                        @if ($forms->count())
                            <div class="row">
                                @foreach ($forms as $form)
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-card" style="border-left: 4px solid {{ $form->color ?? '#3498db' }};">
                                            <div class="form-card-header" style="background-color: {{ $form->color ?? '#3498db' }}; color: white;">
                                                <div class="form-header-content">
                                                    <div class="form-icon">
                                                        <i class="fa fa-{{ $form->icon ?? 'file' }} fa-2x"></i>
                                                    </div>
                                                    <div class="form-details">
                                                        <h4>{{ $form->name }}</h4>
                                                        <small>{{ $form->facility->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-card-body">
                                                <p><strong>Form ID:</strong> {{ $form->form_id }}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="label label-{{ $form->publish_status === 'published' ? 'success' : ($form->publish_status === 'pending' ? 'warning' : 'default') }}">
                                                        {{ ucfirst($form->publish_status) }}
                                                    </span>
                                                </p>
                                                <div class="form-card-actions">
                                                    <a href="{{ route('forms.fields.create', $form->form_id) }}"
                                                        class="btn btn-success btn-xs">
                                                        + Add Fields
                                                    </a>

                                                    @if ($form->publish_status === 'draft')
                                                        <form method="POST"
                                                            action="{{ route('forms.submitForApproval', $form->form_id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-info btn-xs">Submit for Approval</button>
                                                        </form>
                                                    @elseif($form->publish_status === 'pending')
                                                        <form method="POST"
                                                            action="{{ route('forms.approve', $form->form_id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-default btn-xs">Approve</button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('forms.edit', $form->form_id) }}"
                                                        class="btn btn-warning btn-xs">
                                                        Edit
                                                    </a>

                                                    <form action="{{ route('forms.destroy', $form->form_id) }}"
                                                        method="POST" style="display: inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this form?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No forms created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }
        
        .color-option {
            position: relative;
        }
        
        .color-option input[type="radio"] {
            display: none;
        }
        
        .color-option label {
            display: block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .color-option input[type="radio"]:checked + label {
            transform: scale(1.2);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.2);
        }
        
        .form-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .form-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .form-card-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .form-header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .form-icon {
            flex-shrink: 0;
        }
        
        .form-details {
            flex: 1;
        }
        
        .form-card-header h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .form-card-header small {
            opacity: 0.9;
        }
        
        .form-card-body {
            padding: 15px;
        }
        
        .form-card-body p {
            margin: 5px 0;
            font-size: 13px;
        }
        
        .form-card-actions {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .form-card-actions .btn {
            margin: 0;
        }
    </style>
@endsection
