@extends('layouts.app')

@section('title', 'Create New Bike')

@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.extensions.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#equipmentform').bootstrapValidator({
       
        fields: {
			enginenumber: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the engine number'
                        }
                    }
                },
                
			chasisnumber: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the chasis number'
                        }
                    }
                },
				yearofmanufacture: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter year of manufacture'
                        }
                    }
                },
				modelnumber: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the model number'
                        }
                    }
                },
				purchasedon: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the date of purchase'
                        }
                    }
                },
				hasservicecontract: {
                    validators: {
                        notEmpty: {
                            message: 'Please select contract status'
                        }
                    }
                },
				
				enginecapacity: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the engine capacity'
                        },
						numeric: {
                            message: 'Please enter a number'
                        }
                    }
                },
				warrantyperiod: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the warranty period'
                        },
						numeric: {
                            message: 'Please enter a number'
                        }
                    }
                },
				recommendedservicefrequency: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the recommended service frequency'
                        },
						numeric: {
                            message: 'Please enter a number'
                        }
                    }
                },
				
				
				color: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the color'
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
	
	$(function () {

    //Datemask dd/mm/yyyy
    $('#purchasedon, #deliveredtohubon').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

  })
</script>
@append

@section('content')
	<div class="box box-info">
    
    @if ( ! $errors->isEmpty() )
	<div class="row">
		@foreach ( $errors->all() as $error )
		<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    <strong>Failed!</strong>{{ $error }} </div>   
		@endforeach
	</div>
	@endif
            <!-- /.box-header -->
            <!-- form start -->
            {{-- Using the Laravel HTML Form Collective to create our form --}}
            {{ Form::model($equipment, array('route' => array('equipment.update', $equipment->id),  'class' => 'form-horizontal', 'id' => 'equipmentform', 'method' => 'PUT')) }}
            {{ csrf_field() }}
              <div class="box-body create">
                <div class="form-group">
                  <label for="enginenumber" class="col-sm-3 control-label">{{ Form::label('enginenumber', 'Engine Number') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('enginenumber', null, array('class' => 'form-control', 'id' => 'enginenumber')) }}
                  </div>
                </div>
               <div class="form-group">
                  <label for="chasisnumber" class="col-sm-3 control-label">{{ Form::label('chasisnumber', 'Chasis Number') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('chasisnumber', null, array('class' => 'form-control', 'id' => 'chasisnumber')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="modelnumber" class="col-sm-3 control-label">{{ Form::label('modelnumber', 'Model Number') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('modelnumber', null, array('class' => 'form-control', 'id' => 'modelnumber')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="color" class="col-sm-3 control-label">{{ Form::label('color', 'Color') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('color', null, array('class' => 'form-control', 'id' => 'color')) }}
                  </div>
                </div>
                                
                <div class="form-group">
                  <label for="enginecapacity" class="col-sm-3 control-label">{{ Form::label('enginecapacity', 'Engine Capacity') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('enginecapacity', null, array('class' => 'form-control', 'id' => 'enginecapacity')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="brand" class="col-sm-3 control-label">{{ Form::label('brand', 'Brand') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('brand', null, array('class' => 'form-control', 'id' => 'brand')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="yearofmanufacture" class="col-sm-3 control-label">{{ Form::label('yearofmanufacture', 'Year of Manufacture') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('yearofmanufacture', null, array('class' => 'form-control', 'id' => 'yearofmanufacture')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="numberplate" class="col-sm-3 control-label">{{ Form::label('numberplate', 'Number Plate') }}</label>
                  <div class="col-sm-9">
                    {{ Form::text('numberplate', null, array('class' => 'form-control', 'id' => 'numberplate')) }}
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="purchasedon" class="col-sm-3 control-label">{{ Form::label('purchasedon', 'Purchased on') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="purchasedon" id="purchasedon" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="deliveredtohubon" class="col-sm-3 control-label">{{ Form::label('deliveredtohubon', 'Delivered to Hub on') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="deliveredtohubon" id="deliveredtohubon" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="warrantyperiod" class="col-sm-3 control-label">{{ Form::label('warrantyperiod', 'Warranty Period') }}</label>
                  <div class="col-sm-7">
                    {{ Form::text('warrantyperiod', null, array('class' => 'form-control', 'id' => 'warrantyperiod')) }}
                  </div><div class="col-sm-2">                
                  {{ Form::select('warrantyperiodunits', $warrantyunitsdropdown, null, ['class' => 'form-control']) }}
                  </div>  
                </div>
                
                <div class="form-group">
                  <label for="warrantyperiod" class="col-sm-3 control-label">{{ Form::label('warrantyperiod', 'Recommended Service Frequency') }}</label>
                	<div class="col-sm-7">
                    {{ Form::text('recommendedservicefrequency', null, array('class' => 'form-control', 'id' => 'recommendedservicefrequency')) }}
                  </div><div class="col-sm-2">{{ Form::select('servicefrequencyunits', $servicefreqdropdown, null, ['class' => 'form-control']) }}</div>  
                </div>    
                
                
                 <div class="form-group">
                  <label for="warrantyperiod" class="col-sm-3 control-label">{{ Form::label('hasservicecontract', 'Has Service Contract') }}</label>
                	<div class="col-sm-8">
                    {{ Form::radio('hasservicecontract', 1, null, ['class' => '']) }} Yes
                    {{ Form::radio('hasservicecontract', 0, null, ['class' => '']) }} No
                  </div><div class="col-sm-1"></div>  
                </div>          
                              
                <div class="form-group">
                  <label for="insurance" class="col-sm-3 control-label">{{ Form::label('insurance', 'Insurance') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('insurance', null, array('class' => 'form-control', 'id' => 'insurance')) }}
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="facility" class="col-sm-3 control-label">{{ Form::label('facility', 'Hub') }}</label>

                  <div class="col-sm-9">
                    {{ Form::select('facilityid', $hubsdropdown, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Update Bike', array('class' => 'btn btn-warning pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 