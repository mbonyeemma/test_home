@extends('layouts.app')

@section('title', 'Add Role')
@section('title', 'Create Permission')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#roleform').bootstrapValidator({
       
			fields: {
				name: {
						validators: {
							notEmpty: {
								message: 'Please enter the permission name'
							}
						}
					},
					'permissions[]': {
						validators: {
							notEmpty: {
								message: 'Please select atleast one permission'
							}
						}
					}					
			}//endo of validation rules
		});// close form validation function
	});
</script>
@append
@section('content')
<div class="box box-info"> 
@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  {{ Form::open(array('url' => 'roles', 'id' => 'roleform')) }}
  <div class="box-body">
    <div class="form-group"> {{ Form::label('name', 'Name') }}
      {{ Form::text('name', null, array('class' => 'form-control')) }} </div>
    <h2>Assign Permissions</h2>
    <div class='form-group'> @foreach ($permissions as $permission)
      {{ Form::checkbox('permissions[]',  $permission->id ) }}
      {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>
      @endforeach </div>
  </div>
  <!--/box-body -->
  <div class="box-footer"> <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a> {{ Form::submit('Add Role', array('class' => 'btn btn-sm btn-info pull-right')) }}
    
    {{ Form::close() }} </div>
  <!-- /.box-footer --> 
</div>
@endsection 