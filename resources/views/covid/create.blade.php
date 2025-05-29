@extends('layouts.app')

@section('title', 'Add Sample Details')
@section('css')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@append
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>

 <script>
	$(document).ready(function() {
    $('.date-field').datepicker({
       format: 'mm/dd/yyyy',
       endDate: '+0d',
       autoclose: true
    });
		$('#covidform').bootstrapValidator({
       
        fields: {
			
				transactiondate: {
                    validators: {
                        notEmpty: {
                            message: 'select date'
                        }
                    }
                }
		    }//endo of validation rules
    });// close form validation function

    $("#facility").select2();
      //add/remove rows
      var class_row_index=0;
      $("#add_row").click(function(){
          class_row_index++;
          $cloned_row = $("#class_row").clone();
          //remove any select2 from cloned dropdowns
          $cloned_row.find("span").remove();
          $cloned_row.find("select").select2();
          //change name of inputs and selects
          $cloned_row.find("input.form-control").attr("name","samples["+ class_row_index+ "][numberofsamples]");
          $cloned_row.find("select.facilities").attr("name","samples["+ class_row_index+ "][facilityid]").attr("required","required");
          $cloned_row.find("select.samples").attr("name","samples["+ class_row_index+ "][test_type]").attr("required","required");
          //reset the values of html attributes in the cloned row
          $cloned_row.find("input.form-control").val('');
          $cloned_row.find("select").val('').trigger('change');

          $(this).parent().before($cloned_row.attr("id","class_row" + class_row_index));
          //$("#class_row" + class_row_index);
          $("#class_row" + class_row_index + " :input").each(function(){
            $(this).attr("id",$(this).attr("id") + class_row_index);
          });
          $('#covidform').bootstrapValidator('destroy');
          $('#scovidform').data('bootstrapValidator', null);
          $('#covidform').bootstrapValidator();

          $("#remove_class_row" + class_row_index).click(function(){
            $(this).closest("div").remove();
          });
      });
	});


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
    		
            <div class="box-body">
              {{ Form::open(array('route' => 'covid.store', 'class' => '', 'id' => 'covidform')) }}
              {{ csrf_field() }}  
              
              <div class="row">
                <div class="form-group">
                  <label for="insurance" class="col-sm-2 control-label" style="width: 138px;">Transported On</label>

                  <div class="col-sm-2"  style="width: 200px;">
                    {{ Form::text('transactiondate', old('transactiondate'), array('class' => 'form-control date-field', 'id' => 'transactiondate','required'=> 'required')) }}
                  </div>
                </div>
              </div>
              <hr>
              <div class="container " id="class_samples" style="padding:0; margin:0;">

                <div class="row">
                  <div class="col-md-3">
                    <label for="abbreviation">Facility<span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-3">
                    <label for="abbreviation">Sample Typespan <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-3">
                    <label for="facility">Number of Samples<span class="text-danger">*</span></label>
                  </div>
                </div>
                
                  <div class="row" id="class_row">
                  <div class="col-md-3">
                    <div class="form-group has-feedback"> 
                      {{ Form::select('samples[0][facilityid]', $facilities, '', ['class' => 'form-control select2 facilities', 'id' => 'facility', 'required'=> 'required']) }}
                       <span class="form-control-feedback"></span> <span class="text-danger">{{ $errors->first('facilitie_name') }}</span> </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-feedback"> 
                      {{ Form::select('samples[0][test_type]', $test_types, '', ['class' => 'form-control select2 samples', 'id' => 'test_type', 'required'=> 'required']) }}
                       <span class="form-control-feedback"></span> <span class="text-danger">{{ $errors->first('facilitie_name') }}</span> </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-feedback">
                      <input  type="text" id="section" class="form-control" name="samples[0][numberofsamples]" placeholder="No. Samples"  value=""  required="required" min="1">
                      <span class="form-control-feedback"></span> <span class="text-danger">{{ $errors->first('facilitie_name') }}</span> </div>
                  </div>
                  
                  <input type="button" class="btn btn-danger btn-xs rm" id="remove_class_row" value="Remove">
                </div>
                <p>
                  <button type="button" value="add row" id="add_row" class="btn btn-success btn-xs" style=""><i class="fa  fa-plus-circle "></i> Add another row </button>
                </p>
            </div>

             
              <!-- /.box-body -->
              <div class="box-footer" style="width:75%;">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel</a>
                {{ Form::submit('Add Sample', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer transactiondate-->
            
            {{ Form::close() }}
          </div>
@endsection 