@extends('layouts.master')

@section('title') Alert Rules @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Alert Rules & Configuration</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Manage Alert Engine Triggers</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="rulesTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Channels</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="rulesBody">
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
    fetch('/alerts/ajax/rules', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status) {
            let html = '';
            data.data.forEach(rule => {
                let channels = rule.channels ? rule.channels.join(', ') : 'None';
                html += `<tr>
                    <td>${rule.name}</td>
                    <td>${rule.category}</td>
                    <td>${rule.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Disabled</span>'}</td>
                    <td>${rule.priority}</td>
                    <td>${channels}</td>
                    <td>
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </td>
                </tr>`;
            });
            document.getElementById('rulesBody').innerHTML = html;
        }
    });
</script>
@endsection
