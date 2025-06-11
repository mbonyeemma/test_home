@extends('layouts.app')
@section('title', 'Receive Samples')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script>
  $(document).ready(function() {

    $("#is_to_be_transfered").change(function() {
      var opt = $(this).val();
      if (opt == 1) {
        $('.transfer_to, .no_s,#package').removeClass('hidden');
        $('#package').addClass('hidden');
        $('#transfer_to').attr("required", "required");
        $('#number_of_samples').removeAttr("required");
      } else {
        $('.transfer_to, .no_s').addClass('hidden');
        $('#package').removeClass('hidden');
        $('#transfer_to').removeAttr("required");
        $('#number_of_samples').attr("required", "required");
      }
    });

    $('.date').datepicker({
      format: 'yyyy-mm-dd',
      endDate: '+0d',
      autoclose: true
    });

  });
</script>
@append
<style>
  .form-horizontal .form-group {
    margin-right: 0;
    margin-left: 10px;
  }

  #number_of_samples,
  #is_to_be_transfered,
  #transfer_to,
  #receipt_date {
    width: 30%;
  }
</style>
<div class="box box-info">
  {{ Form::open(array('route' => 'reception.receivesmallpackage', 'class' => 'form-horizontal', 'id' => 'no_s')) }}

  <div class="box-body">
    <div class="form-group">
      <label for="receipt_date" class="control-label">Received On:</label>

      <div>
        {{ Form::text('receipt_date', old('receipt_date'), array('class' => 'form-control date', 'id' => 'receipt_date')) }}
      </div>
    </div>
    <div class="form-group">
      <label for="hub" class="control-label">Is to be transfered?</label>
      <div>
        {{Form::select('is_to_be_transfered', ['Select One'=>'',0=>'No',1=>'Yes'], old('is_to_be_transfered'), ['class'=>'form-control input-lg select-field', 'id'=>'is_to_be_transfered','required' => 'required'])}}
      </div>
    </div>
    <div class="form-group transfer_to hidden">
      <label for="ref_lab" class="control-label">Transfer to</label>
      <div>
        {{Form::select('transfer_to', $ref_labs, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}
      </div>
    </div>
    <div class="form-group">
      <label for="insurance" class="control-label">No. Samples</label>

      <div>
        {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'number_of_samples')) }}
      </div>
    </div>
    <div class="form-group">
      <div>
        <input type="text" name="id" value="{{$id}}" class="hidden">
        {{ Form::hidden('is_tracked_from_facility', 0) }}
        <button type="submit" id="submit_form" class="btn btn-primary">Submit </button>
        </button>
      </div>
    </div>
  </div>
  {{ Form::close() }}
</div>
@endsection