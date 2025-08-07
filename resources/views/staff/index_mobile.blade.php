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
        // Reject modal
        $('#rejectModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('id'); // Extract info from data-* attributes
            $(this).find('#rejectUserId').val(userId);
        });

        // Approve modal
        $('#approveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('id'); // Extract info from data-* attributes
            var userName = button.data('name'); // Extract user name
            $(this).find('#approveUserId').val(userId);
            $(this).find('#approveUserName').text(userName);
        });

        // Handle approve button click
        $('#confirmApproveBtn').on('click', function() {
            var userId = $('#approveUserId').val();
            var button = $(this);
            var originalText = button.text();
            
            // Disable button and show loading
            button.prop('disabled', true).text('Approving...');
            
            // Make AJAX call to approve user
            $.ajax({
                url: '/api/restrack_new/approve_user/' + userId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    if (response.status === 200) {
                        // Show success message
                        alert('User approved successfully! User can now login.');
                        // Reload the page to update the table
                        location.reload();
                    } else {
                        alert('Error: ' + response.status_desc);
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'An error occurred while approving the user.';
                    if (xhr.responseJSON && xhr.responseJSON.status_desc) {
                        errorMessage = xhr.responseJSON.status_desc;
                    }
                    alert('Error: ' + errorMessage);
                },
                complete: function() {
                    // Re-enable button and restore text
                    button.prop('disabled', false).text(originalText);
                    // Close modal
                    $('#approveModal').modal('hide');
                }
            });
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
                                <button class="btn btn-success m-2" data-toggle="modal" data-target="#approveModal" data-id="{{ $st->id }}" data-name="{{ $st->name }}">
                                    <i class="fa fa-fw fa-check"></i> Approve
                                </button>
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approveModalLabel">Confirm Approval</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong><span id="approveUserName"></span></strong>?</p>
                <p class="text-info">
                    <i class="fa fa-info-circle"></i> 
                    This will transfer the user to the main system and allow them to login.
                </p>
                <input type="hidden" id="approveUserId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="confirmApproveBtn">
                    <i class="fa fa-check"></i> Confirm Approval
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

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