@extends('layouts.master')

@section('title') Alert History @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Alert History</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="alertsTable">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Triggered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="alertsBody">
                        <!-- Populated via JS API call -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function loadAlerts() {
        fetch('/alerts/ajax/list', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                let html = '';
                data.data.data.forEach(alert => {
                    html += `<tr>
                        <td>${alert.message}</td>
                        <td>${alert.status === 'pending' ? '<span class="badge bg-warning">Pending</span>' : '<span class="badge bg-success">Resolved</span>'}</td>
                        <td>${new Date(alert.created_at).toLocaleString()}</td>
                        <td>
                            ${alert.status === 'pending' ? `<button class="btn btn-sm btn-success" onclick="resolveAlert(${alert.id})">Resolve</button>` : ''}
                        </td>
                    </tr>`;
                });
                document.getElementById('alertsBody').innerHTML = html;
            }
        });
    }

    function resolveAlert(id) {
        fetch(`/alerts/ajax/${id}/resolve`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                loadAlerts(); // reload
            }
        });
    }

    // load on mount
    loadAlerts();
</script>
@endsection
