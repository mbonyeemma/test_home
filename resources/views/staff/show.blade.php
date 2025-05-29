@extends('layouts.app')

@if ($staff->type == 1)
	@section('title', 'View Sample Transporter')
@elseif($staff->type == 2)
	@section('title', 'View Sample Receptionist')
@elseif($staff->type == 4)
	@section('title', 'View Driver')
@elseif($staff->type == 5)
	@section('title', 'EOC Staff Member')
@elseif($staff->type == 6) 
  @section('title', 'Add New POE User')
@elseif($staff->type == 7) 
  @section('title', 'Community User')
@elseif($staff->type == 8) 
  @section('title', 'Special Transporter')
@else
@endif

@section('content')
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
  <div class="col-xs-12 table-responsive">
    <table class="table">
      <tbody>
      
      @role(['administrator','national_hub_coordinator']) 
      <tr>
          <td><b>Username</b></td>
          <td><b>{{$staff->user->username}}</b></td>
      </tr>
      <tr>
          <td>Hub</td>
          <td>@if($staff->hubid){{ $staff->facility->hubname }}@endif</td>
        </tr>@endrole
        <tr>
          <td>First Name</td>
          <td>{{ $staff->firstname }}</td>
        </tr>
        <tr>
          <td>Last Name</td>
          <td>{{ $staff->lastname }}</td>
        </tr>
         <tr>
          <td>Other Names</td>
          <td>{{ $staff->othernames }}</td>
        </tr>
        <tr>
          <td>Email Address</td>
          <td>{{ $staff->emailaddress }}</td>
        </tr>
        <tr>
          <td>Telephone Number</td>
          <td>{{ $staff->telephonenumber }}</td>
        </tr>
        <tr>
          <td>Telephone Number2</td>
          <td>{{ $staff->telephonenumber2 }}</td>
        </tr>
        <tr>
          <td>Preferred Whatsapp Number</td>
          <td>{{ $staff->telephonenumber3 }}</td>
        </tr>
        @if($staff->type == 2)
        <tr>
          <td>Designation</td>
          <td>{{ $staff->designation }}</td>
        </tr>
        @endif
        @if($staff->type == 1)
        <tr>
          <td>Has Driving Permit</td>
          <td>{{ getLookupValueDescription('YES_NO', $staff->hasdrivingpermit) }}
          @if($staff->hasdrivingpermit)
            , Expires On {{changeMySQLDateToPageFormat($staff->permitexpirydate)}}
          @endif
          </td>
        </tr>
        <tr>
          <td>Has Defensive Driving</td>
          <td>{{ getLookupValueDescription('YES_NO', $staff->hasdefensiveriding) }}
          </td>
        </tr>
        <tr>
          <td>Has BB Training</td>
          <td>{{ getLookupValueDescription('YES_NO', $staff->hasbbtraining) }}
          </td>
        </tr>
        <tr>
          <td>Is Immunized for Hepatitis B</td>
          <td>{{ getLookupValueDescription('YES_NO', $staff->isimmunizedforhb) }}
          </td>
        </tr>
        @endif
        
      </tbody>
    </table>
    </div>
    <div class="box-footer clearfix">  
                <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-left">Back</a>
                @if ($staff->type == 1)
                <a href="{{route('staff.edit', $staff->id)}}" class="btn btn-sm btn-warning pull-right">Update Sample Transporter</a>
                @elseif($staff->type == 2)
                 <a href="{{route('staff.edit', $staff->id)}}" class="btn btn-sm btn-warning pull-right">Update Sample Receptionist</a>
                @elseif($staff->type == 4)
                 <a href="{{route('staff.edit', $staff->id)}}" class="btn btn-sm btn-warning pull-right">Update Driver</a>
                @elseif($staff->type == 5)
                <a href="{{route('staff.edit', $staff->id)}}" class="btn btn-sm btn-warning pull-right">Update EOF Staff</a>
                @else
                @endif </div>
  </div>
</div>
@endsection 