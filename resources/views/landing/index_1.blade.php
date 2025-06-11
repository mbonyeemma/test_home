@extends('layouts.app4')

@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
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
<script src="{{ asset('js/jquery.stickytabs.js') }}"></script>
<script>
  $(document).ready(function() {
    $('.nav-tabs').stickyTabs();
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
      url: "{{ url('sampletracking/late_delivery') }}",
      success: function(data) {
        $('#late_delivery').html(data);        
      }
    }); 
    $.ajax({
      type: 'GET',
      url: "{{ url('sampletracking/covid_stats/2')}}",
      success: function(data) {
        $('#deliverd').html(data);        
      }
    });
    $.ajax({
      type: 'GET',
      url: "{{ url('sampletracking/covid_stats/3') }}",
      success: function(data) {
        $('#received').html(data);        
      }
    });
    $.ajax({
      type: 'GET',
      url: "{{ url('sampletracking/covid_stats/1') }}",
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
          console.log(response);
          // Add response in Modal body          
          $('.modal-body').html(response);

          
        }
      });
    }
</script> 
@append
<style>
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
    background: #ffffff url(/img/strip-flag.png) top left repeat-x;
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
<div class="row"  style="margin-top: 20px;">
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
          <span class="info-box-icon bg-yellow"><i class="ion ion-stats-bars"></i></span>

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
          <span class="info-box-icon bg-red"><i class="ion ion-stats-bars"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Not Delivered More Than 3 Days</span>
            <span class="info-box-number" id="late_delivery">
              <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading"></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
</div>


<div class="well firstrow list">
          <div class="row">
            <div class="col-md-12"> {{ Form::open(array('route' => 'home', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
              {{ csrf_field() }}
		{{ Form::hidden('page_type', $page_type) }}              
              {{ Form::text('from', $from, ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
              
              {{ Form::text('to', $to, ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
              
              {{Form::select('facilityid', $facilities, $request->facilityid, ['class'=>'select2 selectdropdown autosubmitsearchform'])}}
              {{Form::select('poe', $poes, $request->poe, ['class'=>'select2 selectdropdown autosubmitsearchform'])}}
              {{Form::select('ref_lab', $ref_labs, $request->ref_lab, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              {{Form::select('site_type', $site_types, $request->site_type, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              {{Form::select('test_type', $test_types, $request->test_type, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
              <button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
              {{ Form::close() }} 
            </div>
          </div>
      </div>


<div class="box tabbed-view">
  <div class="box-header hidden">
    <h3 class="box-title ">Sample Tracking Dashboard</h3>
  </div>
  <div class="box-body no-padding">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="{{$tab_1_class}}"><a href="{{ route('home') }}?page_type=1#tab_1" data-toggle="link" aria-expanded="true">Individual Tracking</a></li>
          <li class="{{$tab_2_class}}"><a href="{{ route('home') }}?page_type=2#tab_2" data-toggle="link" aria-expanded="false">Batch Tracking</li>
          
          <li class="pull-right">
            <a class="dropdown-toggle text-muted" data-toggle="dropdown" href="#" aria-expanded="false">
              <i class="fa fa-gear"></i>
            </a>
            <ul class="dropdown-menu">
              <li role="presentation"><a role="menuitem" target="_blank" tabindex="-1" href="#">Download for App</a></li>
                
            </ul>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_{{$tab}}">
            <div class="col-xs-12 table-responsive">
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
                    <th>Last seen at</th>
                    <th>Delivered on</th>
                    <th>Received at</th>
                    <th>TAT</th>
                </tr>
                </thead>
                <tbody>    

                  @foreach ($package_samples as $sample)
                    <tr>
                      <td>
                        @if($tab == 1)
                        <a href="#" data-id="{{$sample->container}}" class="sample_display" onclick="getPackageSamples({{$sample->id}})">{{$sample->container}}</a>
                        @else
                        {{$sample->container}}
                        @endif
                      </td>
                      <td class="hidden"><a href="#" data-id="{{$sample->id}}" class="sample_display" onclick="trace_sample({{$sample->id}})">{{$sample->sample_id}}</a></td>
                      <td>{{$sample->collection_point}}</td>
                      <td>{{$sample->collection_point_name}}</td>
                      <td>{{$sample->district}}</td>
                      <td>{{$sample->hub}}</td>
                      <td>{{$sample->final_destn}}</td>
                      <td>{{$sample->testtype}}</td>
                      <td>{{$sample->numberofsamples}}</td>
                      <td>{{$sample->date_picked}}</td>
                      <td> 
                        @if($sample->status == 4)
                          Referred 
                        @elseif($sample->final_destination == $sample->location && $sample->status == 3)
                          Received
                        @elseif($sample->final_destination == $sample->location && $sample->status == 2)
                          Delivered
                        @elseif($sample->status == 0)
                          Waiting for pickup
                        @else
                          In Transit
                        @endif
                      </td>                
                      <td>{{$sample->last_seen}}</td>
                      <td>{{$sample->last_seen_facility}}</td>
                      <td>{{$sample->delivered_on}}</td>
                      <td>@if($sample->final_destn == $sample->last_seen_facility && $sample->status == 3)
                          {{$sample->last_seen}}
                        @else
                          
                        @endif</td>
                      <td>{{getPublicTAT($sample->date_picked,$sample->last_seen,$sample->final_destination,$sample->location,$sample->destination,$sample->status)}}</td>
                    </tr>
                   @endforeach  
                
                </tbody>      
              </table>
            </div>
          </div>


        </div>
      </div>
  </div>
</div>

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
@endsection 
