@extends('layouts.app')
@if ($pagetype == 1)
	@section('title', 'All Sample Transporters')
@elseif($pagetype == 4)
	@section('title', 'All Drivers')
@elseif($pagetype == 3)
  @section('title', 'Ref Lab Receptionists')
@elseif($pagetype == 2)
	@section('title', 'All Sample Receptionists')
@elseif($pagetype == 5)	
	@section('title', 'All EOC Staff Members ')
@elseif($pagetype == 6) 
  @section('title', 'All POE Users ')
@else
@endif

@section('content')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
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
      //$('#listtable').DataTable();
      $('#stafflisttable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
          
          {
            extend: 'excelHtml5'
          }
        ]
      } );
    } );
  </script> 
@append
<div class="box box-info">
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="stafflisttable" class="table table-striped table-bordered">
    <thead>
    	<tr> @if($pagetype == 2 || $pagetype == 1 || $pagetype == 3)
          <th>District Where Hub is Located</th>
          <th>Hub</th>
          <th>Facilities Served</th>
          @endif
          <th>First Name</th>
          <th>Last Name</th>
          @if($request->showcontact)
         <th>Other Names</th>
         <th>Email Address</th>
         <th>Telephone Number 1</th>
         <th>Telephone Number 2</th>
         <th>Preferred Whatsapp Number</th>
         @else
          @if($pagetype == 2)
          <th>Designation</th>
          @endif
          @if($pagetype == 1)
          <th>Has Driving Permit</th>
          <th>Densive Driving</th>
          <th>Trained in BB</th>
          <th>Is Immunised for HB</th>
          @endif
          
          <th>Actions</th>
          @endif
        </tr>
    </thead>
      <tbody>
        
      @foreach ($staff as $st)
      <tr>
        @if($pagetype == 2 || $pagetype == 1 || $pagetype == 3)
        <td>{{ $st->district }}</td>
        <td>{{ $st->facility }}</td>
        <td>{{ getFacilitiesforHub($st->hubid)->count() }}</td>

        @endif
        <td><a href="{{ route('staff.show', $st->id ) }}">{{ $st->firstname }}</a></td>
        <td>{{ $st->lastname }}</td>
        @if($request->showcontact)
         <td>{{ $st->othernames }}</td>
         <td>{{ $st->emailaddress }}</td>
         <td>{{ $st->telephonenumber }}</td>
         <td>{{ $st->telephonenumber2 }}</td>
         <td>{{ $st->telephonenumber3 }}</td>
         @else
        @if($pagetype == 2)
        <td>{{ $st->designation }}</td>
        @endif
        @if($pagetype == 1)
        <td>@if($st->hasdrivingpermit){{ getLookupValueDescription('YES_NO', $st->hasdrivingpermit) }} @endif</td>
        <td>@if($st->hasdefensiveriding){{ getLookupValueDescription('YES_NO', $st->hasdefensiveriding) }} @endif</td>
        <td>@if($st->hasbbtraining){{ getLookupValueDescription('YES_NO', $st->hasbbtraining) }} @endif</td>
        <td>@if($st->isimmunizedforhb){{ getLookupValueDescription('YES_NO', $st->isimmunizedforhb) }} @endif</td>
        @endif

        <td><a href="{{ route('staff.edit', $st->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;
        	<a href="{{ route('staff.destroy', $st->id ) }}" class="hidden"><i class=" fa fa-fw fa-trash-o"></i>Delete</a>
          <a href="{{  url('user/resetpassword',['id' => $st->user_id]) }}"><i class=" fa fa-user"></i>Change Password</a>
        </td>
        @endif
      </tr>
      @endforeach
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->
  
</div>
@endsection