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
                    <td><a href="{{ route('staff.edit', $st->id ) }}"><i class="fa fa-fw fa-edit"></i>Activate User</a>&nbsp;
                        <a href="{{ route('staff.destroy', $st->id ) }}" class="hidden"><i class=" fa fa-fw fa-trash-o"></i>Delete</a>
                        <a href="{{  url('user/resetpassword',['id' => $st->id]) }}"><i class=" fa fa-user"></i>Change Password</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->

</div>
@endsection