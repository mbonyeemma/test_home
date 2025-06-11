@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Approval Settings</h3>

    @if(session('success'))
        <div class="alert alert-primary">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Approval</button>
    <br><br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>No of Approvals</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->id }}</td>
                <td>{{ $setting->no_of_approval }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $setting->id }}">Edit</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $setting->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form action="{{ route('approval.update', $setting->id) }}" method="POST">
                  @csrf
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4>Edit Approval</h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label>No of Approvals</label>
                        <input type="number" name="no_of_approval" class="form-control" value="{{ $setting->no_of_approval }}" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary">Save</button>
                      <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('approval.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Add Approval</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>No of Approvals</label>
            <input type="number" name="no_of_approval" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Add</button>
          <button class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
