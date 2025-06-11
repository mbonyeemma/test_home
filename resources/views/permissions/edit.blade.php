@extends('layouts.app')

@section('title', 'Edit Permission: '.$permission->display_name)
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#permissionform').bootstrapValidator({
       
        fields: {
			name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the permission name'
                        }
                    }
                }
		}//endo of validation rules
    });// close form validation function
	});
</script>
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
  
  {{ Form::model($permission, array('id'=>'permissionform','route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}
  <div class="box-body">
    <div class="form-group"> {{ Form::label('name', 'Permission Name') }}
      {{ Form::text('name', $permission->display_name, array('class' => 'form-control')) }} </div>
  </div>
  <!--/box-body -->
  <div class="box-footer"> <a class="btn btn-danger btn-sm" href="{{ URL::previous() }}">Cancel</a> {{ Form::submit('Edit Permission', array('class' => 'btn btn-sm btn-warning pull-right')) }}
    
    {{ Form::close() }} </div>
  <!-- /.box-footer --> 
</div>
@endsection