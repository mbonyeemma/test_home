@extends('layouts.app')

@section('title', 'Edit Role: '.$role->name)
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 
@append
@section('content')
<div class="box box-info"> @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  
  {{ Form::model($role, array('id'=>'roleform','route' => array('roles.update', $role->id), 'method' => 'PUT')) }}
  <div class="box-body">
    <div class="form-group"> {{ Form::label('name', 'Role Name') }}
      {{ Form::text('name', $role->display_name, array('class' => 'form-control')) }} </div>
    <h5><b>Assign Permissions</b></h5>
    @foreach ($permissions as $permission)
    {{Form::checkbox('permissions[]',  $permission->id, checkifPermissioninArray($permission->id, $role_permissions)) }}
    {{Form::label($permission->name, ucfirst($permission->display_name)) }}<br>
    @endforeach </div>
  <!--/box-body -->
  <div class="box-footer"> 
   <a class="btn btn-danger btn-sm" href="{{ URL::previous() }}">Cancel</a>
  {{ Form::submit('Edit Role', array('class' => 'btn btn-sm btn-warning pull-right')) }}
    
    {{ Form::close() }} </div>
  <!-- /.box-footer --> 
</div>
@endsection