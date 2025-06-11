@extends('layouts.app')
@section('title', 'View All Bikes')
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
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>        
         
          <th>Number Plate</th>
          <th>Hub</th>
          <th>Engine Number</th>
          <th>Year of Manufacture</th>  
          <th>Actions</th> 
        </tr>
      </thead>
      <tbody>
      
      @foreach ($equipment as $eq)
     <tr>
       
        <td><a href="{{ route('equipment.show', $eq->id ) }}">{{ $eq->numberplate }}</a></td>
        <td class="bikestate{{$eq->status}}">{{ $eq->hubname }}</td>
        <td class="bikestate{{$eq->status}}">{{ $eq->enginenumber }}</td>
        <td class="bikestate{{$eq->status}}">{{ $eq->yearofmanufacture }}</td> 
        <td>@role('Admin','national_hub_coordinator')<a href="{{ route('equipment.edit', $eq->id ) }}"><i class="fa fa-fw fa-edit"></i>Update</a>&nbsp;
        	<a href="{{ route('equipment.destroy', $eq->id ) }}"><i class="fa fa-fw fa-trash-o"></i>Delete</a>
        @endrole
        @role('hub_coordinator')
        	@if($eq->status == 1)
           		 <a href="{{route('equipment.breakdown',['hubid' => $eq->hubid, 'id' => $eq->id])}}" class="text-muted btn-sm btn btn-danger"><i class="fa fa-gear"></i> Report Break Down</a>
            @else
             <a class="btn btn-sm btn-info text-muted" href="javascript:void(0)"
                            data-toggle="modal" data-target="#status-update{{$eq->id}}">
                      <span class="fa fa-thumbs-o-up"></span>
                            Mark bike fixed</a>
                       <div class="modal fade" tabindex="-1" role="dialog" id="status-update{{$eq->id}}">
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
            {{ Form::hidden('breakdownid', $eq->breakdownid) }}
            {{ Form::hidden('equipmentid', $eq->id) }}
            {{ Form::submit('Report bike as fixed', array('class' => 'btn btn-info pull-right')) }} </div>
          <!-- /.box-footer --> 
          
          {{ Form::close() }} </div>
  		</div> 
      </div>
     </div>
    </div>
            @endif

        @endrole
        </td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  <!-- /.box-body --> 
  
</div>
@endsection