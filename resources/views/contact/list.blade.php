@extends('layouts.app')
@if ($category == 2 && $type == 2)
	@section('title', 'Hub Coordinators')
@elseif($category == 2 && $type == 2)
	@section('title', 'All Drivers')
@elseif($category == 2 && $type == 2)
	@section('title', 'All Sample Receptionists')
@elseif($category == 2 && $type == 6)
  @section('title', 'DLFPs')
@else
  @section('title', 'All Sample Receptionists')
@endif


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
      $('#listtable').DataTable( {
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

@section('content')

<div class="box box-info">
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
    <thead>
    	<tr>
          <th>Hub</th>
          <th>District Where Hub is Located</th>
          <th>First Name</th>
          <th>Last Name</th>
          <!-- <th>Other Names</th> -->
          <th>Email Address</th>
          <th>Phone Number1</th>
          <th>Phone Number2</th>
          <!-- <th>Preferred Whatsapp Number</th> -->
        </tr>
    </thead>
      <tbody>
        
      @foreach ($contacts as $contact)
      <tr>
        <td>{{ $contact->hub }}</td>
        <td>{{ $contact->district }}</td>
        <td>{{ $contact->firstname }}</td>
        <td>{{ $contact->lastname }}</td>
        <!-- <td>{{ $contact->othernames }}</td> -->
        <td>{{ $contact->emailaddress}}</td>
        <td>{{ $contact->telephonenumber }}</td>
        <td>{{ $contact->phone2 }}</td>
        <!-- <td>{{ $contact->phone3 }}</td> -->
      </tr>
      @endforeach
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->
  
</div>
@endsection