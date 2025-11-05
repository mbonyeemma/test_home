@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pending Field Change Requests</h2>

        @if ($pendingChanges->isEmpty())
            <div class="alert alert-info">No pending field changes.</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" id="flash-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible" id="flash-error">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('error') }}
            </div>
        @endif
        @foreach ($pendingChanges as $change)
            @if($loop->first || $loop->index % 2 === 0)
            <div class="row">
            @endif

            <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Action: <span class="text-primary">{{ ucfirst($change->action) }}</span></h3>
                    <div class="box-tools pull-right">
                        <small class="text-muted">Submitted: {{ $change->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="box-body">
                    <p class="mb-1">Form: <strong>{{ $change->form->name ?? 'Form' }}</strong></p>
                    <p class="mb-2">Maker: <strong>{{ $change->maker->name }}</strong></p>

                    @php($data = $change->field_data)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="margin-bottom:0;">
                            <tbody>
                                @if(isset($data['field_label']))
                                <tr>
                                    <th style="width:220px;">Field label</th>
                                    <td>{{ $data['field_label'] }}</td>
                                </tr>
                                @endif
                                @if(isset($data['name']))
                                <tr>
                                    <th>Field name</th>
                                    <td>{{ $data['name'] }}</td>
                                </tr>
                                @endif
                                @if(isset($data['field_type']))
                                <tr>
                                    <th>Type</th>
                                    <td>{{ ucfirst($data['field_type']) }}</td>
                                </tr>
                                @endif
                                @if(array_key_exists('field_value', $data))
                                <tr>
                                    <th>Default value</th>
                                    <td>{{ $data['field_value'] === null ? '—' : $data['field_value'] }}</td>
                                </tr>
                                @endif
                                @if(isset($data['option']))
                                <tr>
                                    <th>Option</th>
                                    <td>{{ ucfirst($data['option']) }}</td>
                                </tr>
                                @endif
                                @if(isset($data['status']))
                                <tr>
                                    <th>Status</th>
                                    <td>{{ ucfirst($data['status']) }}</td>
                                </tr>
                                @endif
                                @if(isset($data['dropdown_options']))
                                <tr>
                                    <th>Dropdown options</th>
                                    <td>
                                        @if(is_array($data['dropdown_options']))
                                            {{ implode(', ', $data['dropdown_options']) ?: '—' }}
                                        @else
                                            {{ $data['dropdown_options'] ?? '—' }}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if($change->field)
                                <tr>
                                    <th>Target field (existing)</th>
                                    <td>#{{ $change->field->id }} — {{ $change->field->field_label ?? $change->field->name }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="pull-right">
                        <form method="POST" action="{{ route('field-changes.approve', $change->id) }}" style="display:inline-block; margin-left:8px;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('field-changes.reject', $change->id) }}" style="display:inline-block; margin-left:8px;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>

            @if($loop->last || $loop->index % 2 === 1)
            </div>
            @endif
        @endforeach
        <script>
        (function(){
            var s = document.getElementById('flash-success');
            if(s){ setTimeout(function(){ if(window.jQuery){ jQuery(s).slideUp(300, function(){ this.remove(); }); } else { s.style.display='none'; } }, 4000); }
            var e = document.getElementById('flash-error');
            if(e){ setTimeout(function(){ if(window.jQuery){ jQuery(e).slideUp(300, function(){ this.remove(); }); } else { e.style.display='none'; } }, 6000); }
        })();
        </script>
    </div>
@endsection
