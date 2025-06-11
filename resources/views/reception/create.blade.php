@extends('layouts.app')
@section('title', 'Receive Samples')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
 <script>
  $(document).ready(function() {
  $('#permitexpirydate').datepicker({
       format: 'mm/dd/yyyy',
       autoclose: true
    });
  });
</script>
@append
@section('content')
  <div class="box box-info">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
         @endif
            
            <!-- /.box-header -->
            <!-- form start -->
            {{-- Using the Laravel HTML Form Collective to create our form --}}
              {{ Form::open(array('route' => 'reception.saveunscannedbarcode', 'class' => '', 'id' => 'unscanned')) }}
            {{ csrf_field() }}
            <div class="form-group">
              <label for="hub" class="control-label">Hub</label>
              <div>
                {{Form::select('hubid', $hubs, old('hubid'), ['class'=>'form-control input-lg select-field'])}}                     
              </div>
            </div>
             <div class="form-group">
              <label for="hub" class="control-label">Facility</label>
              <div>
                {{Form::select('facilityid', $all_facilities, old('facilityid'), ['class'=>'form-control input-lg select-field', 'required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="hub" class="control-label">Sample Type</label>
              <div>
                {{Form::select('test_type', $test_types, old('test_type'), ['class'=>'form-control input-lg select-field', 'required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="hub" class="control-label">Is to be transfered?</label>
              <div>
                {{Form::select('is_to_be_transfered', [0=>'No',1=>'Yes'], old('is_to_be_transfered'), ['class'=>'form-control input-lg select-field', 'id'=>'is_to_be_transfered','required'=>'required'])}}                     
              </div>
            </div>
            <div class="form-group transfer_to hidden">
              <label for="hub" class="control-label">Transfer to</label>
              <div>
                {{Form::select('transfer_to', $all_facilities, old('transfer_to'), ['class'=>'form-control select-field input-lg transfer_to hidden', 'id'=>'transfer_to'])}}                     
              </div>
            </div>
            <div class="form-group">
              <label for="insurance" class="control-label">Barcode</label>

              <div>
                {{ Form::text('barcode', old('barcode'), array('class' => 'form-control', 'id' => 'barcode','required'=>'required')) }}
              </div>
            </div>            

            <div class="form-group">
              <label for="insurance" class="control-label">Number of Samples</label>

              <div>
                {{ Form::text('numberofsamples', old('numberofsamples'), array('class' => 'form-control', 'id' => 'numberofsamples')) }}
              </div>
            </div>
            <div class="form-group">
                <div>
                  {{ Form::hidden('type', 2) }}
                    <button type="submit" id="submit_form" class="btn btn-primary">Submit </button>
                    </button>
                </div>
            </div>
            {{ Form::close() }}
              <!-- /.box-footer -->
    </div>
@endsection 