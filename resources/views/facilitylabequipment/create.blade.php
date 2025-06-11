@extends('layouts.app')

@section('title', 'Facility Lab Equipment')
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>

 <script>
  $(document).ready(function() {
    
    $('#delivereddate, #Verificationdate, #Installationdate, #purchasedon').datepicker({
       format: 'mm/dd/yyyy',
           endDate: '+0d',
       autoclose: true
    });
    
    $('#equipmentform').bootstrapValidator({
       
        fields: {
      labequipment_id: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the name'
                        }
                    }
                }, 
      model: {
        validators: {
          notEmpty: {
            message: 'Please select the model'
          }
        }
      }
    }//endo of validation rules
    });// close form validation function
  });
  
  $(function () {

    //Datemask dd/mm/yyyy
    $('#purchasedon,  #Verificationdate, #Installationdate, #delivereddate').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

  })
  (function($) {
    $.fn.bootstrapValidator.validators.greaterDate = {
      validate: function(validator, $field, options) {
        var value = $field.val();
        if (value === '') {
          return true;
        }
  
        return true;
      }
    };
}(window.jQuery));
</script>
@append

@section('content')
  <div class="box box-info">
    
    @if ( ! $errors->isEmpty() )
  <div class="row">
    @foreach ( $errors->all() as $error )
    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    <strong>Failed!</strong>{{ $error }} </div>   
    @endforeach
  </div>
  @endif
            <!-- /.box-header -->
            <!-- form start -->
            {{-- Using the Laravel HTML Form Collective to create our form --}}
        {{ Form::open(array('route' => 'labequipment.store', 'class' => 'form-horizontal', 'id' => 'equipmentform')) }}
            {{ csrf_field() }}
              <div class="box-body create">

                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label">{{ Form::label('labequipment_id', 'Name') }}</label>

                  <div class="col-sm-9">
                    {{ Form::select('labequipment_id', $labequipmentdropdown, null, ['class' => 'form-control']) }}
                  </div>
                </div>

                <div class="form-group">
                  <label for="model" class="col-sm-3 control-label">{{ Form::label('model', 'Model') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('model', null, array('class' => 'form-control', 'id' => 'model')) }}
                  </div>
                </div>

                <div class="form-group">
                  <label for="serialnumber" class="col-sm-3 control-label">{{ Form::label('serialnumber', 'Serial Number') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('serialnumber', null, array('class' => 'form-control', 'id' => 'serialnumber')) }}
                  </div>
                </div> 

                 <div class="form-group">
                  <label for="labsection" class="col-sm-3 control-label">{{ Form::label('labsection', 'Lab Section') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('labsection', $labsectiondropdown, old('labsectiondropdown'), ['class' => 'form-control']) }}
                  </div> 

                </div> 


                <div class="form-group">
                  <label for="procurementtype" class="col-sm-3 control-label">{{ Form::label('procurementtype', 'Procurement Type') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('procurementtype', $procurementtypedropdown, old('procurementtypedropdown'), ['class' => 'form-control']) }}
                  </div> 
                </div>

                <div class="form-group">
                  <label for="purchasedon" class="col-sm-3 control-label">{{ Form::label('purchasedon', 'Purchased Date') }}</label>
                  <div class="col-sm-9">                    
                    <input name="purchasedon" id="purchasedon" class="form-control"  type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label for="delivereddate" class="col-sm-3 control-label">{{ Form::label('delivereddate', 'Delivery Date') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="delivereddate" id="delivereddate" class="form-control"  type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label for="Verificationdate" class="col-sm-3 control-label">{{ Form::label('Verificationdate', 'Verification Date') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="Verificationdate" id="Verificationdate" class="form-control"  type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="Installationdate" class="col-sm-3 control-label">{{ Form::label('Installationdate', 'Installation Date') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="Installationdate" id="Installationdate" class="form-control"  type="text">
                  </div>
                </div>


                <div class="form-group">
                  <label for="hasspearparts" class="col-sm-3 control-label">{{ Form::label('hasspearparts', 'Spare Parts') }}</label>
                  <div class="col-sm-8">
                    {{ Form::radio('hasspearparts', 1, null, ['class' => '']) }} Yes
                    {{ Form::radio('hasspearparts', 0, null, ['class' => '']) }} No
                  </div><div class="col-sm-1"></div>  
                </div>

                <div class="form-group">
                  <label for="warrantperiod" class="col-sm-3 control-label">{{ Form::label('warrantperiod', 'Warranty Period') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('warrantperiod', $warrantyperioddropdown, null, ['class' => 'form-control']) }}
                  </div> 
                </div> 


              
                <div class="form-group">
                  <label for="insurance" class="col-sm-3 control-label">{{ Form::label('Lifetime', 'Lifetime') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('Lifetime', null, array('class' => 'form-control', 'id' => 'Lifetime')) }}
                  </div>
                </div>

                <div class="form-group">
                  <label for="servicefrequency" class="col-sm-3 control-label">{{ Form::label('servicefrequency', 'Service Frequency') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('servicefrequency', $servicefrequencydropdown, old('servicefrequencydropdown'), ['class' => 'form-control']) }}
                  </div> 
                </div> 
                <div class="form-group">
                  <label for="warrantyperiod" class="col-sm-3 control-label">{{ Form::label('hasservicecontract', 'Service Contract') }}</label>
                  <div class="col-sm-8">
                    {{ Form::radio('hasservicecontract', 1, null, ['class' => '']) }} Yes
                    {{ Form::radio('hasservicecontract', 0, null, ['class' => '']) }} No
                  </div><div class="col-sm-1"></div>  
                </div>
                <div class="form-group">
                  <label for="supplier" class="col-sm-3 control-label">{{ Form::label('supplier', 'Supplier') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('supplier', $supplierdropdown, null, ['class' => 'form-control']) }}
                  </div> 
                </div> 
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Add Equipment', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 