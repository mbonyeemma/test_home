@extends('layouts.app')
@section('title', 'View Referred Samples')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('listpagejs') 
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
<script>
		$(document).ready(function() {
			$('#listtable').DataTable();
		} );
	</script> 
@append
<div class="box box-info"> 
  
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="listtable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Patient</th>
          <th>Specimen Number</th>
          <th>Specimen Type</th>
          <th>Sample Transported By</th>          
          <th>Sample Transported At</th>
          <th>Source Facility</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach ($results as $result)
      <tr>
        <td>{{$result->patient}}</td>
        <td><a href="{{ route('sampletracking.show', $result->id ) }}">{{$result->specimennumber}}</a></td>
        <td>{{$result->specimentype}}</td>
        <td>{{$result->transporter}}</td>
        <td>{{$result->time}}</td>
        <td>{{$result->facility }}</td>
        <td>{{$result->status}}</td>
        <td><a href="{{ route('sampletracking.edit', $result->id ) }}"><i class="fa fa-fw fa-edit"></i>Enter Result</a></td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
  </div>
  <!-- /.box-body --> 
</div>
@endsection