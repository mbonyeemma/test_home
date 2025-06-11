@extends('layouts.app')

@section('title', 'Create new SampleTransporter')

@section('content')
	<div class="box box-info">
            
            <!-- /.box-header -->
            <!-- form start -->
            {{-- Using the Laravel HTML Form Collective to create our form --}}
    {{ Form::open(array('route' => 'sampletransporters.store', 'class' => 'form-horizontal')) }}
            
              <div class="box-body">
                <div class="form-group">
                  <label for="firstname" class="col-sm-2 control-label">{{ Form::label('firstname', 'First Name') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('firstname', null, array('class' => 'form-control', 'id' => 'firstname')) }}
                  </div>
                </div>
               <div class="form-group">
                  <label for="lastname" class="col-sm-2 control-label">{{ Form::label('lastname', 'Last Name') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('lastname', null, array('class' => 'form-control', 'id' => 'lastname')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="othernames" class="col-sm-2 control-label">{{ Form::label('othernames', 'Other Names') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('othernames', null, array('class' => 'form-control', 'id' => 'othernames')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="emailaddress" class="col-sm-2 control-label">{{ Form::label('emailaddress', 'Email Address') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('emailaddress', null, array('class' => 'form-control', 'id' => 'emailaddress')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="telephonenumber" class="col-sm-2 control-label">{{ Form::label('telephonenumber', 'Telephone Number') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('telephonenumber', null, array('class' => 'form-control', 'id' => 'telephonenumber')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="drivingpermitnumber" class="col-sm-2 control-label">{{ Form::label('drivingpermitnumber', 'Driving Permit Number') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('drivingpermitnumber', null, array('class' => 'form-control', 'id' => 'drivingpermitnumber')) }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="nationalid" class="col-sm-2 control-label">{{ Form::label('nationalid', 'National ID') }}</label>

                  <div class="col-sm-10">
                    {{ Form::text('nationalid', null, array('class' => 'form-control', 'id' => 'nationalid')) }}
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                {{ Form::submit('Create Bike', array('class' => 'btn btn-info pull-right')) }}
              </div>
              <!-- /.box-footer -->
            
            {{ Form::close() }}
          </div>
@endsection 