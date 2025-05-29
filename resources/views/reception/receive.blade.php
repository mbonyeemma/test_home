@extends('layouts.app')
@section('title', 'Samples')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('listpagejs') 
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
<script>
  $(document).ready(function() {
    $('#listtable').DataTable();
    $('.filter-date').datepicker({
       format: 'mm/dd/yyyy',
       endDate: '+0d',
       autoclose: true
    });
    
    
    $("#is_to_be_transfered").change(function(){
      var opt = $(this).val();
      if(opt==1){
          $('.transfer_to, .no_s,#package').removeClass('hidden');
          $('#package').addClass('hidden');
          $('#transfer_to').attr("required", "required");          
      }else{
         $('.transfer_to, .no_s').addClass('hidden');
         $('#package').removeClass('hidden');
         $('#transfer_to').removeAttr( "required");
         $('#numberofsamples').attr("required", "required");;

      }
    });
        
  } );
  
</script> 
@append
<style>
	
	.form-horizontal .form-group {
    margin-right: 0; 
    margin-left: 10px; 
  }
  #numberofsamples,#is_to_be_transfered,#transfer_to{
    width: 30%;
  }
</style>
<div class="box box-info">

  
  <!-- /.box-header -->
  {{ Form::open(array('route' => 'reception.processreceipt', 'class' => 'form-horizontal', 'id' => 'hubform')) }}
  <div class="box-body">
    <div class="form-group">
      <label for="hub" class="control-label">Is to be transfered?</label>
      <div>
        {{Form::select('is_to_be_transfered', ['Select One'=>'',0=>'No',1=>'Yes'], old('is_to_be_transfered'), ['class'=>'form-control input-lg select-field', 'id'=>'is_to_be_transfered','required' => 'required'])}}                     
      </div>
    </div>
    <div class="form-group transfer_to hidden">
      <label for="ref_lab" class="control-label">Transfer to</label>
      <div>
        {{Form::select('transfer_to', $ref_labs, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}                     
      </div>
    </div>
     <div class="form-group">
        <label for="insurance" class="control-label hidden no_s">Number of Samples</label>

        <div>
          {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control hidden no_s', 'id' => 'numberofsamples')) }}
        </div>
      </div>
    <div class="box-body table-responsive">
      <table  class="table table-striped table-bordered hidden" id="package">
        <thead>
          <tr>
              <th>Envelope ID</th>
              <th>Number of Samples</th>
          </tr>
        </thead>
        <tbody>      
        @foreach ($packages as $package)
        <tr>
        
          <td>       
             {{$package->barcode}}    
          </td>
          <td>
            <input type="text" value="" name="packages[{{$package->id}}][number_of_samples]">
            <input type="hidden" value="{{$package->id}}" name="packages[{{$package->id}}][small_package_id]">
             <input type="hidden" value="{{$id}}" name="big_package_id">
          </td>
        </tr>
        @endforeach
        <tr>
          <td></td>
          <td>
            {{ Form::hidden('is_tracked_from_facility', 0) }}
            {{ Form::submit('Submit', array('class' => 'btn btn-info ')) }}
          </td>
        </tr>
          </tbody>      
      </table>
    </div>
  </div>
  {{ Form::close() }}
  <!-- /.box-body --> 
</div>
@endsection

