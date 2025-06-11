@extends('layouts.app')

@section('title', 'Refer Sample')
@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
@append
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
 <script>
	$(document).ready(function() {
		$('#hubform').bootstrapValidator({
       
        fields: {
			name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a name'
                        }
                    }
                },
                
			healthregionid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a health region'
                        }
                    }
                },
				parentid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the facility to which this hub is attached'
                        }
                    }
                },
				ipid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the IP'
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
	
	$('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1,
		minuteStep: 1,
		initialDate: '12-02-2012'
    });
});
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
    		{{ Form::open(array('route' => 'sampletracking.store', 'class' => 'form-horizontal', 'id' => 'hubform')) }}
            {{ csrf_field() }}
              <div class="box-body">                
                <div class="form-group">
                  <label for="facility" class="col-sm-2 control-label">{{ Form::label('facilityid', 'Source Facility') }}</label>
                  <div class="col-sm-10"> {{ Form::select('facilityid', $facilities, null, ['class' => 'form-control']) }}
                  {{Form::hidden('hubid', $hubid)}}
                   </div>
                </div>
                <div class="form-group">
                  <label for="patient" class="col-sm-2 control-label">{{ Form::label('patient', 'Patient') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('patient', null, array('class' => 'form-control', 'id' => 'patient')) }}
                  </div>
                </div> 
                <div class="form-group">
                  <label for="sampletransportedby" class="col-sm-2 control-label">{{ Form::label('sampletransportedby', 'Sample Transported By') }}</label>
                  <div class="col-sm-10"> {{ Form::select('sampletransportedby', $sampletransporters, null, ['class' => 'form-control']) }}
                   </div>
                </div>
                
                <div class="form-group">
                  <label for="facility" class="col-sm-2 control-label">{{ Form::label('specimentype', 'Specimen Type') }}</label>
                  <div class="col-sm-10"> {{ Form::select('specimentype', $specimentypes, null, ['class' => 'form-control']) }}
                   </div>
                </div>
                  <div class="form-group">
                  <label for="specimen number" class="col-sm-2 control-label">{{ Form::label('specimennumber', 'Specimen Number') }}</label>
                  <div class="col-sm-10">
                    {{ Form::text('specimennumber', null, array('class' => 'form-control', 'id' => 'specimennumber')) }}
                  </div>
                </div>    
                
                <div class="form-group">
                  <label for="sampletransported_at" class="col-sm-2 control-label">{{ Form::label('sampletransported_at', 'Sample Transported At:') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('sampletransported_at', date('Y-m-d H:i'), array('class' => 'form-control form_datetime', 'id' => 'sampletransported_at')) }}
                    
                  </div>
                </div>
                           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Refer Sample', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 