@extends('layouts.app')
	@section('title', 'View User: '.$user->name)
@section('content')
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
  <div class="col-xs-12 table-responsive">
    <table class="table">
      <tbody>
        <tr>
          <td>Name</td>
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <td>Username</td>
          <td>{{ $user->username }}</td>
        </tr>
        <tr>
          <td>Email </td>
          <td>{{ $user->email }}</td>
        </tr>
       
      </tbody>
    </table>
    </div>
    <div class="box-footer clearfix">  
                <a href="{{URL::previous()}}" class="btn btn-sm btn-default pull-left">Back</a>
                <a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-warning pull-right">Update User</a> </div>
  </div>
</div>
@endsection 