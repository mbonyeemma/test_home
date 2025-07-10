@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pending Field Change Requests</h2>

        @if ($pendingChanges->isEmpty())
            <div class="alert alert-info">No pending field changes.</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @foreach ($pendingChanges as $change)
            <div class="card my-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Action: <span class="text-primary">{{ ucfirst($change->action) }}</span></h5>
                    <p class="mb-1">Form: <strong>{{ $change->form->name ?? 'Form' }}</strong></p>
                    <p class="mb-1">Maker: <strong>{{ $change->maker->name }}</strong></p>
                    <p class="mb-2"><small>Submitted: {{ $change->created_at->diffForHumans() }}</small></p>

                    <pre class="bg-light p-2 border rounded">{{ json_encode($change->field_data, JSON_PRETTY_PRINT) }}</pre>

                    <div class="d-flex gap-2 mt-3">
                        <form method="POST" action="{{ route('field-changes.approve', $change->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">✅ Approve</button>
                        </form>

                        <form method="POST" action="{{ route('field-changes.reject', $change->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">❌ Reject</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
