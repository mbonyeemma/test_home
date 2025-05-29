{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Reset Password')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		
		$('#resetpasswordform').bootstrapValidator({
       
        fields: {
				oldpassword: {          
				validators: {
							notEmpty: {
								message: 'Please enter your old password'
							}
						}
					},
            	password: {          
				validators: {
							notEmpty: {
								message: 'Please enter a password'
							},
							securePassword: {
								message: 'The password is not valid'
							}
						}
					},
				password_confirmation: {
                validators: {
					notEmpty: {
								message: 'Please confirm your password'
							},
                    identical: {
                        field: 'password',
                        message: 'The passwords do not match'
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

    {{ Form::open(array('action'=>'UserController@saveresetpassword','id'=>'resetpasswordform','method' => 'POST')) }}
<div class="box-body">
    @if($userid == Auth::user()->id)
	<div class="form-group">
        {{ Form::label('oldpassword', 'Old Password') }}<br>
        {{ Form::password('oldpassword', array('class' => 'form-control')) }}

    </div>
    @endif
    <div class="form-group">
        {{ Form::label('password', 'New Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}
        {{ Form::hidden('userid', $userid) }}

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Confirm New Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

    </div>
</div>
              <!-- /.box-body -->
              <div class="box-footer">
              <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a>
    {{ Form::submit('Reset', array('class' => 'btn btn-sm btn-info pull-right')) }}

    {{ Form::close() }}
</div>
</div>

@endsection
