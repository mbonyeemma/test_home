@extends('layouts.app')
@section('title', 'Eligible Riders for Pickup Notifications')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<style>
    .rider-role {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }
    .role-transporter {
        background-color: #3498db;
        color: white;
    }
    .role-special {
        background-color: #9b59b6;
        color: white;
    }
    .role-private {
        background-color: #e67e22;
        color: white;
    }
    .stats-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .stats-item {
        display: inline-block;
        margin-right: 30px;
    }
    .stats-number {
        font-size: 24px;
        font-weight: bold;
        color: #3498db;
    }
    .stats-label {
        font-size: 13px;
        color: #666;
    }
</style>
@endsection

@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#ridersTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25
    });
});
</script>
@endsection

@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Eligible Riders for Pickup Notifications</h3>
            <div class="box-tools pull-right">
                <a href="{{ route('samples.prepared') }}" class="btn btn-sm btn-default">
                    Back to Prepared Samples
                </a>
            </div>
        </div>
        
        <div class="box-body">
            <div class="stats-box">
                <h4>Hub: <strong>{{ $hubName }}</strong></h4>
                <div style="margin-top: 15px;">
                    <div class="stats-item">
                        <div class="stats-number">{{ count($riders) }}</div>
                        <div class="stats-label">Total Active Riders</div>
                    </div>
                    <div class="stats-item">
                        <div class="stats-number">{{ $riders->where('email', '!=', null)->count() }}</div>
                        <div class="stats-label">With Email</div>
                    </div>
                    <div class="stats-item">
                        <div class="stats-number">{{ $riders->where('phone_number', '!=', null)->count() }}</div>
                        <div class="stats-label">With Phone</div>
                    </div>
                </div>
            </div>
            
            <p style="color: #666; margin-bottom: 20px;">
                These riders will receive notifications when you request pickup for prepared packages.
            </p>
            
            <div class="table-responsive">
                <table id="ridersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>Registered Since</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riders as $index => $rider)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $rider->name }}</strong>
                                    @if($rider->staff_name && trim($rider->staff_name) !== '')
                                        <br><small style="color: #999;">{{ $rider->staff_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $rider->username }}</td>
                                <td>
                                    @if($rider->role_name === 'sample_transporter')
                                        <span class="rider-role role-transporter">Sample Transporter</span>
                                    @elseif($rider->role_name === 'special_sample_transportor')
                                        <span class="rider-role role-special">Special Transporter</span>
                                    @elseif($rider->role_name === 'private_rider')
                                        <span class="rider-role role-private">Private Rider</span>
                                    @else
                                        <span class="rider-role">{{ ucwords(str_replace('_', ' ', $rider->role_name)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rider->email)
                                        {{ $rider->email }}
                                    @else
                                        <span style="color: #999;">No email</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rider->phone_number)
                                        {{ $rider->phone_number }}
                                    @else
                                        <span style="color: #999;">No phone</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rider->isactive)
                                        <span style="color: #27ae60;">Active</span>
                                    @else
                                        <span style="color: #e74c3c;">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ date('Y-m-d', strtotime($rider->created_at)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <p style="padding: 20px; color: #666;">
                                        No eligible riders found for your hub. Please contact the administrator.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

