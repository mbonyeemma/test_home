@extends('layouts.app')

@section('title', 'Add IP')
@section('css') 
<link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@append
@section('js') 
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script> 
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script> 
<script>
	$(document).ready(function() {
		$('#ipform').bootstrapValidator({
       
        fields: {
			name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a name'
                        }
                    }
                }, 
					emailaddress: {          
				validators: {
							regexp: {
							  regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
							  message: 'The value is not a valid email address'
							}
						}
					}
		}//endo of validation rules
    });// close form validation function
	//date 
	//Date picker
    $('#startdate, #enddate').datepicker({
      autoclose: true
    })
});
</script> 
@append

@section('content')
<div class="box box-info"> 
  
  <!-- /.box-header --> 
  <!-- form start --> 
  {{-- Using the Laravel HTML Form Collective to create our form --}}
  {{ Form::open(array('route' => 'organization.store', 'class' => 'form-horizontal','id' => 'ipform')) }}
  {{ csrf_field() }}
  <div class="box-body">
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label">{{ Form::label('name', 'Name') }}</label>
      <div class="col-sm-10"> {{ Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) }} </div>
    </div>
    <div class="form-group">
      <label for="healthregionid" class="col-sm-2 control-label">{{ Form::label('healthregionid', 'Health Region') }}</label>
      <div class="col-sm-10"> {{ Form::select('healthregionid', $healthregion, null, ['class' => 'form-control']) }} </div>
    </div>
     
    
    <div class="form-group">
      <label for="address" class="col-sm-2 control-label">{{ Form::label('address', 'Address') }}</label>
      <div class="col-sm-10"> {{ Form::text('address', null, array('class' => 'form-control', 'id' => 'address')) }} </div>
    </div>
    <div class="form-group">
      <label for="telephonenumber" class="col-sm-2 control-label">{{ Form::label('telephonenumber', 'Telephone Number') }}</label>
      <div class="col-sm-10"> {{ Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber')) }} </div>
    </div>
    <div class="form-group">
      <label for="emailaddress" class="col-sm-2 control-label">{{ Form::label('emailaddress', 'Email Address') }}</label>
      <div class="col-sm-10"> {{ Form::text('emailaddress', null, array('class' => 'form-control', 'id' => 'emailaddress')) }} </div>
    </div>
    <div class="form-group">
      <label for="supportagencyid" class="col-sm-2 control-label">{{ Form::label('supportagencyid', 'Spport Agency') }}</label>
      <div class="col-sm-10"> {{ Form::select('supportagencyid', $supportagencies, null, ['class' => 'form-control']) }} </div>
    </div>
    <div class="form-group">
      <label for="supportperiod" class="col-sm-2 control-label">{{ Form::label('supportperiod', 'Spport Period') }}</label>
      <div class="col-sm-6"> {{ Form::text('startdate', null, array('class' => 'form-control', 'id' => 'startdate', 'placeholder' => 'Start Date')) }} </div>
      <div class="col-sm-4"> {{ Form::text('enddate', null, array('class' => 'form-control', 'id' => 'enddate', 'placeholder' => 'End Date')) }} </div>
    </div>
    
  </div>
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
   
    {{ Form::hidden('type', 1) }}
    {{ Form::submit('Create IP', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 