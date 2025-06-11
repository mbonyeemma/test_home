@extends('layouts.app')
@section('title', 'Report Breakdown')
@section('js') 
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script> 
<script>
	$(document).ready(function() {		
		//chec if mechanic is contacted and display details about the mechanic
		$('#action1').click(function(){
			if($('#action1').is(":checked")){
				$('#mechan').removeClass('hidden');
			}else{
				$('#mechan').addClass('hidden');
			}
		});		
		
		$('#breakdown').bootstrapValidator({  
			icon: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},     
			fields: {
				
				datebrokendown: {
						validators: {
							notEmpty: {
								message: 'date is required'
							}
						}
					},										
					datereported: {
						validators: {
							notEmpty: {
								message: 'date is required'
							},
							date: {
								message: 'The date is not valid',
								format: 'MM/DD/YYYY'
							},
							callback: {
								message: 'The report date should be the same as, or after the break down date',
								callback: function(value, validator) {									
									return comparedate($('#datereported').val(),$('#datebrokendown').val());
								}
							}
						}
					}
			}//endo of validation rules
		});// close form validation function
		
		$('.datefield1,.datefield2').on('changeDate show', function(e) {
		   $('#breakdown').bootstrapValidator('revalidateField', 'datebrokendown');
		 });	
		 $('.datefield2,.datefield1').on('changeDate show', function(e) {
			// alert($('#datebrokendown').val());
		   $('#breakdown').bootstrapValidator('revalidateField', 'datereported');
		 });
		 //http://bladephp.co/remote-call-using-bootstrap-validator
		 //http://bootstrapvalidator.votintsev.ru/settings/
	});
</script> 
@append
@section('content')
<div class="box box-info"> 
  <!-- form start --> 
  {{ Form::open(array('url' => 'equipment/savebreakdown', 'class' => 'form-horizontal', 'id' => 'breakdown')) }}
  {{ csrf_field() }}
  <h2 class="section">Enter the relevant dates</h2>
  <div class="form-group">
      <label style="text-align:left; margin-left:16px; width:16%; font-weight:400;" for="datebrokendown" class="col-sm-3 control-label">{{ Form::label('datebrokendown', 'Date Broken Down') }}</label>
      <div class="col-sm-9">
        
        <input name="datebrokendown" id="datebrokendown" class="form-control datefield1" type="text">
      </div>
    </div>
    <div class="form-group">
      <label style="text-align:left; margin-left:16px; width:16%; font-weight:400;" for="datereported" class="col-sm-3 control-label">{{ Form::label('datereported', 'Date Reported') }}</label>
      <div class="col-sm-9">
        
        <input name="datereported" id="datereported" class="form-control datefield2" type="text">
      </div>
    </div>
  <h2 class="section">Specify reason(s) why motor bike is not functional <i>(tick all that apply)</i>:</h2>
  <ul class="horizontal-checkbox">
  @foreach($reasons_for_breakdown as $key=>$value)
      <li>
        <input name="reasonforbreakdown[]" type="checkbox" value="{{$key}}" id="reason{{$key}}" class="reasonforbreakdown">
        {{$value}}</li>
  @endforeach 
  </ul>
  <div class="clear"></div>
  
  
  <h2 class="section">Specify actions taken at the hub:</h2>
  <ul class="horizontal-checkbox bottom-spaced">
  @foreach($actions_taken_for_breakdown as $key=>$value)
      <li>
        <input name="actionstaken[]" type="checkbox" value="{{$key}}" id="action{{$key}}">
        {{$value}}</li>
  @endforeach 
  </ul>
  
  <div id="mechan" class="hidden">
  	<div class="form-group">
          <label for="mechanicid" style="text-align:left; margin-left:16px; width:8%; font-weight:400;" class="col-sm-3 control-label">{{ Form::label('mechanicid', 'Mechanic') }}</label>

          <div class="col-sm-10">
            {{ Form::select('mechanicid', $mechanics, null, ['class' => 'form-control']) }}
             
          </div>
        </div>
  </div>
  <div class="clear"></div>
  
  
  
  <!-- /.box-body -->
  <div class="box-footer"> <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
    </button>
    {{ Form::hidden('hubid', $hubid) }}
    {{ Form::hidden('id', $id) }}
    {{ Form::submit('Report Breakdown', array('class' => 'btn btn-info pull-right')) }} </div>
  <!-- /.box-footer --> 
  
  {{ Form::close() }} </div>
@endsection 