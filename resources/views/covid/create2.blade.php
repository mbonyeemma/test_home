@extends('layouts.app')

@section('title', 'Add Sample Details')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
    $('.date-field').datepicker({
       format: 'mm/dd/yyyy',
       endDate: '+0d',
       autoclose: true
    });
		$('#covidform').bootstrapValidator({
       
        fields: {
			
				facilityid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the facility to which this hub is attached'
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
    		
              <div class="box-body">
            {{ Form::open(array('route' => 'covid.store', 'class' => 'form-horizontal', 'id' => 'covidform')) }}
            {{ csrf_field() }}  
                
                <div class="form-group">
                  <label for="facilityid" class="col-sm-2 control-label">{{ Form::label('facilityid', 'Facility') }}</label>
                  <div class="col-sm-10"> {{ Form::select('facilityid', $facilities, null, ['class' => 'form-control']) }} </div>
                </div>
                                 
              <div class="form-group">
                <label for="insurance" class="col-sm-2 control-label">No. Samples</label>
                <div class="col-sm-10">
                  {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'number_of_samples')) }}
                </div>
              </div>

              <div class="form-group">
                <label for="insurance" class="col-sm-2 control-label">Transported On</label>

                <div class="col-sm-10">
                  {{ Form::text('transactiondate', old('transactiondate'), array('class' => 'form-control date-field', 'id' => 'transactiondate')) }}
                </div>
              </div>

             
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
                {{ Form::submit('Add Sample', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer transactiondate-->
            
            {{ Form::close() }}
          </div>
@endsection 