@extends('layouts.app')

@section('title', 'All Facilities')
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
			//$('#facilitylist').DataTable();
			/*$('#facilitylist').DataTable( {
				dom: 'Bfrtip',
				buttons: [
					'excelHtml5',
					'pdfHtml5'
				]
			} );*/
			 $('#facilitylist').DataTable( {
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
		} );
	</script>
@append

@section('content')

<div class="box box-info">
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="facilitylist" class="table table-striped table-bordered display">
      <thead>
        <tr>
          <th>Name</th>
          <th>Hub</th>
          <th>District</th>
          <th>Level</th>
           <th>Actions</th>

        </tr></thead>
        <tbody>
      @foreach ($facilities as $facility)
      <tr>
        <td><a href="{{ route('facility.show', $facility->id ) }}">{{ $facility->name }}</a></td>
        <td>{{ $facility->hub }}</td>
        <td>{{ $facility->district }}</td>
        <td>{{ $facility->facilitylevel }}</td>
        <td>@if(Entrust::can('Update_facility'))<a href="{{ route('facility.edit', $facility->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;@endif
        <a href="{{route('facility.printqr', $facility->id)}}" target="_blank"><i class="fa fa-fw fa-qrcode"></i> Print QR code</a>
        @if($can_delete_facility)
       &nbsp;<a href="{{ route('facility.edit', $facility->id ) }}"><i class="fa fa-fw fa-trash-o"></i>Delete</a>
        @endif
        </td>

      </tr>
      @endforeach
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->
  
</div>
@endsection