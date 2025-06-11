@extends('layouts.app')

@section('title', 'Receive Sample')
@section('title', 'Create Permission')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
	$(document).ready(function() {
		$('#recform').bootstrapValidator({
       
			fields: {
					code: {
						validators: {
							notEmpty: {
								message: 'Please select atleast one permission'
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
  {{ Form::open(array('route' => 'samples.processreceipt', 'id' => 'recform')) }}
  <div class="box-body">
    <div class="form-group"> {{ Form::label('code', 'Sample Code') }}
      {{ Form::text('code', null, array('class' => 'form-control')) }} </div>
   
  </div>
  <!--/box-body -->
  <div class="box-footer"> <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Cancel</a> {{ Form::submit('Receive', array('class' => 'btn btn-sm btn-info pull-right')) }}
    
    {{ Form::close() }} </div>
  <!-- /.box-footer --> 
</div>
@endsection 