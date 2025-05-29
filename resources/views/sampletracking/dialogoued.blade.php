 @if(($sample->status == 1) && (Auth::user()->hubid = $sample->destinationfacility))
        <a href="{{route('sampletracking.receivesample',['id' => $sample->id])}}">Receive Sample</a>
        @endif
<div class="modal fade refers" tabindex="-1" role="dialog" id="samplemodal_{{$sample->id}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Select the destination facility</h4>
      </div>
      <div class="modal-body">
      	<div class="box box-info no-border"> 
      	{{ Form::open(array('url' => 'sampletracking/savereferral', 'class' => 'form-horizontal')) }}
  {{ csrf_field() }}  			
            <div class="form-group">
              <label for="dateofweek" class="col-sm-3 control-label">{{ Form::label('destinationid', 'Destination Facility') }}</label>
              <div class="col-sm-9">
                {{Form::select('destinationid', $hubs, null, ['class'=>'form-control'])}}
                {{Form::hidden('sourceid', Auth::user()->id)}}
              </div>
            </div>
                        
  			<div class="box-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </button>
            {{ Form::submit('Refer', array('class' => 'btn btn-info pull-right')) }} </div>
          <!-- /.box-footer --> 
          
          {{ Form::close() }} </div>
  		</div> 
      </div>
     </div>
    </div>