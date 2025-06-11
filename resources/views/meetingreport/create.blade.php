@extends('layouts.app')

@section('title', 'Upload Report')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#meetingreportform').bootstrapValidator({
       
        fields: {
			name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter a name'
                        }
                    }
                }, 
			
				hubid: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a hub'
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
    		{{ Form::open(array('route' => 'meetingreport.store', 'class' => 'form-horizontal', 'id' => 'meetingreportform', 'enctype' => 'multipart/form-data')) }}
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="file" class="col-sm-2 control-label">{{ Form::label('file', 'File') }}</label>

                  <div class="col-sm-10">
                    {{ Form::file('file', ['class' => 'form-control', 'id' => 'file'])}}.
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Upload', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 