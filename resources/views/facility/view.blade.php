@extends('layouts.app')

@section('title', 'View Facility')

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
          <td>Name</td>
          <td>{{ $facility->name }}</td>
        </tr>
        <tr>
          <td>District</td>
          <td>{{ $facility->district->name}}</td>
        </tr>
        <tr>
          <td>Level</td>
          <td>{{ $facility->facilitylevel->level }}</td>
        </tr>
         <tr>
          <td>Distance from hub</td>
          <td>{{ $facility->distancefromhub }} KM</td>
        </tr>
        <tr>
          <td>In-charge</td>
          <td>{{ $facility->incharge }}, {{ $facility->inchargephonenumber }}</td>
        </tr>
        <tr>
          <td>Lab Manager</td>
          <td>{{ $facility->labmanager }}, {{ $facility->labmanagerphonenumber }}</td>
        </tr>
        <tr>
          <td>Facility Type</td>
          <td>{{ $facility->type }}</td>
        </tr>
        @role(['administrator','national_hub_coordinator']) 
        <tr>
        	<td>QR Code</td>
            <td>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <a href="{{route('facility.printqr', $facility->id)}}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa fa-print"></i> Print QR Code
                    </a>
                    <div style="border: 2px solid #ddd; padding: 10px; border-radius: 8px; background-color: #f9f9f9;">
                        {!! QrCode::size(150)->generate((string)$facility->id)!!}
                    </div>
                </div>
            </td>
        </tr>
        @endrole
      </tbody>
    </table>
    </div>
    <div class="box-footer clearfix">  
        <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-left">Back</a>
        <a href="{{route('facility.edit', $facility->id)}}" class="btn btn-sm btn-warning pull-right">Update Facility</a> 
    </div>
  </div>
</div>
@endsection 