{{-- \resources\views\permissions\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Permission')
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

    {{ Form::open(array('url' => 'permissions', 'id' => 'permissionform')) }}
<div class="box-body">
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    @if(!$roles->isEmpty()) 
        <h2>Assign Permission to Roles</h2>
		@foreach ($roles as $role) 
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->display_name)) }}<br>

        @endforeach
    @endif
    </div>
  <!--/box-body -->
  <div class="box-footer"> <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a>
    {{ Form::submit('Add Permission', array('class' => 'btn btn-sm btn-info pull-right')) }}

    {{ Form::close() }}
</div>
</div>

@endsection