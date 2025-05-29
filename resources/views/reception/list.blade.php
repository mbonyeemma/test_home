@role(['national_hub_coordinator','administrator']) 
          {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown autosubmitsearchform'])}}
        @endrole
       @role(['national_hub_coordinator','administrator'])  
       @endrole



       @extends('layouts.app')
@section('title', 'Samples Pending Receipt')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
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
   $('.select2').select2();
    // $('#listtable').( {
    //     dom: 'Bflrtip',
    //     buttons: [
          
    //       {
    //         extend: 'excelHtml5',
    //         exportOptions: {
    //           columns: ':visible'
    //         }
    //       },
    //       {
    //         extend: 'pdfHtml5',
    //         exportOptions: {
    //           columns: ':visible'
    //         }
    //       },
    //       'colvis'
    //     ],
    //     "language": {
    //         //"zeroRecords": function(){alert('asdfs')},
                     
    //     }
    //   } );
    // var table = $('#listtable').DataTable();
    // $('#listtable').on('search.dt', function() {
    //     var value = $('.dataTables_filter input').val();
    //     //console.log(value); // <-- the value
    // }); 
     @role(['cphl_sample_receptionist']) 
    $('.dataTables_filter input').unbind().keyup(function(e) {
        var value = $(this).val();
        //only start to search if string is at least 12 characters
        if (e.keyCode == 13) {
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

    $("#search").keyup(function(){
      var opt = $(this).val();
      if(opt != ''){
        $("#searchform").submit();
      }
      
    });



    //var totalRecords =$("#listtable").DataTable().page.info().recordsTotal;
    //test if dataTable is empty
    //(totalRecords === 0)? alert("table is empty") : alert("table is not empty");
    //var table = $('#listtable').DataTable();
    //var info = table.page.info();
    //alert(info.);

    $('.filter-date, .date_field').datepicker({
       format: 'yyyy-mm-dd',
       endDate: '+0d',
       autoclose: true
    });
    
    $(".sample").click(function(){
      var sampleid = $(this).attr('id');
      $('#samplemodal_' + sampleid).modal('show');
    });

    $("#pop_facilityid").change(function(){
      var facility_id = $('#pop_facilityid').val();
      if(facility_id!=''){
        var url =  "/samples/get_district_hub/"+facility_id;
        $.get(url, function(data, status){
          var json_data = JSON.parse(data);
          $("#id_district").html(json_data.district);
          $("#id_hub").html(json_data.hub);
        }); 
      }
    });
        
  } );
  
</script> 
@append
<style>
  #searchbutton{
    margin-top: -4px;
  }
  .input-field{
    width:200px;
  }
  .selectdropdown{
    width:200px;
  }
  .search{
    width:220px;
    border-radius: 4px;
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
  @if(Session::has('success'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{!! Session::get('success') !!}</strong>
  </div>
@endif
<div class="well firstrow list">
  <div class="row">
    {{ Form::open(array('route' => 'reception.list', 'class' => 'form-search', 'id' => 'searchform')) }}
            {{ csrf_field() }}
      <div class="col-sm-2" style="padding-left: 0;">
        {{ Form::text('from', old('from'), ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
      </div>
      <div class="col-sm-2">
        {{ Form::text('to', old('to'), ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }} 
      </div>
      <div class="col-sm-2" style="padding-right: 2em;">
        {{Form::select('status', $status_dropdown, old('status'), ['class'=>'selectdropdown autosubmitsearchform'])}}
      </div>
      <div class="col-sm-2" style="padding-right: 2em;">
        {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown autosubmitsearchform'])}}
      </div>
      <div class="col-sm-2 col-md-offset-1">
        {{ Form::text('search', old('search'), array('class' => 'form-control search', 'id'=>'search', 'placeholder' => 'Search')) }}
      </div>
      <div class='col-md-1'>
        {{ Form::submit(trans('Search Here'), array('class'=>'btn btn-primary' )) }}
      </div> 
    {{ Form::close() }} 
  </div>
</div>  
  <!-- /.box-header -->
  <div class="box-body">
    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr>
          <th>Envelope ID</th>
          <th>No.Envelopes/Samples</th>
          <th>Picked From</th>
          <th>Picked on</th>
          <th>Hub</th>
          <th>Status</th>
          <th>Received at</th>
          @role(['hub_coordinator','cphl_sample_receptionist'])
          <th>Action</th>
          @endrole
        </tr>
      </thead>
      <tbody>      
      @foreach ($packages as $sample)
      <tr>
      <td>{{$sample->barcode}}</td>
      <td>
        @if($sample->numberofenvelopes == 0)
          {{$sample->numberofsamples}}
        @else
          {{$sample->numberofenvelopes}}
        @endif
      </td>
        <td>{{ $sample->facility->name }}</td>
        <td>{{ getPageDateFormat($sample->created_at)}}</td>
        @if($sample->facility->hub == '')
        <td>{{ $sample->facility->name}}</td>
        @else
        <td>{{ $sample->facility->hub->name}}</td>
        @endif
        <td>
        @if($sample->status == 1)
          In transit 
        @elseif($sample->status == 2 && $sample->final_destination == $sample->packageMovementEvent->location)
          Delivered 
        @elseif($sample->status == 3 && $sample->final_destination == $sample->packageMovementEvent->location)
          Received 
        @else
         Waiting Pickup 
        @endif
        </td>
        <td>@if($sample->received_at_destination_on != '')
        {{$sample->received_at_destination_on}}
        @else
        {{$sample->delivered_on}}
        @endif</td>
        @role(['hub_coordinator','cphl_sample_receptionist'])
        <td>    
        @role(['cphl_sample_receptionist'])
          @if($sample->final_destination != $sample->packageMovementEvent->location || ($sample->final_destination == $sample->packageMovementEvent->location && $sample->status != 3 ))
          <a class="btn btn-xs btn-info" href="{{ route('reception.receivesample',$sample->id) }}">
              {{ trans('Receive') }}
          </a>
          @endif
        @endrole
        </td>
        @endrole
      </tr>
      @endforeach
        </tbody>      
    </table>
    {{ $packages->links() }}
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
        {{ Form::open(array('route' => 'reception.saveunscannedbarcode', 'class' => '', 'id' => 'unscanned')) }}
            {{ csrf_field() }}
           
             <div class="form-group">
              <label for="hub" class="control-label">Facility</label>
              <div>
                {{Form::select('facilityid', $all_facilities, old('facilityid'), ['class'=>'form-control select-field select2', 'required'=>'required','id'=>'pop_facilityid','style'=>'width:100%;'])}}                     
              </div>
            </div>
             
            <div class="col-lg-4" style="padding-top: 24px;">
              <label for="district">District:</label>
              <div>
              <u id="id_district" style="width: 100%">_____________________________________</u>
            </div>
            </div>

            <div class="form-group" style="padding-top: 24px;">
              <label for="hub">Hub:</label>
              <div>
                <u id="id_hub" style="width: 100%">________________________________</u>
              </div>
            </div>

            <div class="form-group">
              <label for="hub" class="control-label">Sample Type</label>
              <div>
                {{Form::select('test_type', $test_types, old('test_type'), ['class'=>'form-control select-field', 'required'=>'required','style'=>'width:100%;'])}}                     
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
                {{Form::select('transfer_to', $ref_labs, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}                     
              </div>
            </div>

             <div class="form-group">
              <label for="picked_on" class="control-label">Date Picked from Facility</label>

              <div>
                {{ Form::text('picked_on', old('picked_on'), array('class' => 'form-control date_field', 'id' => 'picked_on','required'=>'required')) }}
              </div>
            </div> 
             <div class="form-group">
              <label for="delivered_on" class="control-label">Date Delivered</label>

              <div>
                {{ Form::text('delivered_on', old('delivered_on'), array('class' => 'form-control date_field', 'id' => 'delivered_on','required'=>'required')) }}
              </div>
            </div> 

            <div class="form-group">
              <label for="barcode" class="control-label">Barcode</label>

              <div>
                {{ Form::text('barcode', old('barcode'), array('class' => 'form-control', 'id' => 'barcode','required'=>'required')) }}
              </div>
            </div>            

            <div class="form-group">
              <label for="numberofsamples" class="control-label">Number of Samples</label>

              <div>
                {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'numberofsamples')) }}
              </div>
            </div>
            <div class="form-group">
                <div>
                  {{ Form::hidden('type', 2) }}
                  {{ Form::hidden('is_tracked_from_facility', 0) }}
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
