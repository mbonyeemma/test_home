{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add User')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		//display hub dropdown if In-charge option is checked
		$('#role5, #role1').click(function(){
			if($('#role5').is(':checked') || $('#role1').is(':checked')){
				$('#hub').removeClass('hidden');
			}else{
				if ($('#hubid').val() !== '') {
					$('#hubid').val('');    
				}  
				$('#hub').addClass('hidden');
			}
		});
		//display hub dropdown if Regional hub coordinator option is checked
		$('#role4').click(function(){
			if($(this).is(':checked')){
				$('#hr').removeClass('hidden');
			}else{
				if ($('#healthregionid').val() !== '') {
					$('#healthregionid').val('');    
				} 
				$('#hr').addClass('hidden');
			}
		});
        //display ips, if user is ip
        $('#role11').click(function(){
            if($(this).is(':checked')){
                $('#ips').removeClass('hidden');
            }else{
                if ($('#organisation_id').val() !== '') {
                    $('#organisation_id').val('');    
                } 
                $('#ips').addClass('hidden');
            }
        });
	    
		$('#userform').bootstrapValidator({
       
        fields: {
			name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the name'
                        }
                    }
                },
				username: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the username'
                        }
                    }
                },
				
				'roles[]': {
                    validators: {
                        notEmpty: {
                            message: 'Please select atleast one role for the user'
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
				},
				email: {          
				validators: {
							notEmpty: {
							  message: 'Please enter the email address'
							},
							regexp: {
							  regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
							  message: 'The value is not a valid email address'
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

    {{ Form::open(array('id'=>'userform','url' => 'users')) }}
<div class="box-body">
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>
	<h2>Assign Group</h2>
    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, null, ['id' => 'role'.$role->id] ) }}
            {{ Form::label($role->name, ucfirst($role->display_name)) }}<br>

        @endforeach
    </div>
	<div class="form-group hidden" id="hub">
        {{ Form::label('hubid', 'Hub') }}
        {{ Form::select('hubid', $hubs, null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group hidden" id="hr">
        {{ Form::label('healthregionid', 'Health Region') }}
        {{ Form::select('healthregionid', $healthregions, null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group hidden" id="ips">
        {{ Form::label('organization', 'IP') }}
        {{ Form::select('organisation_id', $ips, null, ['class' => 'form-control']) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

    </div>
</div>
              <!-- /.box-body -->
              <div class="box-footer">
              <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a>
    {{ Form::submit('Add User', array('class' => 'btn btn-sm btn-info pull-right')) }}

    {{ Form::close() }}
</div>
</div>

@endsection
