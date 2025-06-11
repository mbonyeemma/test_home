@role(['national_hub_coordinator','administrator'])
{{Form::select('hubid', $hubs, old('hubid'), ['class'=>'selectdropdown autosubmitsearchform'])}}
@endrole
@role(['national_hub_coordinator','administrator'])
@endrole



@extends('layouts.app')
@section('title', 'Samples Pending Receipt')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2();

    $("#is_to_be_transfered").change(function() {
      var opt = $(this).val();
      if (opt == 1) {
        $('.transfer_to').removeClass('hidden');
        $('#transfer_to').attr("required", "required");
      } else {
        $('.transfer_to').addClass('hidden');
        $('#transfer_to').removeAttr("required");

      }
    });

    //var totalRecords =$("#listtable").DataTable().page.info().recordsTotal;
    //test if dataTable is empty
    //(totalRecords === 0)? alert("table is empty") : alert("table is not empty");
    //var table = $('#listtable').DataTable();
    //var info = table.page.info();
    //alert(info.);

    $('.filter-date, .date_field').datepicker({
      format: 'yyyy-mm-dd',
      endDate: '+0d',
      autoclose: true
    });

    $(".sample").click(function() {
      var sampleid = $(this).attr('id');
      $('#samplemodal_' + sampleid).modal('show');
    });

    $("#pop_facilityid").change(function() {
      var facility_id = $('#pop_facilityid').val();
      if (facility_id != '') {
        var url = "/samples/get_district_hub/" + facility_id;
        $.get(url, function(data, status) {
          var json_data = JSON.parse(data);
          $("#id_district").html(json_data.district);
          $("#id_hub").html(json_data.hub);
        });
      }
    });

  });
</script>
@append
<style>
  #searchbutton {
    margin-top: -4px;
  }

  .input-field {
    width: 200px;
  }

  .selectdropdown {
    width: 200px;
  }

  .search {
    width: 220px;
    border-radius: 4px;
  }

  .input-field,
  .selectdropdown {
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-top-color: rgb(204, 204, 204);
    border-right-color: rgb(204, 204, 204);
    border-bottom-color: rgb(204, 204, 204);
    border-left-color: rgb(204, 204, 204);
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
  }
</style>
<div class="box box-info">
  @if(Session::has('success'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{!! Session::get('success') !!}</strong>
  </div>
  @endif

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-6 m-auto">
          @if(Session::has('success'))
          <div class="alert alert-danger">
            {!! Session::get('success') !!}
          </div>
          @endif
          <div class="card shadow">
            <div class="card-header bg-info text-white">
              <h3 class="card-title">Barcode not Scanned</h3>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <p>The barcode was not scanned so, record its details here for follow-up</p>
            <!-- form start -->
            {{ Form::open(array('route' => 'reception.saveunscannedbarcode', 'class' => '', 'id' => 'unscanned')) }}
            {{ csrf_field() }}
            <div class="card-body">
              <div class="form-group">
                <label for="hub" class="control-label">Facility</label>
                {{Form::select('facilityid', $all_facilities, old('facilityid'), ['class'=>'form-control select-field select2', 'required'=>'required','id'=>'pop_facilityid','style'=>'width:100%;'])}}
              </div>

              <div class="col-lg-4" style="padding-top: 24px;">
                <label for="district">District:</label>
                <div>
                  <u id="id_district" style="width: 100%">_____________________________________</u>
                </div>
              </div>
              <div class="form-group" style="padding-top: 24px;">
                <label for="hub">Hub:</label>
                <div>
                  <u id="id_hub" style="width: 100%">________________________________</u>
                </div>
              </div>

              <div class="form-group">
                <label for="hub" class="control-label">Sample Type</label>
                {{Form::select('test_type', $test_types, old('test_type'), ['class'=>'form-control select-field', 'required'=>'required','style'=>'width:100%;'])}}
              </div>
              <div class="form-group">
                <label for="hub" class="control-label">Is to be transfered?</label>
                {{Form::select('is_to_be_transfered', [0=>'No',1=>'Yes'], old('is_to_be_transfered'), ['class'=>'form-control input-lg select-field', 'id'=>'is_to_be_transfered','required'=>'required'])}}
              </div>
              <div class="form-group transfer_to hidden">
                <label for="hub" class="control-label">Transfer to</label>
                {{Form::select('transfer_to', $ref_labs, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}
              </div>
              <div class="form-group">
                <label for="picked_on" class="control-label">Date Picked from Facility</label>
                {{ Form::text('picked_on', old('picked_on'), array('class' => 'form-control date_field', 'id' => 'delivered_on','required'=>'required')) }}
              </div>
              <div class="form-group">
                <label for="delivered_on" class="control-label">Date Delivered</label>
                {{ Form::text('delivered_on', old('delivered_on'), array('class' => 'form-control date_field', 'id' => 'delivered_on','required'=>'required')) }}
              </div>
              <div class="form-group">
                <label for="barcode" class="control-label">Barcode</label>
                {{ Form::text('barcode', $searchString, array('class' => 'form-control', 'id' => 'barcode','required'=>'required')) }}
              </div>
              <div class="form-group">
                <label for="numberofsamples" class="control-label">Number of Samples</label>
                {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'numberofsamples')) }}
              </div>
              <div class="form-group">
                <div>
                  {{ Form::hidden('type', 2) }}
                  {{ Form::hidden('is_tracked_from_facility', 0) }}
                  <button type="submit" id="submit_form" class="btn btn-primary">Submit </button>
                </div>
              </div>
              {{ Form::close() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection