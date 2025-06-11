@extends('layouts.app')

@section('title', 'Create Hub')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
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
    		{{ Form::open(array('route' => 'hub.store', 'class' => 'form-horizontal', 'id' => 'hubform')) }}
            {{ csrf_field() }}
              <div class="box-body">
              <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">{{ Form::label('name', 'Name') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="healthregionid" class="col-sm-2 control-label">{{ Form::label('healthregionid', 'Health Region') }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('healthregionid', $healthregions, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="ipid" class="col-sm-2 control-label">{{ Form::label('ipid', 'IP') }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('ipid', $ips, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="ipid" class="col-sm-2 control-label">{{ Form::label('parentid', 'Based on (Facility)') }}</label>
                  <div class="col-sm-10"> {{ Form::select('parentid', $facilities, null, ['class' => 'form-control']) }} </div>
                </div>
                                 
                <div class="form-group hidden">
                  <label for="address" class="col-sm-2 control-label">{{ Form::label('address', 'Address') }}</label>

                  <div class="col-sm-10">
                    {{ Form::textarea('address', null, array('class' => 'form-control', 'id' => 'address')) }}
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Create Hub', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 