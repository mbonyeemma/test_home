@extends('layouts.app')

@section('title', 'Facilities not Visited')
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
           
        </tr></thead>
        <tbody>
      @foreach ($facilities as $facility)
      <tr>
        <td><a href="{{ route('facility.show', $facility->id ) }}">{{ $facility->facilityname }}</a></td>
        <td>{{ $facility->facilityname }}</td>
        <td>{{ $facility->district }}</td>
      </tr>
      @endforeach
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->
  
</div>
@endsection