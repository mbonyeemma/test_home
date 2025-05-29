@extends('layouts.app4')
@section('title', 'COVID-19 Samples')
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

      $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/statistics'); ?>",
      success: function(data) {
        console.log(data);
        $('#destinedforcphl').html(data.destinedforcphl);
        $('#receivedatcphl').html(data.receivedatcphl);
        $('#hubpackages').html(data.hubpackages);       
      }
    });
    $.ajax({
      type: 'GET',
      url: "<?php echo url('sampletracking/outbreak'); ?>",
      success: function(data) {
        $('#outbreak').html(data);        
      }
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

.m{
  width: 100%;
    height: 7px;
    background: #ffffff url(/imag/strip-flag.png) top left repeat-x;
    background-size: 5px;
}

</style>
<section class="content">
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fa fa-info"></i> Important Note!</h5>
    The totals in the graph below are the samples that have been transported to UVRI using the National Sample Transport Network. There are other samples which may have been transported to UVRI using other means, depending on urgency of the sample. For detailed information about the COVID, please visit <a href="https://covid19.gou.go.ug/" target="_blank">https://covid19.gou.go.ug/</a> </div>
  
  <div class="well firstrow list">
    <div class="row">
      <div class="col-md-10"> {{ Form::open(array('route' => 'home', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
        {{ csrf_field() }}
        
        {{ Form::text('from', $from, ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
        
        {{ Form::text('to', $to, ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }}
        
        {{Form::select('facilityid', $facilities, $request->facilityid, ['class'=>'selectdropdown autosubmitsearchform'])}}
        {{Form::select('ref_lab', $ref_labs, $request->ref_lab, ['class'=>'selectdropdown autosubmitsearchform'])}} 
        {{Form::select('site_type', $site_types, $request->site_type, ['class'=>'selectdropdown autosubmitsearchform'])}} 
        {{Form::select('sample_type', $sample_types, $request->sample_type, ['class'=>'selectdropdown autosubmitsearchform'])}} 
        <button type="submit" id="searchbutton" class="btn btn-primary">Filter <i class="glyphicon glyphicon-filter"></i></button>
        {{ Form::close() }} 
      </div>
      <div class="col-md-2">
        <a class="btn btn-primary pull-right" href="{{ route('contact.comprehensive_list') }}" target="_blank">Contacts</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-9"> 
      
      <!-- AREA CHART -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Samples Transported between {{$from}} and {{$to}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          
         <div id="covid-chart" >
          <?php //echo lava::render('LineChart', 'samples', 'samples-chart'); ?>
          <?php echo lava::render('ColumnChart', 'samples_tracked', 'covid-chart'); ?> </div>
        
        </div>
        <!-- /.box-body --> 
      </div>
      
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Sample Status for samples collected between {{$from}} and {{$to}}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="box-body table-responsive">
          <table id="listtable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Sample ID</th>
                <th>Package ID</th>                
                <th>Collection Site</th>
                <th>Sample Type</th>
                <th>No. Samples</th>
                <th>Picked at</th>
                <th>Status</th>
                <th>Received/Last seen on</th>
                <th>Transit Location</th>
            </tr>
            </thead>
            <tbody>    

              @foreach ($package_samples as $sample)
                <tr>
                  <td>{{$sample->sample_id}}</td>
                  <td>{{$sample->container}}</td>
                  <td>{{$sample->sourcefacility}}</td>
                  <td>{{$sample->sampletype}}</td>
                  <td>{{$sample->numberofsamples}}</td>
                  <td>{{$sample->thedate}}</td>
                  <td> 
                    @if($sample->source == $sample->destination && $sample->status == 3)
                      Received
                    @elseif($sample->source == $sample->destination && $sample->status == 2)
                      Delivered
                    @else
                      In Transit
                    @endif
                  </td>                
                  <td>{{$sample->last_seen}}</td>
                  <td>{{$sample->last_seen_facility}}</td>
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
    <div class="col-md-3"> 
      <!-- LINE CHART -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Summary</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="table-responsive">
                <table id="listtable" class="table table-striped table-bordered">
                  <tr>
                    <th>Sample Type</th>
                    <th>No. samples</th>
                  </tr>
                  @foreach($samples_graph as $line)
                  <tr>
                    <td>{{$line->sampletype}} </td>
                    <td>{{$line->samples}} </td>
                  </tr>
                  @endforeach
                </table>
              
           </div>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 
    </div>
    <!-- /.col (RIGHT) --> 
  </div>
  <!-- /.row --> 
  
</section>
@endsection 