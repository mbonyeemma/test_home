@extends('layouts.app')
@section('title', 'Add Daily Routing')
@section('css')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@append
@section('js') 
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script> 
<script src="{{ asset('js/select2.full.min.js') }}"></script> 
<script>
	$(document).ready(function() {
		$('.select2').select2();
		$('#routingscheduleform').bootstrapValidator({
       
        fields: {
			monday: {
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
  {{ Form::open(array('route' => 'dailyrouting.store', 'class' => 'form-horizontal', 'id' => 'routingscheduleform')) }}
  {{ csrf_field() }}
  <div class="box-body">
    <div class="form-group">
      <label for="monday" class="col-sm-2 control-label">{{ Form::label('monday', 'Monday') }}</label>
      <div class="col-sm-10"> {{ Form::select('monday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder'=>'Select facilities visited on Monday']) }} </div>
    </div>
    <div class="form-group">
      <label for="tuesday" class="col-sm-2 control-label">{{ Form::label('tuesday', 'Tuesday') }}</label>
      <div class="col-sm-10"> {{ Form::select('tuesday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Monday']) }} </div>
    </div>
    <div class="form-group">
      <label for="wednesday" class="col-sm-2 control-label">{{ Form::label('wednesday', 'Wednesday') }}</label>
      <div class="col-sm-10"> {{ Form::select('wednesday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Wednesday']) }} </div>
    </div>
    <div class="form-group">
      <label for="thursday" class="col-sm-2 control-label">{{ Form::label('thursday', 'Thursday') }}</label>
      <div class="col-sm-10"> {{ Form::select('thursday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Thursday']) }} </div>
    </div>
    <div class="form-group">
      <label for="friday" class="col-sm-2 control-label">{{ Form::label('friday', 'Friday') }}</label>
      <div class="col-sm-10"> {{ Form::select('friday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Friday']) }} </div>
    </div>
    <div class="form-group">
      <label for="saturday" class="col-sm-2 control-label">{{ Form::label('saturday', 'Saturday') }}</label>
      <div class="col-sm-10"> {{ Form::select('saturday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Saturday']) }} </div>
    </div>
    <div class="form-group">
      <label for="sunday" class="col-sm-2 control-label">{{ Form::label('sunday', 'Sunday') }}</label>
      <div class="col-sm-10"> {{ Form::select('sunday[]', $facilitydropdown, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder' => 'Select facilities visited on Sunday']) }} </div>
    </div>
  </div>
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a>
    </button>
    {{ Form::submit('Create Daily Routing', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 