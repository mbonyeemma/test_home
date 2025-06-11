@extends('layouts.app')

@section('title', 'View Bike Details')

@section('content')
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Bike Details</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Bike Break Down</a></li>
        <li class="pull-right">
        @if($equipment->status == 2)
        <a class="btn btn-sm btn-info text-muted" href="javascript:void(0)"
                        data-toggle="modal" data-target="#status-update">
                  <span class="fa fa-thumbs-o-up"></span>
                        Mark bike fixed</a>
        @else
        <a href="{{route('equipment.breakdown',['hubid' => $equipment->hub->id, 'id' => $equipment->id])}}" class="text-muted btn btn-primary"><i class="fa fa-gear"></i> Report Break Down</a>
        @endif
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="col-xs-12 table-responsive bikestate{{$equipment->status}}">
            <table class="table view">
              <tbody>
                <tr class="first-row">
                  <td>Number Plate</td>
                  <td>{{ $equipment->numberplate }}</td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>{{ getLookupValueDescription("EQUIPMENT_STATUS", $equipment->status) }}
                  @if($equipment->status == 2)<br />
                  <a class="btn btn-sm btn-info" href="javascript:void(0)"
                        data-toggle="modal" data-target="#status-update">
                  <span class="fa fa-thumbs-o-up"></span>
                        Mark bike fixed</a>
                  @endif()
 </td>
                </tr>
                <tr>
                  <td>Engine Number</td>
                  <td>{{ $equipment->enginenumber }}</td>
                </tr>
                <tr>
                  <td>Chasis Number</td>
                  <td>{{ $equipment->chasisnumber }}</td>
                </tr>
                <tr>
                  <td>Year of Manufacture</td>
                  <td>{{ $equipment->yearofmanufacture }}</td>
                </tr>
                <tr>
                  <td>Color</td>
                  <td>{{ $equipment->color }}</td>
                </tr>
                <tr>
                  <td>Model Number</td>
                  <td>{{ $equipment->modelnumber }}</td>
                </tr>
                <tr>
                  <td>Brand</td>
                  <td>{{ $equipment->brand }}</td>
                </tr>
                <tr>
                  <td>Engine Capacity</td>
                  <td>{{ $equipment->enginecapacity }}</td>
                </tr>
                <tr>
                  <td>Insurance</td>
                  <td>{{ $equipment->insurance }}</td>
                </tr>
                <tr>
                  <td>Hub</td>
                  <td>{{ $equipment->hub->name }}</td>
                </tr>
                
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_3"> 
        <div class="box box-info" style="border:none;">
          <div class="box-header">
            <h3 class="box-title"></h3>
          </div>
        	@if($reasons_for_breakdown)
            <div class="row">
            	<div class="col-md-12">
                <h2 class="section">Reasons for breakdown</h2>
                	<ul>
                    	@foreach($reasons_for_breakdown as $reason)
                        	<li>{{getLookupValueDescription("BIKE_DOWN_REASONS", $reason->reason)}}</li>
                        @endforeach()
                    </ul>
                </div>
             </div>
            @endif
            
            
            <div class="row">
            	<div class="col-md-12">@if($breakdown_action_taken)
                	<h2 class="section">Actions taken</h2>
                	<ul>
                    	@foreach($breakdown_action_taken as $action)
                        	<li>{{getLookupValueDescription("BIKE_BREAK_DOWN_ACTIONS", $action->action)}}
                            @if($action->action == 1)
                            	<h3>Mechanic Details</h3>
                                	<p><span>Name: </span>{{$equipment->breakdown->mechanic->getFullName()}}</p>
                                    <p><span>Telephone: </span>{{$equipment->breakdown->mechanic->telephonenumber}}</p>
                                    <p><span>Email: </span>{{$equipment->breakdown->mechanic->emailaddress}}</p>
                                
                            @endif()
                            </li>
                        @endforeach()
                    </ul>
                    @else
                    <p>Currently this bike is in good working condition. To view previous history of breakdown, click here.</p>
                    @endif
                </div>
             </div>
            
            @if($breakdown_action_taken)
             <div class="box-footer" style="border:none;">
                <a class="btn btn-info pull-right" href="{{ URL::previous() }}">View all Break Down History</a></button>
            </div>
            @endif
        </div> <!-- /.box-body -->
        </div>
        
        <!-- /.tab-pane --> 
      </div>
      <!-- /.tab-content --> 
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="status-update">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Bike Now in Normal State</h4>
      </div>
      <div class="modal-body">
      	<div class="box box-info no-border"> 
      	{{ Form::open(array('url' => 'equipment/updatebreakdownstatus', 'class' => 'form-horizontal', 'id' => 'breakdown')) }}
  {{ csrf_field() }}
  
  			<div class="form-group">
              <label for="datebrokendown" class="col-sm-3 control-label">{{ Form::label('closingnotes', 'Any Notes') }}</label>
              <div class="col-sm-9">
                {{ Form::textarea('closingnotes', null, array('class' => 'form-control', 'id' => 'closingnotes', 'placeholder' => 'Enter remarks on how this bike breakdown was fixed')) }}
              </div>
            </div>
  			<div class="box-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </button>
            {{ Form::hidden('breakdownid', $equipment->breakdownid) }}
            {{ Form::hidden('equipmentid', $equipment->id) }}
            {{ Form::submit('Report bike as fixed', array('class' => 'btn btn-info pull-right')) }} </div>
          <!-- /.box-footer --> 
          
          {{ Form::close() }} </div>
  		</div> 
      </div>
     </div>
    </div>
  </div>
      
@endsection 