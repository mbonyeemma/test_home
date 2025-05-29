@extends('layouts.app')
@section('title', 'Total Hub Samples')
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
	});
	
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
<div class="well firstrow list">
  <div class="row">
    {{ Form::open(array('route' => 'ip.hubsamples', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
            {{ csrf_field() }}
    {{ Form::text('from', $from, ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
    {{ Form::text('to', $to, ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
    @role(['implementing_partner']) 
    {{Form::select('hubid', $hubs, $request->hubid, ['class'=>'selectdropdown autosubmitsearchform'])}}
    @endrole
   {{Form::select('facilityid', $facilities, $request->facilityid, ['class'=>'selectdropdown autosubmitsearchform'])}} 
    
   	<button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
    {{ Form::close() }}
   
  </div>
  
</div>
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Hub</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($samples as $sample)
      <tr>
        <td>{{$sample->hub}}</td>
        <td>{{$sample->total}}</td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  <!-- /.box-body --> 
</div>
@endsection