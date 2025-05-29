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
          {{ Form::text('thedate', null, ['class' => 'form-control', 'id' => 'routedate']) }}
        </div>
        <div class="col-xs-4">
          {{ Form::select('bikeid', $bikes, null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-4">
          {{ Form::select('transporterid', $transporters, null, ['class' => 'form-control']) }}
        </div>
      </div>
      
       <div class="row">
        <div class="col-xs-4">
          <h2>Facility Visited</h2>
          {{ Form::select('facilityid', $facilitydropdown, null, ['class' => 'form-control']) }}
        </div>
        <div class="col-xs-8">
          <h2>Reason(s)</h2>
            <div class='form-group'> @foreach ($routereasons as $id => $reason)
              {{ Form::checkbox('reasons[]',  $id, null, array('id'=>'reason'.$id)) }}
              {{ucfirst($reason) }}
              @endforeach 
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <h2>Samples and Results</h2>
            <div class="table-responsive">
                <table class="table table-">
                  <tr>
                      <td>Sample Category</td>
                      <td class="samples">No. Samples</td>
                      <td class="sesults">No. Results</td>
                  </tr>
                    @foreach ($samplecategories as $id => $samplecategory)
                    <tr>
                      <td>{{$samplecategory}}</td>
                      <td class="samples">{{ Form::text("samplecategories[".$id."][sample]", null, ['class' => 'form-control']) }}</td>
                      <td class="sesults">{{ Form::text("samplecategories[".$id."][result]", null, ['class' => 'form-control']) }}</td>
                    </tr>
                @endforeach 
              </table>
            </div>
        </div>
        
      </div>

  </div>
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a>
    </button>
    {{ Form::submit('Create Daily Routing', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 