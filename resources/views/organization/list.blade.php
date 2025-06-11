@extends('layouts.app')
@section('title', 'View All Ips')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('listpagejs') 
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
<script>
		$(document).ready(function() {
			$('#listtable').DataTable();
		} );
	</script> 
@append
<div class="box box-info"> 
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($organizations as $organization)
      <tr>
        <td><a href="{{ route('organization.show', $organization->id ) }}">{{ $organization->name }}</a></td>
        <td><a href="{{ route('organization.edit', $organization->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;  @if(Entrust::can('Delete-IP'))
        <a href="{{ route('organization.destroy', $organization->id ) }}"><i class=" fa fa-fw fa-trash-o"></i>Delete</a>
        @endif
        </td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  <!-- /.box-body --> 
</div>
@endsection