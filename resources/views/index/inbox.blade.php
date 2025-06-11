<div class="col-md-3"> <a href="{{route('message.create')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="{{route('messages', ['types'=>1])}}"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right">{{ count($unread_inbox)}}</span></a></li>
                <li><a href="{{route('messages', ['types'=>2])}}"><i class="fa fa-envelope-o"></i> Sent <span class="label label-success pull-right">{{ count($sent)}}</span></a></li>
                <li><a href="{{route('messages', ['types'=>3])}}"><i class="fa fa-filter"></i> Drafts <span class="label label-warning pull-right">{{count($draft)}}</span></a> </li>
                <li><a href="{{route('messages', ['types'=>4])}}"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /. box -->
        </div>