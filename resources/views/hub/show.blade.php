@extends('layouts.app')

@section('title', 'View '.$hub->hubname.' Details')
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
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Hub Details</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Facilities Served ({{count($facilities)}})</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Techinical Team</a></li>
        <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Routing Schedule</a></li>
        
        <li class="pull-right">
                <a class="dropdown-toggle text-muted" data-toggle="dropdown" href="#" aria-expanded="false">
                  <i class="fa fa-gear"></i>
                </a>
                <ul class="dropdown-menu">
                  <li role="presentation"><a role="menuitem" target="_blank" tabindex="-1" href="{{route('download.hubinfo', ['hubid' => $hub->id, 'type' => 1])}}">Download for App</a></li>
                    
                </ul>
              </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="col-xs-12 table-responsive">
    <table class="table">
      <tbody>
      <tr>
          <td>Name</td>
          <td>{{ $hub->hubname }}</td>
        </tr>
        <tr>
          <td>IP</td>
          <td>@if($hub->ipid){{ $hub->ip->name }}@endif</td>
        </tr>
      	<tr>
          <td>Health Region</td>
          <td>@if($hub->ipid){{ $hub->ip->healthregion->name }}@endif</td>
        </tr>
         <tr>
          <td>Code</td>
          <td>@if($hub->code){{ $hub->code }}@endif</td>
        </tr>        
      </tbody>
    </table>
    <div class="box-footer clearfix"> <a href="{{ URL::previous() }}" class="btn btn-default pull-left">Back</a>
              
                    <a href="{{route('hub.edit', $hub->id)}}" class="btn btn-warning pull-right">Update Hub</a></div>
    </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2">
        	<div class="box-body table-responsive">
    <table id="facilitylist" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>District</th>
          <th>Level</th>
          @if($can_update_facility || $can_delete_facility)
          <th>Actions</th>
          @endif
        </tr></thead>
        <tbody>
      @foreach ($facilities as $facility)
      <tr>
        
        <td><a href="{{ route('facility.show', $facility->id ) }}">{{ $facility->name }}</a></td>
        <td>{{ $facility->district }}</td>
        <td>{{ $facility->facilitylevel }}</td>
        @if($can_update_facility || $can_delete_facility)
        <td>
        @if($can_update_facility)<a href="{{ route('facility.edit', $facility->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;
        <a href="{{route('facility.printqr', $hub->id)}}" target="_blank"><i class="fa fa-fw fa-qrcode"></i> Print QR code</a>
        
        @endif
        @if($can_delete_facility)
        	&nbsp;<a href="{{ route('facility.edit', $facility->id ) }}"><i class="fa fa-fw fa-trash-o"></i>Delete</a>
            @endif
        </td>
        @endif
      </tr>
      @endforeach
        </tbody>
    </table>
  </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_3">
          <div class="row">
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">In-Charge</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	
                  <div class="table-responsive">
                  @if($incharge)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$incharge->firstname.' '.$incharge->lastname.' '.$incharge->othernames}}</td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$incharge->telephonenumber}}</td>
                        </tr>
                        @if(!empty($incharge->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$incharge->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($incharge->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$incharge->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($incharge->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$incharge->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$incharge->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                     @else
                    <p class="no-contact">You do not have any in-charge contact. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$incharge)<a href="{{url('contact/new/category/2/type/1/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/1/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $incharge->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Hub Cordinator</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	
                  <div class="table-responsive">
                  @if($hubcordinator)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$hubcordinator->firstname.' '.$hubcordinator->lastname.' '.$hubcordinator->othernames}}</td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$hubcordinator->telephonenumber}}</td>
                        </tr>
                        @if(!empty($hubcordinator->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$hubcordinator->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($hubcordinator->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$hubcordinator->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($hubcordinator->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$hubcordinator->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$hubcordinator->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                    @else
                    <p class="no-contact">You do not have any hub coordinator contact. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$hubcordinator)<a href="{{url('contact/new/category/2/type/2/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/2/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $hubcordinator->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>
          </div>
          <div class="row mid-row">
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Lab Manager</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	
                  <div class="table-responsive">
                  @if($labmanager)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$labmanager->firstname.' '.$labmanager->lastname.' '.$labmanager->othernames}}</td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$labmanager->telephonenumber}}</td>
                        </tr>
                        @if(!empty($labmanager->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$labmanager->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($labmanager->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$labmanager->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($labmanager->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$labmanager->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$labmanager->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                     @else
                    <p class="no-contact">You do not have any lab manager contact. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$labmanager)<a href="{{url('contact/new/category/2/type/3/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/3/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $labmanager->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">VL Focal Person</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	
                  <div class="table-responsive">
                  @if($vlfocalperson)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$vlfocalperson->firstname.' '.$vlfocalperson->lastname.' '.$vlfocalperson->othernames}}</td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$vlfocalperson->telephonenumber}}</td>
                        </tr>
                        @if(!empty($vlfocalperson->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$vlfocalperson->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($vlfocalperson->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$vlfocalperson->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($vlfocalperson->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$vlfocalperson->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$vlfocalperson->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                     @else
                    <p class="no-contact">You do not have any VL focal person contact. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$vlfocalperson)<a href="{{url('contact/new/category/2/type/4/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/4/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $vlfocalperson->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>
          </div>
          <div class="row mid-row">
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">EID Focal Person</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	
                  <div class="table-responsive">
                  @if($eidfocalperson)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$eidfocalperson->firstname.' '.$eidfocalperson->lastname.' '.$eidfocalperson->othernames}}</td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$eidfocalperson->telephonenumber}}</td>
                        </tr>
                        @if(!empty($eidfocalperson->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$eidfocalperson->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($eidfocalperson->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$eidfocalperson->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($eidfocalperson->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$eidfocalperson->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$eidfocalperson->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                     @else
                    <p class="no-contact">You do not have any lab EID focal person. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$eidfocalperson)<a href="{{url('contact/new/category/2/type/5/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/5/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $eidfocalperson->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>


             <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">DLFP</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  
                  <div class="table-responsive">
                  @if($dlfp)
                    <table class="table no-margin">
                      <tbody>
                        <tr class="first-row">
                          <td class="contact-label"> Name</td>
                          <td>{{$dlfp->firstname.' '.$dlfp->lastname.' '.$dlfp->othernames}}</td>
                        </tr>
                        
                        <tr>
                          <td class="contact-label">DLFP's District</td>
                            <td>@if($dlfp->dlfpdistrictid)
                              {{ $dlfp->dlfpDistrict->name }}
                              @endif
                            </td>
                        </tr>
                        <tr>
                          <td class="contact-label">Telephone Number1</td>
                          <td>{{$dlfp->telephonenumber}}</td>
                        </tr>
                        @if(!empty($dlfp->phone2))
                        <tr>
                          <td class="contact-label">Telephone Number2</td>
                          <td>{{$dlfp->phone2}}</td>
                        </tr>
                        @endif
                        @if(!empty($dlfp->phone3))
                        <tr>
                          <td class="contact-label">Preferred Whatsap Number</td>
                          <td>{{$dlfp->phone3}}</td>
                        </tr>
                        @endif
                        @if(!empty($dlfp->phone4))
                        <tr>
                          <td class="contact-label">Telephone Number4</td>
                          <td>{{$dlfp->phone4}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td class="contact-label">Email Address</td>
                          <td>{{$dlfp->emailaddress}}</td>
                        </tr>
                        
                      </tbody>
                    </table>
                     @else
                    <p class="no-contact">You do not have any lab DLFP. Please click the button below to to add one.</p>
                    @endif
                  </div>
                  
                  <!-- /.table-responsive --> 
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"> @if(!$dlfp)<a href="{{url('contact/new/category/2/type/6/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Add Contact</a>@else 
                <a href="{{url('contact/new/category/2/type/6/obj', ['obj' => $hub->id])}}" class="btn btn-sm btn-info btn-flat pull-left">Change to New Contact</a>
                <a href="{{route('contact.edit', $dlfp->id)}}" class="btn btn-sm btn-warning btn-flat pull-right">Update Contact</a> @endif</div>
                <!-- /.box-footer --> 
              </div>
            </div>


          </div>

        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_4">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
  <div class="col-xs-12 table-responsive">
   @if(count($mondayschedule) || count($tuesdayschedule) || count($wednesdayschedule) || count($thursdayschedule) || count($fridayschedule) || count($saturdayschedule) || count($sundayschedule))
   @if(count($mondayschedule)) 
   <table class="table table-bordered">
    
    	<thead>
        	<td>Monday</td>
            <td>Tuesday</td>
            <td>Wednesday</td>
            <td>Thursday</td>
            <td>Friday</td>
            <td>Saturday</td>
            <td>Sunday</td>
        </thead>
      <tbody>
      <tr>
          <td>
          		<ul class="nav nav-pills nav-stacked">
                @foreach($mondayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($tuesdayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($tuesdayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($wednesdayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($wednesdayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($thursdayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($thursdayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($fridayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($fridayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($saturdayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($saturdayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
          <td>@if(count($sundayschedule))
          		<ul class="nav nav-pills nav-stacked">
                @foreach($sundayschedule as $schedule)
                    <li>{{$schedule->facility->name}}</li>
                @endforeach
                </ul>
          	@endif
          </td>
        </tr>
        
      </tbody>
    </table>
    @else
   <p>This hub has not yet added their routing schedule. Follow-up with them, or create for them one by clicking of the "Create Schedule" button below.
    @endif
    </div>
  </div>
   <div class="box-footer"> 
  @if(count($mondayschedule) || count($tuesdayschedule) || count($wednesdayschedule) || count($thursdayschedule) || count($fridayschedule) || count($saturdayschedule) || count($sundayschedule))
  
  @else
   <a class="btn btn-primary pull-right" href="{{ route('routingschedulecreate', ['id' => $hub->id]) }}">Create Schedule</a>
  @endif
  </div>
</div>
              
            </div>
          </div>
          
        </div> 
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content --> 
    </div>
  </div>
</div>
@endsection 