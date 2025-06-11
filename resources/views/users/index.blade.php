{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Users')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script>
		$(document).ready(function() {
			$('#users-table').DataTable();
		} );
	</script>
@append
@section('content')
<div class="box box-info">
    <div class="box-body table-responsive">
        <table id="users-table" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date/Time Added</th>
                    <th>User Roles</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>

                    <td><a href="{{ route('users.show', $user->id ) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                    <td>{{ $user->roles()->pluck('display_name')->implode(', ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                    <td>
                    <a href="{{ route('users.edit', $user->id) }}"> <i class="fa fa-fw fa-edit"></i> Edit</a>
					<a href="{{ route('users.destroy', $user->id) }}"> <i class="fa fa-ban"></i> De-activate</a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection