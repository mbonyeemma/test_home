@extends('layouts.app')

@section('title', 'View Sample Tracking Details')

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
      <tr>
          <td>Source Facility</td>
          <td>@if($sampletracking->facilityid){{ $sampletracking->facility->name }}@endif</td>
        </tr>
        
        <tr>
          <td>Patient</td>
          <td>{{ $sampletracking->patient }}</td>
        </tr>
        <tr>
          <td>Sample Transported By</td>
          <td>{{ $sampletracking->transporter->getFullName()}}</td>
        </tr>
         <tr>
          <td>Specimen Type</td>
          <td>{{ getLookupValueDescription('SPECIMEN_TYPES', $sampletracking->specimentype) }}</td>
        </tr>
        <tr>
          <td>Specimen Number</td>
          <td>{{ $sampletracking->specimennumber }}</td>
        </tr>
        <tr>
          <td>Sample Transported At</td>
          <td>{{ $sampletracking->sampletransported_at }}</td>
        </tr>
        
      </tbody>
    </table>
    </div>
    <div class="box-footer clearfix">  
                <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-left">Back</a>
                <a href="{{route('sampletracking.create')}}" class="btn btn-sm btn-primary pull-right">Refer Another Sample</a> </div>
  </div>
</div>
@endsection 