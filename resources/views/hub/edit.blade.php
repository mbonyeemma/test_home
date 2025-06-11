@extends('layouts.app')

@section('title', 'Update Hub')
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
            {{ Form::model($hub, array('route' => array('hub.update', $hub->id),  'class' => 'form-horizontal', 'id' => 'hubform', 'method' => 'PUT')) }}
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
                    {{ Form::select('healthregionid', $healthregion, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="ipid" class="col-sm-2 control-label">{{ Form::label('ipid', 'IP') }}</label>

                  <div class="col-sm-10">
                    {{ Form::select('ipid', $ips, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div>
                 <div class="form-group hidden">
                  <label for="email" class="col-sm-2 control-label">{{ Form::label('email', 'Email Address') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('email', null, array('class' => 'form-control', 'id' => 'email')) }}
                  </div>
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
                {{ Form::submit('Update Hub', array('class' => 'btn btn-warning pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 