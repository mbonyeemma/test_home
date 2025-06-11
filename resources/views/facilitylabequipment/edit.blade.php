@extends('layouts.app')

@section('title', 'Equipment Inventory')

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
      name: {
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
        {{ Form::model($labequipment, array('route' => array('labequipment.update', $labequipment->id),  'class' => 'form-horizontal', 'id' => 'equipmentform', 'method' => 'PUT')) }}
          {{ csrf_field() }}
              <div class="box-body create">
              <!-- <div class="form-group">
                  <label for="facility" class="col-sm-3 control-label">{{ Form::label('facility', 'Hub') }}</label>

                  <div class="col-sm-9">
                    {{ Form::select('facilityid', $hubsdropdown, null, ['class' => 'form-control']) }}
                     
                  </div>
                </div> -->

                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label">{{ Form::label('name', 'Name') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) }}
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
                    {{ Form::text('serial_number', null, array('class' => 'form-control', 'id' => ' serial_number')) }}
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
                    
                    <input name="purchasedon" id="purchasedon" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text">
                  </div>
                </div>



                <div class="form-group">
                  <label for="delivereddate" class="col-sm-3 control-label">{{ Form::label('delivereddate', 'Delivery Date') }}</label>
                  <div class="col-sm-9">
                    
                    <input name="delivery_date" id="delivery_date" class="form-control"  type="text">
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
                  <label for="warrantperiod" class="col-sm-3 control-label">{{ Form::label('warrantperiod', 'Warrant Period') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('warrantperiod', $warrantyperioddropdown, old('warrantyperioddropdown'), ['class' => 'form-control']) }}
                  </div> 
                </div> 
 
                <div class="form-group">
                  <label for="insurance" class="col-sm-3 control-label">{{ Form::label('Lifetime', 'Lifetime') }}</label>

                  <div class="col-sm-9">
                    {{ Form::text('life_span', null, array('class' => 'form-control', 'id' => 'life_span')) }}
                  </div>
                </div>

                <div class="form-group">
                  <label for="servicefrequency" class="col-sm-3 control-label">{{ Form::label('servicefrequency', 'Service Frequency') }}</label>
                  <div class="col-sm-9">
                    {{ Form::select('servicefrequency', $servicefreqdropdown, old('servicefreqdropdown'), ['class' => 'form-control']) }}
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
                    {{ Form::select('supplier', $warrantyperioddropdown, old('warrantyperioddropdown'), ['class' => 'form-control']) }}
                  </div> 
                </div> 
                
                
                     
                
                
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a></button>
                {{ Form::submit('Update Lab Equipment', array('class' => 'btn btn-warning pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 