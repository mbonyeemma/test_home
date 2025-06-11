@extends('layouts.app')
@section('title', 'View All Equipment')
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
			$('#listtable').DataTable( {
				dom: 'Bfrtip',
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
<style>
	div.dataTables_length label {
    font-weight: normal;
    float: left;
    text-align: left;
    margin-bottom: 0;
}
div.dataTables_length select {
    min-width: 60px;
    margin-right: 4px;
}
</style>
<div class="box box-info"> 
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>        
         
          <th>Name</th>
          @role('Admin','national_hub_coordinator')<th>Hub</th>@endrole
          <th>Lab Section</th>
          <th>Model</th>
          <th>Serial No.</th> 
          <th>Installed on</th> 
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($equipment as $eq)
     <tr class="bikestate{{$eq->status}}">
       
        <td><a href="{{ route('labequipment.show', $eq->id ) }}">{{ $eq->name }}</a></td>
        @role('Admin','national_hub_coordinator')<td>{{ $eq->hubname }}</td>@endrole
        <td>{{getLookupValueDescription("LAB_SECTIONS",$eq->location)}}</td>
        <td>{{ $eq->model }}</td>
        <td>{{ $eq->serial_number }}</td> 
        <td>{{ $eq->installation_date }}</td>
        <td>@role('Admin','national_hub_coordinator')<a href="{{ route('labequipment.edit', $eq->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;
        	<a href="{{ route('labequipment.destroy', $eq->id ) }}"><i class="fa fa-fw fa-trash-o"></i>Delete</a>
            @endrole
            @role('hub_coordinator')
        <a href="{{route('labequipment.breakdown',['hubid' => $eq->hubid, 'id' => $eq->id])}}" class="text-muted btn btn-primary"><i class="fa fa-gear"></i> Report Break Down</a>
         @endrole
        </td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  </div>
  <!-- /.box-body --> 
  
</div>
@endsection