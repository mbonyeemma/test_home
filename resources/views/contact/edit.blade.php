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
					emailaddress: {          
				validators: {
							regexp: {
							  regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
							  message: 'Please enter a valid email address'
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
            
             {{ Form::model($contact, array('route' => array('contact.update', $contact->id),  'class' => 'form-horizontal', 'id' => 'contactform', 'method' => 'PUT')) }}
            	{{ csrf_field() }}
              <div class="box-body">
             @if($contact->category ==2 && $contact->type == 6)
             
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
                  <label for="telephonenumber" class="col-sm-2 control-label">{{ Form::label('telephonenumber', 'Telephone Number1') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber')) }}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="telephonenumber2" class="col-sm-2 control-label">{{ Form::label('telephonenumber2', 'Telephone Number2') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('phone2', null, array('class' => 'form-control', 'id' => 'phone2')) }}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="telephonenumber3" class="col-sm-2 control-label">{{ Form::label('telephonenumbe3', 'Preferred Whatsapp Number') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('phone3', null, array('class' => 'form-control', 'id' => 'phone3')) }}
                  </div>
                </div>  
                <div class="form-group hidden">
                  <label for="telephonenumber4" class="col-sm-2 control-label">{{ Form::label('telephonenumbe4', 'Telephone Number4') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('phone4', null, array('class' => 'form-control', 'id' => 'phone4')) }}
                  </div>
                </div>                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
            	
              
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit($title, array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 