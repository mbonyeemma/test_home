@extends('layouts.app')
@section('title', 'Mobile App Registrations')


@section('content')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
@append
@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script>
    $(document).ready(function() {
        //$('#listtable').DataTable();
        $('#stafflisttable').DataTable({
            dom: 'Bfrtip',
            buttons: [

                {
                    extend: 'excelHtml5'
                }
            ]
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#rejectModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('id'); // Extract info from data-* attributes
            $(this).find('#rejectUserId').val(userId);
        });
    });
    </script>
@append
<div class="box box-info">

    <!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="stafflisttable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <!-- <th>District Where Hub is Located</th>
                    <th>Hub</th>
                    <th>Facilities Served</th> -->

                    <th>Transporter Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Riding / Driving Permit</th>
                    <th>Hub Name</th>
                    <th>Defensive Driving</th>
                    <th>Trained in BB</th>
                    <th>Is Immunised for HB</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staff as $st)
                <tr>
                    <td>{{ $st->name }}</td>
                    <td>{{ $st->telephone_number }}</td>
                    <td>{{ $st->email }}</td>
                    <td>{{ $st->driving_permit }}</td>
                    <td>{{ $st->hubname }}</td>
                    <td>{{ $st->defensive_driving }}</td>
                    <td>{{ $st->bb_training }}</td>
                    <td>{{ $st->hep_b_immunisation }}</td>
                    <td>
                        @if($st->isactive == 0)
                            @if($st->fullyApproved)
                                <button class="btn btn-secondary m-2" disabled>
                                    <i class="fa fa-fw fa-check"></i> Approved
                                </button>
                            @elseif($st->hasApproved)
                                <button class="btn btn-success m-2" disabled>
                                    <i class="fa fa-fw fa-check"></i> You Approved
                                </button>
                            @else
                                <a class="btn btn-success m-2" href="{{ route('staff.approve', $st->id) }}">
                                    <i class="fa fa-fw fa-edit"></i> Approve
                                </a>
                                <button class="btn btn-warning m-2" data-toggle="modal" data-target="#rejectModal" data-id="{{ $st->id }}">
                                    <i class="fa fa-fw fa-times"></i> Reject
                                </button>
                            @endif
                        @elseif($st->isactive == 1)
                            <button class="btn btn-secondary m-2" disabled>
                                <i class="fa fa-fw fa-check"></i> Approved
                            </button>
                        @elseif($st->isactive == 2)
                            <button class="btn btn-secondary m-2" disabled>
                                <i class="fa fa-fw fa-ban"></i> Rejected
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{ route('staff.rejectWithReason') }}">
        @csrf
        <input type="hidden" name="id" id="rejectUserId">
  
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="rejectModalLabel">Reject Staff</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="reason">Reason for Rejection</label>
              <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Reject</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
@endsection