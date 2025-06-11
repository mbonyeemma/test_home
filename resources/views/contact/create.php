@extends('layouts.app')

	@section('title', $title)
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#contactform').bootstrapValidator({
       
        fields: {
			
			firstname: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the first name'
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
            
            <!-- /.box-header -->
            <!-- form start -->
            {{-- Using the Laravel HTML Form Collective to create our form --}}
    		{{ Form::open(array('route' => 'contact.store', 'class' => 'form-horizontal', 'id' => 'contactform')) }}
            	{{ csrf_field() }}
              <div class="box-body">
              @if($category ==2 && $type = 6)
             
              <div class="form-group">
                  <label for="ipid" class="col-sm-2 control-label">{{ Form::label('dlfpdistrictid', "DLFP's District") }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('dlfpdistrictid', $districts_for_hub, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                @endif
                <div class="form-group">
                  <label for="firstname" class="col-sm-2 control-label">{{ Form::label('firstname', 'First Name') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) }}
                  </div>
                </div>
               <div class="form-group">
                  <label for="lastname" class="col-sm-2 control-label">{{ Form::label('lastname', 'Last Name') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="othernames" class="col-sm-2 control-label">{{ Form::label('othernames', 'Other Names') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('othernames', null, array('class' => 'form-control', 'id' => 'othernames')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="emailaddress" class="col-sm-2 control-label">{{ Form::label('emailaddress', 'Email Address') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('emailaddress', null, array('class' => 'form-control', 'id' => 'emailaddress')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="telephonenumber" class="col-sm-2 control-label">{{ Form::label('telephonenumber', 'Telephone Number') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber')) }}
                  </div>
                </div>  

                

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
            	{{ Form::hidden('category', $category) }}
        	   	{{ Form::hidden('type', $type) }}
                {{ Form::hidden('obj', $obj) }}
              
                <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit($title, array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 