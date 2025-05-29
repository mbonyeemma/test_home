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
			date: {
            validators: {
                notEmpty: {
                    message: 'Please select a date'
                }
              }
          },
        bikeid: {
            validators: {
                notEmpty: {
                    message: 'Please select the bike'
                }
              }
          },
           bikeid: {
            validators: {
                notEmpty: {
                    message: 'Please select the bike'
                }
              }
          },
      email: {
			email: {          
			validators: {
					regexp: {
					  regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
					  message: 'The value is not a valid email address'
					}
				}
			}
	  }
		}//endo of validation rules
    });// close form validation function
	
	
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
        <div class="col-xs-4">
          {{ Form::text('date', null, ['class' => 'form-control', 'id' => 'routedate']) }}
        </div>
        <div class="col-xs-4">
          {{ Form::select('bikeid', $bikes, null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
          {{ Form::select('trans', $transporters, null, ['class' => 'form-control']) }}
        </div>
      </div>
      
      <div id="action-point">
       <div class="row">
       <div class="form-group">
            <div class="col-xs-2">
            	{{ Form::select('facilityid', $facilitydropdown, null, ['class' => 'form-control']) }}
            </div>
            
            <div class="col-xs-3">
            {{ Form::select('samples[]', $samplecategories, null, ['class' => 'form-control']) }}
            {{ Form::text('numberofsamplesfor', null, ['class' => 'form-control']) }}
            </div>
            <div class="col-xs-3">
            {{ Form::select('samples[]', $samplecategories, null, ['class' => 'form-control']) }}
            {{ Form::text('numberofresults', null, ['class' => 'form-control']) }}
            </div>
        </div>
        
        {{Form::button('<span class="btn btn-primary"></span> Remove', ['class' => 'remove-reason btn-normal'])}}
       </div>
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