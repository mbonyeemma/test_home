@extends('layouts.app')
@section('title', 'Samples Awaiting Pickup')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
<style>
    .btn-request-rider {
        background-color: #f39c12;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }
    .btn-request-rider:hover {
        background-color: #e67e22;
    }
    .btn-request-rider:disabled {
        background-color: #95a5a6;
        cursor: not-allowed;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        background-color: #fff3cd;
        color: #856404;
    }
</style>
@endsection

@section('listpagejs')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#samplesTable').DataTable({
        order: [[5, 'desc']],
        pageLength: 25
    });

    $('.btn-request-rider').click(function() {
        var packageId = $(this).data('package-id');
        var barcode = $(this).data('barcode');
        var button = $(this);
        
        if (!confirm('Send pickup request to all riders for package ' + barcode + '?')) {
            return;
        }
        
        button.prop('disabled', true).text('Sending...');
        
        $.ajax({
            url: '/samples/request-rider/' + packageId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                button.text('✓ Request Sent!').css('background-color', '#27ae60');
                
                alert('Pickup request sent successfully!\n\n' +
                      'Riders notified: ' + response.summary.total_riders + '\n' +
                      'Emails sent: ' + response.summary.emails_sent + '\n' +
                      'SMS sent: ' + response.summary.sms_sent + '\n' +
                      'App notifications: ' + response.summary.app_notifications);
                
                setTimeout(function() {
                    button.prop('disabled', false)
                          .text('🏍️ Request Rider')
                          .css('background-color', '');
                }, 3000);
            },
            error: function(xhr) {
                console.log('Full error response:', xhr);
                var errorMsg = 'Failed to send request';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMsg = 'Server error: ' + xhr.statusText + ' (Status: ' + xhr.status + ')';
                }
                
                alert('Error Details:\n\n' + 
                      'Status Code: ' + xhr.status + '\n' +
                      'Message: ' + errorMsg + '\n\n' +
                      'Please check:\n' +
                      '1. You are logged in\n' +
                      '2. This package belongs to your hub\n' +
                      '3. Check browser console (F12) for more details');
                
                console.error('Request failed:', errorMsg);
                console.error('Response:', xhr.responseText);
                button.prop('disabled', false).text('Request Rider');
            }
        });
    });
});
</script>
@endsection

@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Samples Awaiting Pickup</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        
        <div class="box-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table id="samplesTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Facility</th>
                            <th>Test Type</th>
                            <th>Samples</th>
                            <th>Status</th>
                            <th>Date Prepared</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preparedSamples as $sample)
                            <tr>
                                <td><strong>{{ $sample->barcode }}</strong></td>
                                <td>{{ $sample->facility_name }}</td>
                                <td>{{ $sample->test_type_name ?? 'N/A' }}</td>
                                <td>{{ $sample->numberofsamples }}</td>
                                <td>
                                    <span class="status-badge">
                                        Awaiting Pickup
                                    </span>
                                </td>
                                <td>{{ date('Y-m-d H:i', strtotime($sample->created_at)) }}</td>
                                <td>
                                    <button 
                                        class="btn-request-rider" 
                                        data-package-id="{{ $sample->id }}"
                                        data-barcode="{{ $sample->barcode }}">
                                        🏍️ Request Rider
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <p style="padding: 20px; color: #666;">
                                        No prepared samples waiting for pickup at this time.
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

