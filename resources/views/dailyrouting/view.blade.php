@extends('layouts.app')

@section('title', 'Routing for '.$hub->name.' on '.getPageDateFormat($thedate))
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('listpagejs') 
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('js/jquery.stickytabs.js') }}"></script> 
<script>
		
		$(document).ready(function() {
			$('#facilitylist').DataTable();
			$('.nav-tabs').stickyTabs();
		} );
		$(function() {
	var options = { 
			selectorAttribute: "data-target",
			backToTop: true
		};
		$('.nav-tabs').stickyTabs( options );
	});
	</script> 
@append
@section('content')
<div class="box tabbed-view">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
      <?php $class = '';?>
      @foreach($bikes as $bike)
      <?php if($bikes_counter == 1){
		  	$class = 'active';
		  }else{
			  $class = '';
		  }
	  ?>
        <li class="{{$class}}"><a href="#tab_{{$bikes_counter}}" data-toggle="tab" aria-expanded="true">{{$bike}}</a></li>
        <?php $bikes_counter++;?>
      @endforeach
        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
      </ul>
      <div class="tab-content">
      <?php $bikes_counter = 1;
	  		$class = '';
	  ?>
      @foreach($bikes as $bikeid => $numberplate)
      <?php if($bikes_counter == 1){
		  	$class = 'active';
		  }else{
			  $class = '';
		  }?>
        <div class="tab-pane {{$class}}" id="tab_{{$bikes_counter}}">
        <?php $results = getDailyRoutingForBike($hub->id, $bikeid, $thedate); 
		if(count($results)){?>
          <div class="col-xs-12 table-responsive">
            <table class="table">
            	<thead>
                	<th>Facility</th>
                    <th>Sample Category</th>
                    <th>No. Samples</th>
                    <th>No. Results</th>
                </thead>
              <tbody>
              @foreach($results as $result)
                <tr>
                  <td>{{$result->name}}</td>
                  <td>{{$result->category}}</td>
                  <td>{{$result->numberofsamples}}</td>
                  <td>{{$result->numberofresults}}</td>
                </tr>
             @endforeach   
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <?php }//close if - count results
		$bikes_counter++;?>
        <!-- /.tab-pane -->
        @endforeach      
        <!-- /.tab-pane --> 
      </div>
      <!-- /.tab-content --> 
    </div>
  </div>
</div>
@endsection 