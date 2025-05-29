@extends('layouts.app')
@section('title', 'Results')
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
<div class="well firstrow list">
  <div class="row">
    {{ Form::open(array('route' => 'dailyrouting.resultlist', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
            {{ csrf_field() }}
   {{ Form::text('from', old('from'), ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
   {{ Form::text('to', old('to'), ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
	@role(['national_hub_coordinator','administrator']) 
    {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown autosubmitsearchform'])}}
    @endrole
   {{Form::select('facilityid', $facilities, old('facilityid'), ['class'=>'selectdropdown autosubmitsearchform'])}} 
   @role(['national_hub_coordinator','administrator']) 
   {{Form::select('districtid', $districts, old('districtid'), ['class'=>'selectdropdown autosubmitsearchform'])}} 
   @endrole
   	<button type="submit" id="searchbutton" class="btn btn-primary">Filters <i class="glyphicon glyphicon-filter"></i></button>
    {{ Form::close() }}
   
  </div>
  
</div>
  <div class="row" style="margin-right: 0; margin-left:0"> 
      <!-- Left col -->
      <section class="col-lg-9"> 
         <div id="resultstable" >
     <?php echo lava::render('ColumnChart', 'theresults', 'resultstable'); ?>
    </div>
      </section>
       <section class="col-lg-3"> 
        <h2 style="margin-bottom:5px; margin-left:5px;">Summary</h2>
         <div class="table-responsive">
         	<table class="table table-bordered">           	
                
                	@foreach($result_graph as $line)
                    <tr>
                    <td>{{$line->resulttype}}</td>
                    <td>{{$line->results}}</td>
                    
                    </tr>
					@endforeach
                
            </table>
         </div>
      </section>
      
     </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Hub</th>
          <th>Facility</th>
          <th>VL</th>
          <th>HIV EID</th>
          <th>GeneXpert</th>
          <th>Sickle Cell (SCD)</th>
          <th>CD4/CD8</th>          
          <th>CBC/FBC</th>
          <th>LFTS</th>
          <th>RFTS</th>
          <th>Culture & sensitivity</th>
          <th>MTB Culture & DST</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($results as $result)
      <tr>
        <td>{{$result->hub}}</td>
        <td>{{$result->facility}}</td>
        <td>{{$result->VL}}</td>
        <td>{{$result->HIVEID}}</td>
        <td>{{$result->Genexpert}}</td>
        <td>{{$result->SickleCell}}</td>
        <td>{{$result->CD4CD8}}</td>        
        <td>{{$result->CBCFBC}}</td>
        <td>{{$result->LFTS}}</td>
        <td>{{$result->RFTS}}</td>
        <td>{{$result->Culturesensitivity}}</td>
        <td>{{$result->MTBCultureDST}}</td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  <!-- /.box-body --> 
</div>
@endsection