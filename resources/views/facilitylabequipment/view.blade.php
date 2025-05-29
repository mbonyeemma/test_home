@extends('layouts.app')

@section('title', 'Lab Equipment Details')

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
          <td>{{getLookupValueDescription("LAB_EQUIPMENT", $labequipment->labequipment_id) }}</td>
        </tr>

         <tr>
          <td>Model</td>
          <td>{{ $labequipment->model}}</td>
        </tr>

         <tr>
          <td>Serial Number</td>
          <td>{{ $labequipment->serial_number}}</td>
        </tr>
         <tr>
          <td>Lab Section</td>
          <td>{{getLookupValueDescription("LAB_SECTIONS", $labequipment->location) }}</td>
        </tr>
        <tr>
          <td>Procurement Type</td>
          <td>{{getLookupValueDescription("PROCUREMENT_TYPE", $labequipment->procurement_type) }}</td>
        </tr>
        <tr>
          <td>Purchased Date</td>
          <td>{{getPageDateFormat($labequipment->purchase_date)}}</td>
        </tr>
          <tr>
          <td>Delievered Date</td>
          <td>{{getPageDateFormat($labequipment->delivery_date)}}</td>
        </tr>
        <tr>
          <td>Verification Date</td>
          <td>{{getPageDateFormat($labequipment->verification_date)}}</td>
        </tr>
        <tr>
          <td>Installation Date</td>
          <td>{{getPageDateFormat($labequipment->installation_date)}}</td>
        </tr>
        <tr>
          <td>Spear Parts</td>
          <td>{{getLookupValueDescription("YES_NO", $labequipment->spare_parts) }}</td>
        </tr>

        <tr>
          <td>Warranty Period</td>
         <td>{{getLookupValueDescription("WARRANTY_PERIOD", $labequipment->warranty) }}</td>
        </tr>
        <tr>
          <td>Lifetime</td>
          <td>{{$labequipment->life_span}} Years</td>
        </tr>
        <tr>
          <td>Service Frequency</td>
         <td>{{getLookupValueDescription("SERVICE_FREQUENCY", $labequipment->service_frequency) }}</td>
        </tr>
        <tr>
          <td>Service Contract</td>
          <td>{{getLookupValueDescription("YES_NO",$labequipment->service_contract)}}</td>
        </tr>
      </tbody>
    </table>
    </div>
    <div class="box-footer clearfix">  
                <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-left">Back</a>
                <a href="{{route('labequipment.edit', $labequipment->id)}}" class="btn btn-sm btn-warning pull-right">Update Facility</a> </div>
  </div>
</div>
@endsection 