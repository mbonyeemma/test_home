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
		
		$(".sample").click(function(){
			var sampleid = $(this).attr('id');
			$('#samplemodal_' + sampleid).modal('show');
		});
				
	} );
	
</script> 
@append
<style>
	#searchbutton{
		margin-top: -4px;
	}
	.input-field{
		width:100px;
	}
	.selectdropdown{
		width:200px;
	}
	.input-field, .selectdropdown {
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
        border-top-color: rgb(204, 204, 204);
        border-right-color: rgb(204, 204, 204);
        border-bottom-color: rgb(204, 204, 204);
        border-left-color: rgb(204, 204, 204);
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
</style>
<div class="box box-info">

  
  <!-- /.box-header -->
  {{ Form::open(array('route' => 'samples.processreceipt', 'class' => 'form-horizontal', 'id' => 'hubform')) }}
  <div class="box-body table-responsive">
    <table  class="table table-striped table-bordered">
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
        <td>{{ Form::submit('Submit', array('class' => 'btn btn-info ')) }}</td>
      </tr>
        </tbody>      
    </table>
  </div>
  {{ Form::close() }}
  <!-- /.box-body --> 
</div>
@endsection