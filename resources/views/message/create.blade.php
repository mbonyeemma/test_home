@extends('layouts.master')
@section('title', 'Mailbox')
@section('content')
@section('css')
<link rel="stylesheet" href="{{ asset('inbox/css/bootstrap3-wysihtml5.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@append
@section('js')
<script src="{{ asset('js/bootstrapValidator.min-0.5.1.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script> 
<script src="{{ asset('inbox/js/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$('.select2').select2();
	});
	$(function () {
    //Add text editor
    	$("#compose-textarea").wysihtml5();
  	});
</script>
@append
	<div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            {{ Form::open(array('route' => 'message.store', 'class' => '', 'id' => 'staffform')) }}
            	{{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <!-- <input class="form-control" placeholder="To:" name="to_id"> -->
                {{ Form::select('receivers[]', $users, null, ['class' => 'form-control select2 select2-hidden-accessible', 'multiple'=>"",'style'=>'width: 100%;', 'tabindex'=>'"-1"', 'aria-hidden'=>'"true"', 'data-placeholder'=>'Select receivers']) }}
              </div>
              <div class="form-group">
                <input class="form-control" name="subject" placeholder="Subject:">
              </div>
              <div class="form-group">
                    <textarea id="compose-textarea" name="content" class="form-control" style="height: 300px">
                      
                    </textarea>
                    {{Form::hidden('senderid',Auth::user()->id)}}
              </div>
              <div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input name="attachment" type="file">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div>
            </div>
            
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
            </div>
            <!-- /.box-footer -->
          </div>
          {{ Form::close() }}
          <!-- /. box -->
        </div>
@endsection 