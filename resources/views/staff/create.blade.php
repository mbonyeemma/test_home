@extends('layouts.app')
@if ($pagetype == 1)
	@section('title', 'Add New Sample Transporter')
@elseif($pagetype == 4)
	@section('title', 'Add New Driver')
  @elseif($pagetype == 3)
  @section('title', 'Add New Ref Lab Receptionist')
@elseif($pagetype == 2)
	@section('title', 'Add New Sample Receptionist')
@elseif($pagetype == 5)	
	@section('title', 'Add New EOC Staff')
@elseif($pagetype == 6) 
  @section('title', 'Add New POE User')
@elseif($pagetype == 7) 
  @section('title', 'Community User')
@elseif($pagetype == 8) 
  @section('title', 'Private Transporter')
@else
@endif
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
	$('#permitexpirydate').datepicker({
		   format: 'mm/dd/yyyy',
		   autoclose: true
		});
		
	$("input[name='hasdrivingpermit']").change(function(){
		if( $(this).is(":checked") ){ // check if the radio is checked
            var val = $(this).val(); // retrieve the value
			//alert(val);
			if(val == 1){
				$('#permitexpirydate').removeClass('hidden');
			}else{
				$('#permitexpirydate').val('');
				$('#permitexpirydate').addClass('hidden');
			}
        }	
		
	});
	$("input[name='permitexpirydate']").change(function(){
		$('#staffform').bootstrapValidator('revalidateField', 'permitexpirydate');
	});
	$("select[name='facilityid']").change(function(){
      var id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
          url: "<?php echo url('staff/bikewithoutrider'); ?>",
          method: 'POST',
          data: {hubid:id, _token:token},
          success: function(data) {
			  	$("#motorbikeid").html("").prepend("<option value=''>Select One</option>"); 
			    $("select[name='motorbikeid'").html('');
				$("select[name='motorbikeid'").html(data.options);
			  }
		  });
	  });
		
		$('#staffform').bootstrapValidator({
       
        fields: {
			facilityid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a hub'
                        }
                    }
                },
                
			firstname: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the first name'
                        }
                    }
                },
				hasdrivingpermit: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a number'
                        }
                     }
                },
        permitexpirydate: {
            validators: {
                notEmpty: {
                    message: 'Please select the permit expiry date'
                }
             }
        },
		    password: {
            validators: {
                notEmpty: {
                    message: 'Please the password the user will use to login into the mobile app'
                }
             }
        },
        username: {
            validators: {
                notEmpty: {
                    message: 'Please the username the user will use to login into the mobile app'
                }
             }
        },
        isimmunizedforhb: {
            validators: {
                notEmpty: {
                    message: 'Please select whether the transporter is Immunized for HB'
                }
             }
        },
        hasdrivingpermit: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter has driving permit'
                }
             }
        },
        hasbbtraining: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter is trained in BB'
                }
             }
        },
        hasdefensiveriding: {
            validators: {
                notEmpty: {
                    message: 'Please specify whether transporter has undergone defensive driving'
                }
             }
        },
        poe_site: {
            validators: {
                notEmpty: {
                    message: 'Please select the POE site'
                }
             }
        },
				nationalid: {
                    validators: {
						stringLength: {
							  min: 14,
							  max: 14,
							  message: 'The NIN should be 14 characters long'
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
            {{-- Using the Laravel HTML Form Collective to create our form --}}
    		{{ Form::open(array('route' => 'staff.store', 'class' => 'form-horizontal', 'id' => 'staffform')) }}
            	{{ csrf_field() }}
              <div class="box-body">
              @role(['Admin','Regional_hub_coordinator','Program_officer','national_hub_coordinator']) 
                @if($pagetype == 1 || $pagetype == 2 || $pagetype == 4 || $pagetype == 8) 
                  <div class="form-group">
                      <label for="facility" class="col-sm-2 control-label">{{ Form::label('facility', 'Hub') }}</label>

                      <div class="col-sm-10">
                        {{ Form::select('facilityid', $hubsdropdown, null, ['class' => 'form-control', 'id' => 'facilityid']) }}
                         
                      </div>
                    </div>
                  @endif             
                @endrole
                {{ csrf_field() }}
                <div class="box-body">
                @if($pagetype == 6) 
                <div class="form-group">
                    <label for="facility" class="col-sm-2 control-label">{{ Form::label('poe_site', 'POE Site') }}</label>

                    <div class="col-sm-10">
                      {{ Form::select('poe_site', $poe_sites, null, ['class' => 'form-control', 'id' => 'poe_site']) }}
                       
                    </div>
                </div>             
                @endif 
                @if($pagetype == 3) 
                <div class="form-group">
                    <label for="facility" class="col-sm-2 control-label">{{ Form::label('ref_lab', 'Ref Lab') }}</label>

                    <div class="col-sm-10">
                      {{ Form::select('ref_lab', $ref_labs, null, ['class' => 'form-control', 'id' => 'ref_lab']) }}
                       
                    </div>
                </div>             
                @endif
                @if ($pagetype == 1)
                 <div class="form-group">
                  <label for="motorbikeid" class="col-sm-2 control-label">{{ Form::label('bikes', 'Motor Bike') }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('motorbikeid', $bikes, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                @endif
              @if ($pagetype == 2)
              	<div class="form-group" style="display:none">
                  <label for="designation" class="col-sm-2 control-label">{{ Form::label('designation', 'Designation') }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('designation', $designation, null, ['class' => 'form-control']) }}
                     
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
                <div class="form-group">
                  <label for="telephonenumber2" class="col-sm-2 control-label">{{ Form::label('telephonenumber2', 'Telephone Number2') }}</label>

                  <div class="col-sm-10">
                  {{ Form::text('telephonenumber2', null, array('class' => 'form-control', 'id' => 'telephonenumber2')) }}
                  </div>
                </div> 

                <div class="form-group">
                  <label for="telephonenumber3" class="col-sm-2 control-label">{{ Form::label('telephonenumber3', 'Preferred Whatsapp Number') }}</label>

                  <div class="col-sm-10">
                  {{ Form::text('telephonenumber3', null, array('class' => 'form-control', 'id' => 'telephonenumber3')) }}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="username" class="col-sm-2 control-label">{{ Form::label('username', 'Username') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('username', null, array('class' => 'form-control', 'id' => 'username')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">{{ Form::label('password', 'Password') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('password', null, array('class' => 'form-control', 'id' => 'password')) }}
                  </div>
                </div>
                
                @if ($pagetype == 1)
                    <div class="form-group">
                      <label for="drivingpermit" class="col-sm-2 control-label">{{ Form::label('drivingpermit', 'Has Driving Permit') }}</label>
    
                      <div class="col-sm-3">
                        {!!generateRationInput($yes_no, 'hasdrivingpermit')!!}
                        @if ($errors->has('hasdrivingpermit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hasdrivingpermit') }}</strong>
                            </span>
                        @endif
                      </div>
                      <div class="col-sm-3">
                        {{ Form::text('permitexpirydate', null, array('class' => 'form-control hidden', 'id' => 'permitexpirydate', 'placeholder' => 'Expiry Date')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="hasdefensiveriding" class="col-sm-2 control-label">{{ Form::label('hasdefensiveriding', 'Has Defensive Driving') }}</label>

                      <div class="col-sm-10">
                        {!!generateRationInput($yes_no, 'hasdefensiveriding')!!}
                        @if ($errors->has('hasdefensiveriding'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hasdefensiveriding') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="hasbbtraining" class="col-sm-2 control-label">{{ Form::label('hasbbtraining', 'Has BB Training') }}</label>

                      <div class="col-sm-10">
                        {!!generateRationInput($yes_no, 'hasbbtraining')!!}
                        @if ($errors->has('hasbbtraining'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hasbbtraining') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="isimmunizedforhb" class="col-sm-2 control-label">{{ Form::label('isimmunizedforhb', 'Is Immunized for Hepatitis B') }}</label>

                      <div class="col-sm-10">
                        {!!generateRationInput($yes_no, 'isimmunizedforhb')!!}
                        @if ($errors->has('isimmunizedforhb'))
                            <span class="help-block">
                                <strong>{{ $errors->first('isimmunizedforhb') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>  
                 @endif
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                {{Form::hidden('type', $pagetype)}}
                <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a>
               
                {{ Form::submit('Create', array('class' => 'btn btn-sm btn-info pull-right')) }}
                
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 