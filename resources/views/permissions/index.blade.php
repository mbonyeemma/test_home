{{-- \resources\views\permissions\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Permissions')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script>
		$(document).ready(function() {
			$('#permisions-table').DataTable();
			
		} );
	</script>
@append
@section('content')
   <div class="box box-info">

    <div class="box-body table-responsive">
        <table id="permisions-table" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->display_name }}</td> 
                    <td>
                    <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
					<a href="{{ URL::to('permissions/'.$permission->id.'/destroy') }}"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection