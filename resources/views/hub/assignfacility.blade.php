@extends('layouts.app')
@section('title', 'Assign Hub to facility')
@section('css')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@append
@section('js') 
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script> 
<script src="{{ asset('js/select2.full.min.js') }}"></script> 
<script>
	$(document).ready(function() {
		$('.select2').select2();
		$('#assignfacilityform').bootstrapValidator({
       
        fields: {
			hub: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a hub'
                        }
                    }
                },
			'facilities[]': {
                    validators: {
                        notEmpty: {
                            message: 'Please select at least one facility'
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
  {{ Form::open(array('route' => 'hub.massassignfacilities', 'class' => 'form-horizontal', 'id' => 'assignfacilityform')) }}
  {{ csrf_field() }}
  <div class="box-body">
   
    <div class="form-group">
      <label for="hub" class="col-sm-2 control-label">{{ Form::label('hub', 'hub') }}</label>
      <div class="col-sm-10"> {{ Form::select('hubid', $hubdropdown, null, ['class' => 'form-control', 'data-placeholder' => 'Select hub']) }} </div>
    </div>
    <div class="form-group">
      <label for="facilities" class="col-sm-2 control-label">{{ Form::label('facilities', 'Facilities') }}</label>
      <div class="col-sm-10"> {{ Form::select('facilities[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select all facilities for this hub']) }} </div>
    </div>
  </div>
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a>
    </button>    {{ Form::submit('Save', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 