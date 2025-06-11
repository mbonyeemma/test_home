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
		$('#dailyroutingform').bootstrapValidator({				
			fields: {
				 thedate: {
				validators: {
					notEmpty: {
						message: 'Please select a date'
					}
				  }
				 },
				facilityid: {
				validators: {
					notEmpty: {
						message: 'Please select the facility'
					}
				  }
			  },
				bikeid: {
				validators: {
					notEmpty: {
						message: 'Please select the motor cycle'
					}
				  }
			  },
			  fields: {
				transporterid: {
				validators: {
					notEmpty: {
						message: 'Please select the sample transporter'
					}
				  }
			  }
			}//endo of validation rules
		}
		});//close form validation function
		
		var rejectReason = $('#action-point');
     var reasonRow = rejectReason.children(":first");
     var reasonRowTemp = reasonRow.clone();
     reasonRow.find('button.remove-reason').remove();
     
     // nb can't use .length for inputCount as we are dynamically removing from middle of collection
     var inputCount = 1;

     $('#add-action').click(function () {
       var newRow = reasonRowTemp.clone();
       inputCount++;
       newRow.find('select.rejectionReason').attr('placeholder', 'Select '+inputCount);
       rejectReason.append(newRow);
       
     });  
     
     $('#action-point').on('click', 'button.remove-reason', function () {
       $(this).parent().remove();
     }); 
});
</script> 
@append
@section('content')
<div class="box box-info"> 
  
  <!-- /.box-header --> 
  <!-- form start --> 
  {{-- Using the Laravel HTML Form Collective to create our form --}}
  {{ Form::open(array('route' => 'dailyrouting.store', 'class' => 'form-horizontal', 'id' => 'dailyroutingform')) }}
  {{ csrf_field() }}
  <div class="box-body">
    <div class="row">
      <div class="col-xs-4"> {{ Form::text('thedate', null, ['class' => 'form-control', 'id' => 'routedate']) }} </div>
      <div class="col-xs-4"> {{ Form::select('bikeid', $bikes, null, ['class' => 'form-control']) }} </div>
      <div class="col-xs-4"> {{ Form::select('transporterid', $transporters, null, ['class' => 'form-control']) }} </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <h2>Reason(s)</h2>
        @foreach ($routereasons as $id => $reason)
        {{ Form::checkbox('reasons[]',  $id, null, array('id'=>'reason'.$id)) }}
        {{ucfirst($reason) }}
        @endforeach </div>
    </div>
    <div class="row">
        <div class="col-xs-3">	
        	<h2>Facility Visited</h2>
        </div>
        <div class="col-xs-5">	
        	<h2>Samples</h2>
        </div>
        <div class="col-xs-4">	
        	<h2>Results</h2>
        </div>
     </div>
    <div id="action-point">
    
      <div class="row">
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-3">
              {{ Form::select('facilityid', $facilitydropdown, null, ['class' => 'form-control']) }} </div>
            <div class="col-xs-9">
              <div class="row">
                <div class="col-xs-6">
                  
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="form-group">
                        <div class="input-group"> {{ Form::select('samples[]', $samplecategories, null, ['class' => 'form-control']) }}
                          <div class="input-group-addon"> {{ Form::text('numberofsamplesfor', null, ['class' => '']) }} </div>
                        </div>
                        <!-- /.input group --> 
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <div class="input-group my-colorpicker2 colorpicker-element">
                        <input class="form-control" type="text">
                        <div class="input-group-addon"> <i></i> </div>
                      </div>
                      <!-- /.input group --> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{Form::button('<i class="fa fa-fw fa-remove"></i> Remove', ['class' => 'btn btn-danger btn-sm remove-reason btn-normal'])}} </div>
    </div>
    <div><a href="#" id="add-action">Add facility</a></div>
  </div>
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a>
    </button>
    {{ Form::submit('Create Daily Routing', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 