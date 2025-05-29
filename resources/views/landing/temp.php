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
              {{Form::select('test_type', $test_types, $request->test_type, ['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
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
                  <td><a href="#" data-id="{{$sample->container}}" class="sample_display" onclick="getPackageSamples({{$sample->id}})">@if($sample->container != '')
                    {{$sample->container}}
                    @else
                    {{$sample->sample_id}}
                    @endif</a></td>
                  <td class="hidden"><a href="#" data-id="{{$sample->id}}" class="sample_display" onclick="trace_sample({{$sample->id}})">{{$sample->sample_id}}</a></td>
                  <td>{{$sample->collection_point}}</td>
                  <td>{{$sample->collection_point_name}}</td>
                  <td>{{$sample->district}}</td>
                  <td>{{$sample->hub}}</td>
                  <td>{{$sample->final_destn}}</td>
                  <td>{{$sample->sampletype}}</td>
                  <td>{{$sample->numberofsamples}}</td>
                  <td>{{$sample->date_picked}}</td>
                  <td> 
                    @if($sample->status == 4)
                      Referred out
                    @elseif($sample->source == $sample->destination && $sample->status == 3)
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
                  <td>@if($sample->final_destn == $sample->last_seen_facility && $sample->status == 3)
                      {{$sample->last_seen}}
                    @else
                      
                    @endif</td>
                  <td>{{getPublicTAT($sample->date_picked,$sample->last_seen,$sample->final_destination,$sample->source,$sample->destination,$sample->status)}}</td>
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