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
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script> 
<script src="{{ asset('js/select2.full.min.js') }}"></script> 
<script>
	$(document).ready(function() {
   // $(".select-field").select2();
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
				],
        "language": {
            //"zeroRecords": function(){alert('asdfs')},
                     
        }
			} );
    var table = $('#listtable').DataTable();
    $('#listtable').on('search.dt', function() {
        var value = $('.dataTables_filter input').val();
        //console.log(value); // <-- the value
    }); 
     @role(['cphl_sample_receptionist']) 
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
    $("select[name='hubid']").change(function(){
      var hubid = $(this).val();
      var hiddenvalue = $("input[name='_token']").val();
      
      $.ajax({
        url: "<?php echo url('dailyrouting/facilitiesforhub'); ?>",
        method: 'POST',
        data: {hubid:hubid, _token:hiddenvalue},
        success: function(data) {
            $("select[name='facilityid'").empty();
          $("select[name='facilityid'").html(data.options);
          }
        });
      });

    $("#is_to_be_transfered").change(function(){
      var opt = $(this).val();
      if(opt==1){
          $('.transfer_to').removeClass('hidden');
          $('#transfer_to').attr("required", "required");          
      }else{
         $('.transfer_to').addClass('hidden');
         $('#transfer_to').removeAttr( "required");

      }
    });

    //var totalRecords =$("#listtable").DataTable().page.info().recordsTotal;
    //test if dataTable is empty
    //(totalRecords === 0)? alert("table is empty") : alert("table is not empty");
    //var table = $('#listtable').DataTable();
    //var info = table.page.info();
    //alert(info.);

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
<div class="well firstrow list">
  <div class="row">
    {{ Form::open(array('route' => 'samples.cphl', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
            {{ csrf_field() }}
   {{ Form::text('from', old('from'), ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
   {{ Form::text('to', old('to'), ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
   {{Form::select('status', $status_dropdown, old('status'), ['class'=>'selectdropdown autosubmitsearchform'])}}
   
@role(['national_hub_coordinator','administrator']) 
    {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown autosubmitsearchform'])}}
    @endrole
   @role(['national_hub_coordinator','administrator'])  
   @endrole
    
   	<button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
    {{ Form::close() }}
   
  </div>
  
</div>
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>
            <th>Envelope ID</th>
            <th>No.Envelopes</th>
          <th>From</th>
          <th>Picked on</th>
          <th>Status</th>
          <th>Received at</th>
         
        </tr>
      </thead>
      <tbody>      
      @foreach ($samples as $sample)
      <tr>
      <td>{{$sample->barcode}}</td>
      <td>{{$sample->numberofenvelopes}}</td>
        <td>{{$sample->hubname}}</td>
        <td>{{getPageDateFormat($sample->thedate)}}</td>
        <td> @if($sample->status == 1)
        In transit to CPHL
        @elseif($sample->status == 2)
        Delivered to CPHL
        @elseif($sample->status == 3)
        Received at CPHL
        @else
       Waiting Pickup 
        @endif</td>
        <td>@if($sample->recieved_at != '')
        {{$sample->recieved_at}}
        @else
        {{$sample->delivered_at}}
        @endif</td>
        @role(['hub_coordinator','cphl_sample_receptionist'])
        <td>    
        
        </td>
        @endrole
      </tr>
      @endforeach
        </tbody>      
    </table>
  </div>
  <!-- /.box-body --> 
</div>


<!-- The Modal -->
<div class="modal" id="no_barcode">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Barcode not Scanned</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->

      <div class="modal-body">
        <p>The barcode was not scanned so, record its details here for follow-up</p>
        {{ Form::open(array('route' => 'samples.saveunscannedbarcode', 'class' => '', 'id' => 'unscanned')) }}
            {{ csrf_field() }}
            <div class="form-group">
              <label for="hub" class="control-label">Hub</label>
              <div>
                {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'form-control input-lg select-field'])}}                     
              </div>
            </div>
             <div class="form-group">
              <label for="hub" class="control-label">Facility</label>
              <div>
                {{Form::select('facilityid', $all_facilities, old('facilityid'), ['class'=>'form-control input-lg select-field', 'required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="hub" class="control-label">Sample Type</label>
              <div>
                {{Form::select('test_type', $test_types, old('test_type'), ['class'=>'form-control input-lg select-field', 'required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="hub" class="control-label">Is to be transfered?</label>
              <div>
                {{Form::select('is_to_be_transfered', [0=>'No',1=>'Yes'], old('is_to_be_transfered'), ['class'=>'form-control input-lg select-field', 'id'=>'is_to_be_transfered','required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group transfer_to hidden">
              <label for="hub" class="control-label">Transfer to</label>
              <div>
                {{Form::select('transfer_to', $all_facilities, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="insurance" class="control-label">Barcode</label>

              <div>
                {{ Form::text('barcode', old('barcode'), array('class' => 'form-control', 'id' => 'barcode','required'=>'required')) }}
              </div>
            </div>            

            <div class="form-group">
              <label for="insurance" class="control-label">Number of Samples</label>

              <div>
                {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'numberofsamples')) }}
              </div>
            </div>
            <div class="form-group">
                <div>
                  {{ Form::hidden('type', 2) }}
                    <button type="submit" id="submit_form" class="btn btn-primary">Submit </button>
                    </button>
                </div>
            </div>
            {{ Form::close() }}
      </div>
      <div class="form-group">
        <label for="hub" class="col-sm-3 control-label"></label>
        <div class="col-sm-9">
          
        </div>
    </div>
  </div>
</div>
@endsection
