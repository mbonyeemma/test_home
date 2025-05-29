@extends('layouts.dashboard')
@section('content')

<br>
<div class="well firstrow list">
    <div class="row">
        <div class="col-md-12"> 
            {{ Form::open(array('route' => 'monitor', 'class' => 'form-search', 'id' => 'recieved_sample')) }}
                {{ csrf_field() }}              
                <div class="col-sm-2" style="padding-left: 0;">
			        {{ Form::text('date_from', Request::get('date_from'), ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => 'From']) }}
			    </div>
			    <div class="col-sm-2">
			        {{ Form::text('date_to', Request::get('date_to'), ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => 'To']) }} 
			    </div>
			    <div class="col-sm-2 col-md-offset-0.8">
			        {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown select-hubs'])}}
			    </div>
			    <div class="col-sm-3 col-md-offset-1">
			        {{ Form::text('search', Request::get('search'), array('class' => 'form-control pull-right', 'id'=>'srch', 'placeholder' => 'Search Code')) }}
			    </div>
			    <div class='col-md-1 col-md-offset-1'>
			        <button type="submit" id="searchbutton" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"> Search</i></button>
			    </div> 
            {{ Form::close() }} 
        </div>
    </div>
</div>

<div class="panel panel-primary" style="margin-top: 20px;">
    <div class="panel-body">
		<div class="box-body">
		    <table class="table table-striped table-hover table-condensed">
		      	<thead>
			        <tr>
			          <th>Envelope</th>
			          <th>Sample Type</th>
			          <th>No. Samples</th>
			          <th>Source Facility</th>
			          <th>Hub</th>
			          <th>Picked From Facility On</th>
			          <th>CPHL Sample Reception Date</th>
			          <th>TAT</th>
			        </tr>
		      	</thead>
		      	<tbody> 
		      		@foreach($packages as $package)
		      			<tr>
                            <td>
                            <a href="{{route('vl.data', $package->barcode)}}" class="barcode_display" onclick="showpackages(this.href);return false">{{$package->barcode}}</a>
                            </td>
                            <td>{{ $package->testtyps }}</td>
                            <td>{{ $package->numberofsamples }}</td>
                            <td>{{ $package->facilityname }}</td>
                            <td>{{ $package->hub }}</td>
                            <td>{{ $package->created_at }}</td>
                            <td>{{ $package->Event_date}}</td>  
                            <td>{{ returnTATforRecievedPackage($package->created_at,$package->Event_date)}}</td>                       
                        </tr>
		      		@endforeach
		      	</tbody> 
		    </table>
		    {{ $packages->links() }}
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" id="showbarcodes" role="dialog">
    <div class="modal-dialog " style="width:100%;max-width:1250px;"> 
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Samples in a Package</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@stop

@section('page-js-script')

<script type="text/javascript">

    function showpackages(url) {
		popupWindow = window.open(url,'popUpWindow','height=300,width=700,left=50,top=50,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes')
	}	
</script>


<script type="text/javascript">
	$(document).ready(function(){

		$('.filter-date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true
            });

	});
</script>




@stop