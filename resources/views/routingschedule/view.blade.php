@extends('layouts.app')
@section('title', 'View Routing Schedule')

@section('content')
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
  <div class="col-xs-12 table-responsive">
     @if(count($mondayschedule))<table class="table table-bordered">
   
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
    </div>
  </div>
   <div class="box-footer"> 
  @if(count($mondayschedule) || count($tuesdayschedule) || count($wednesdayschedule) || count($thursdayschedule) || count($fridayschedule) || count($saturdayschedule) || count($sundayschedule))
  <a class="btn btn-default" href="{{ URL::previous() }}">Back</a> <a class="btn btn-warning pull-right" href="{{ route('routingschedule.edit', ['id' => $id]) }}">Update Schedule</a>
  @else
  <a class="btn btn-default" href="{{ URL::previous() }}">Cancel</a> <a class="btn btn-primary pull-right" href="{{ route('routingschedule.create') }}">Create Schedule</a>
  @endif
  </div>
</div>
@endsection 