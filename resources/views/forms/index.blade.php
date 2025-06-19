@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Created Forms</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('forms.create') }}" class="btn btn-primary" style="margin-bottom: 15px;">+ Create New Form</a>

    @if($forms->count())
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
                    @foreach($forms as $form)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $form->name }}</td>
                        <td>{{ $form->form_id }}</td>
                        <td>{{ $form->fields->count() }}</td>
                        <td>{{ $form->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('forms.show', $form->form_id) }}" class="btn btn-info btn-xs">View</a>
                            <a href="{{ route('forms.edit', $form->form_id) }}" class="btn btn-warning btn-xs">Edit</a>
                            <form method="POST" action="{{ route('forms.destroy', $form->form_id) }}" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this form?')">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger btn-xs" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No forms have been created yet.</p>
    @endif
</div>
@endsection
