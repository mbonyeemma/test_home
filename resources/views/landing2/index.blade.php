@extends('layouts.app4')
@section('title', 'Sample Tracking Dashboard')
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
<script src="{{ asset('js/select2.full.min.js') }}"></script> 
<script>
  $(document).ready(function() {
    $('.select2').select2();
    $('#listtable').DataTable( {
        dom: 'Bflrtip',
        order: [[ 9, "desc" ]],
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

      
    $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/outbreak'); ?>",
      success: function(data) {
        $('#outbreak').html(data);        
      }
    }); 
    $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/covid_stats/2'); ?>",
      success: function(data) {
        $('#deliverd').html(data);        
      }
    });
    $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/covid_stats/3'); ?>",
      success: function(data) {
        $('#received').html(data);        
      }
    });
    $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/covid_stats/1'); ?>",
      success: function(data) {
        $('#in_transit').html(data);        
      }
    });

  });
  //on clicking the sample_id, load details of movement
    function trace_sample(pacakge_id){

       // AJAX request
       $.ajax({
        url: '<?php echo url('sampletracking/trace_sample'); ?>/'+pacakge_id,
        type: 'GET',
        success: function(response){ 
          // Add response in Modal body
          $('.modal-body').html(response);

          // Display Modal
          $('#pgmovement').modal('show'); 
        }
      });
      
    }
    function getPackageSamples(pacakge_id){
      // Display Modal
          $('#pgmovement').modal('show'); 
       // AJAX request
       $.ajax({
        url: '<?php echo url('sampletracking/get_package_details'); ?>/'+pacakge_id,
        type: 'GET',
        success: function(response){ 
          // Add response in Modal body          
          $('.modal-body').html(response);

          
        }
      });
    }
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

.m{
  width: 100%;
    height: 7px;
    background: #ffffff url(/imag/strip-flag.png) top left repeat-x;
    background-size: 5px;
}
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 400px;
    overflow-y: auto;
}
</style>
<section class="content">
  <div class="alert alert-info alert-dismissible hidden">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fa fa-info"></i> Important Note!</h5>
    The totals in the graph below are the samples that have been transported to UVRI using the National Sample Transport Network. There are other samples which may have been transported to UVRI using other means, depending on urgency of the sample. For detailed information about the COVID, please visit <a href="https://covid19.gou.go.ug/" target="_blank">https://covid19.gou.go.ug/</a> </div>
  
  
  <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-stats-bars"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">In Transit</span>
              <span class="info-box-number" id="in_transit"><img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-stats-bars"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Delivered this month</span>
              <span class="info-box-number" id="deliverd"><img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-stats-bars"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Received this month</span>
              <span class="info-box-number" id="received"><img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-stats-bars"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Covid 19 captured manually</span>
              <span class="info-box-number" id="outbreak">
                <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
  </div>
  <div class="row">
    <div class="col-md-12"> 
      <div class="box box-success">
        <div class="box-body chart-responsive">
      <div class="well firstrow list">
          <div class="row">
            <div class="col-md-12"> {{ Form::open(array('route' => 'home', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
              {{ csrf_field() }}
              
              {{ Form::text('from', $from, ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
              
              {{ Form::text('to', $to, ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
              
              {{Form::select('facilityid', $facilities, $request->facilityid, ['class'=>'select2 selectdropdown autosubmitsearchform'])}}
              {{Form::select('poe', $poes, $request->poe, ['class'=>'select2 selectdropdown autosubmitsearchform'])}}
              {{Form::select('ref_lab', $ref_labs, $request->ref_lab, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              {{Form::select('site_type', $site_types, $request->site_type, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              {{Form::select('sample_type', $sample_types, $request->sample_type, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              <button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
              {{ Form::close() }} 
            </div>
          </div>
      </div>
    </div>
  </div>
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Latest Sample Status and Location</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="box-body table-responsive">
          <table id="listtable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Package ID</th>
                <th class="hidden">Sample ID</th> 
                <th>Collection Point</th>               
                <th>Collection Point Name</th>
                <th>District</th>
                <th>Hub</th>
                <th>Destination</th>
                <th>Test Type</th>
                <th>No. Samples</th>
                <th>Picked at</th>
                <th>Status</th>
                <th>Last seen on</th>
                <th>Last seet at</th>
                <th>Delivered on</th>
                <th>Received at</th>
                <th>TAT</th>
            </tr>
            </thead>
            <tbody>    

              @foreach ($package_samples as $sample)
                <tr>
                  <td><a href="#" data-id="{{$sample->container}}" class="sample_display" onclick="getPackageSamples({{$sample->id}})">{{$sample->container}}</a></td>
                  <td class="hidden"><a href="#" data-id="{{$sample->id}}" class="sample_display" onclick="trace_sample({{$sample->id}})">{{$sample->sample_id}}</a></td>
                  <td>{{$sample->collection_point}}</td>
                  <td>{{$sample->collection_point_name}}</td>
                  <td>{{$sample->district}}</td>
                  <td>{{$sample->hub}}</td>
                  <td>{{$sample->final_destn}}</td>
                  <td>{{$sample->sampletype}}</td>
                  <td>{{$sample->total}}</td>
                  <td>{{$sample->thedate}}</td>
                  <td> 
                    @if($sample->source == $sample->destination && $sample->status == 3)
                      Received
                    @elseif($sample->source == $sample->destination && $sample->status == 2)
                      Delivered
                    @elseif($sample->source == $sample->destination && $sample->status == 0)
                      Waiting for pickup
                    @else
                      In Transit
                    @endif
                  </td>                
                  <td>{{$sample->last_seen}}</td>
                  <td>{{$sample->last_seen_facility}}</td>
                  <td>{{$sample->delivered_on}}</td>
                  <td>{{$sample->received_at_destination_on}}</td>
                  <td>{{getPublicTAT($sample->thedate,$sample->received_at_destination_on)}}</td>
                </tr>
               @endforeach  
            
            </tbody>      
          </table>
        </div>
        </div>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 
      
    </div>
    <!-- /.col (LEFT) -->
    
  <!-- /.row --> 
  <!-- Modal -->
   <div class="modal fade " id="pgmovement" role="dialog">
    <div class="modal-dialog " style="width:100%;max-width:1250px;">
 
     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Package Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading">
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
     </div>
    </div>
  </div>
</section>
@endsection 