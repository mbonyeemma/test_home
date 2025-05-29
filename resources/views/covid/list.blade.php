@extends('layouts.app')
@section('title', 'Samples')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
@append
@section('listpagejs') 
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$('#listtable').DataTable( {
				dom: 'Bflrtip',
				buttons: [
					
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'pdfHtml5',
						exportOptions: {
							columns: ':visible'
						}
					},
					'colvis'
				]
			} );
		$('.filter-date').datepicker({
		   format: 'mm/dd/yyyy',
		   endDate: '+0d',
		   autoclose: true
		});
	@role(['cphl_sample_reception']) 
		$(".sample").click(function(){
			var sampleid = $(this).attr('id');
			$('#samplemodal_' + sampleid).modal('show');
		});
			
    var table = $('#listtable').DataTable();
    $('#listtable').on('search.dt', function() {
        var value = $('.dataTables_filter input').val();
        //console.log(value); // <-- the value
    }); 

    $('.dataTables_filter input').unbind().keyup(function() {
        var value = $(this).val();
        //only start to search if string is at least 12 characters
        if (value.length>12) {
            table.search(value).draw();
            var info = table.page.info();
            //var rowstot = info.recordsTotal;
            //alert("rowstot: " + rowstot);
            var rowsshown = info.recordsDisplay;
          if(rowsshown == 0){
            //use record details of the unscanned barcode
            //alert('no results');
            $('#barcode').val(value);
            $('#no_barcode').modal('show');
          }
        } 

        if (value.length==0) table.search('').draw();

    });
    @endrole

    //on selecting a hub, get the facilities it serves
    $("select[name='facilityid']").change(function(){
      var facilityid = $(this).val();
      var hiddenvalue = $("input[name='_token']").val();
      
      $.ajax({
        url: "<?php echo url('dailyrouting/hubforfacility'); ?>",
        method: 'POST',
        data: {facilityid:facilityid, _token:hiddenvalue},
        success: function(data) {
            $("#id_district").html(data.districtname);
            $("#id_hub").html(data.hubname);
            $("select[name='hubid'").val(data.hubid);
          }
        });
      });
    
      $("#listtable").on("click", ".rec_sample", function(eve) {
          eve.preventDefault();
          $('#no_samples #the_id').val(event.target.id);
          $('#no_samples').modal('show');
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
    {{ Form::open(array('route' => 'covid.process_list_filters', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
            {{ csrf_field() }}
   
   	{{ Form::text('from', $from, ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}

   {{ Form::text('to', $to, ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}

  
   {{Form::select('facilityid', $facilities, $request->facilityid, ['class'=>'selectdropdown autosubmitsearchform'])}} 
   {{Form::select('test_type', $test_types, $request->test_type, ['class'=>'selectdropdown autosubmitsearchform'])}} 
   
    
   	<button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
    {{ Form::close() }}
   
  </div>
  
</div>
  
 <div class="row"> 
    <div class="col-md-9"><!-- /.box-header -->
      <div class="box-body table-responsive">
        <table id="listtable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Facility</th>
              <th>District </th>
              <th>Date Transported</th>
              <th>Sample Type</th>
              <th>No. Samples</th>
              <th class="hidden">Action</th>
              
            </tr>
          </thead>
          <tbody>      
          @foreach ($samples as $sample)
          <tr>
          <td>{{$sample->facility}}</td>
            <td>{{$sample->district}}</td>       
            <td>{{$sample->transactiondate}}</td>
            <td>{{$sample->test_type}}</td>
            <td>{{$sample->numberofsamples}}</td>     
           
            <td class="hidden">   
                <a href="{{ route('covid.edit',$sample->id) }}" id="{{$sample->id}}" class="rec_sample">Update</a>
            
            </td>
          
          </tr>
          @endforeach
            </tbody>      
        </table>
      </div>
    </div><!-- End of col-md-9 -->
    <div class="col-md-3">
      <h2 style="margin-bottom:5px; margin-left:5px;">Summary</h2>
      <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
          <th>Sample Type</th>
          <th>No. samples</th>
        </tr>
        @foreach($sample_summary_totals as $line)
        <tr>
          <td>{{$line->sampletype}} </td>
          <td>{{$line->samples}} </td>
        </tr>
        @endforeach
        </table>
      </div>
    </div>
  </div><!-- /.box-body --> 
</div>



@endsection