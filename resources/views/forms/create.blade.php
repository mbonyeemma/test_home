@extends('layouts.app')

@section('title', 'Forms')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Left column: Form creation -->
            <div class="col-md-4">
                <div class="panel panel-default">
                    {{-- <div class="panel-heading"><strong>Create New Form</strong></div> --}}
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
                                <label for="formSubmissionUrl">Form Submission URL:</label>
                                <input type="url" name="formSubmissionUrl" id="formSubmissionUrl" class="form-control"
                                    value="{{ old('formSubmissionUrl', $form->formSubmissionUrl ?? '') }}" required>
                            </div>

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
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Form Name</th>
                                            <th>Url</th>
                                            <th>Form ID</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($forms as $form)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $form->name }}</td>
                                                <td>{{ $form->form_submission_url }}</td>
                                                <td>{{ $form->form_id }}</td>
                                                <td>{{ $form->publish_status }}</td>
                                                <td>
                                                    <a href="{{ route('forms.fields.create', $form->form_id) }}"
                                                        class="btn btn-success btn-xs">
                                                        + Add Fields
                                                    </a>

                                                    @if ($form->publish_status === 'draft')
                                                        <form method="POST"
                                                            action="{{ route('forms.submitForApproval', $form->form_id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-info btn-xs">Submit for
                                                                Approval</button>
                                                        </form>
                                                    @elseif($form->publish_status === 'pending_approval')
                                                        <form method="POST"
                                                            action="{{ route('forms.approve', $form->form_id) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-default btn-xs">Approve</button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('forms.edit', $form->form_id) }}"
                                                        class="btn btn-warning btn-xs">
                                                        Edit
                                                    </a>

                                                    <form action="{{ route('forms.destroy', $form->form_id) }}"
                                                        method="POST" style="display:inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this form?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No forms created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
