{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Roles')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script>
		$(document).ready(function() {
			$('#roles-table').DataTable();
			
		} );
	</script>
@append
@section('content')
<div class="box box-info">

    <div class="box-body table-responsive">
        <table id="roles-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $role)
                <tr>

                    <td>{{ $role->display_name }}</td>

                    <td><ul>@foreach($role->perms as $permission)
        				<li>{{ $permission->display_name }}</li>
    					@endforeach</ul></td>
                    <td>
                    <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class=""><i class="fa fa-fw fa-edit"></i> Update</a>
					<a href="{{ route('roles.destroy', $role->id ) }}"><i class="fa fa-fw fa-trash-o"></i>Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection

