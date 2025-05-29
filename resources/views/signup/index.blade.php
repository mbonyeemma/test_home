@extends('layouts.openlayout')
@section('title', 'Signup')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		
			$('#signupform').bootstrapValidator({
		   
			fields: {
				firstname: {
						validators: {
							notEmpty: {
								message: 'Please enter the first name'
							}
						}
					},
					
			code: {
				validators: {
					notEmpty: {
						message: 'Please the code the user will use to login into the mobile app'
					}
				 }
			},
			
			lastname: {
				validators: {
					notEmpty: {
						message: 'Please enter the last name'
					}
				}
			},
			telephonenumber: {
				validators: {
					notEmpty: {
						message: 'Please enter the telephone number'
					}
				}
			},
				email: {          
			validators: {
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
            
            <!-- /.box-header -->
            <!-- form start -->
    		{{ Form::open(array('route' => 'signup.store', 'class' => 'form-horizontal', 'id' => 'signupform')) }}
            	{{ csrf_field() }}
              <div class="box-body">             
               
                <div class="form-group">
                  <label for="firstname" class="col-sm-3 control-label">{{ Form::label('firstname', 'First Name') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) }}
                  </div>
                </div>
               <div class="form-group">
                  <label for="lastname" class="col-sm-3 control-label">{{ Form::label('lastname', 'Last Name') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="othernames" class="col-sm-3 control-label">{{ Form::label('othernames', 'Other Names') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('othernames', null, array('class' => 'form-control', 'id' => 'othernames')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="emailaddress" class="col-sm-3 control-label">{{ Form::label('emailaddress', 'Email Address') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('emailaddress', null, array('class' => 'form-control', 'id' => 'emailaddress')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="telephonenumber" class="col-sm-3 control-label">{{ Form::label('telephonenumber', 'Telephone Number') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="logincode" class="col-sm-3 control-label">{{ Form::label('logincode', 'Mobile App Login Code') }}</label>
                  <div class="col-sm-9">
                    {{ Form::text('code', null, array('class' => 'form-control', 'id' => 'code', 'placeholder' => 'e.g., 345')) }}
                  </div>
                </div>
               
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a>               
                {{ Form::submit('Signup', array('class' => 'btn btn-sm btn-info pull-right')) }}
               
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 