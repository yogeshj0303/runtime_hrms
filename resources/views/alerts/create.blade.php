@extends('layouts.master')

@section('title') Send Manual Alert @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Send Manual Broadcast Alert</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Compose Alert</h5>
            </div>
            <div class="card-body">
                <form id="broadcastForm">
                    <div class="mb-3">
                        <label class="form-label">Message / Announcement</label>
                        <textarea class="form-control" id="message" rows="4" required placeholder="Type your broadcast message here..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-select" id="priority" required>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Audience</label>
                            <select class="form-select" id="target_type" required>
                                <option value="all">All Employees</option>
                                <option value="department">Specific Department</option>
                                <option value="employee">Specific Employee</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3" id="target_ids_container" style="display: none;">
                        <label class="form-label">Select Targets (IDs)</label>
                        <input type="text" class="form-control" id="target_ids" placeholder="e.g. 1,2,5">
                        <small class="text-muted">Comma separated IDs</small>
                    </div>

                    <button type="submit" class="btn btn-primary" id="sendBtn">Send Broadcast</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('target_type').addEventListener('change', function() {
        if (this.value === 'all') {
            document.getElementById('target_ids_container').style.display = 'none';
        } else {
            document.getElementById('target_ids_container').style.display = 'block';
        }
    });

    document.getElementById('broadcastForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let type = document.getElementById('target_type').value;
        let idsStr = document.getElementById('target_ids').value;
        let targetIds = idsStr ? idsStr.split(',').map(id => id.trim()) : [];

        let payload = {
            message: document.getElementById('message').value,
            priority: document.getElementById('priority').value,
            target_type: type,
            target_ids: type !== 'all' ? targetIds : null
        };

        let btn = document.getElementById('sendBtn');
        btn.innerHTML = 'Sending...';
        btn.disabled = true;

        fetch('/alerts/ajax/broadcast', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = 'Send Broadcast';
            btn.disabled = false;
            if(data.status) {
                alert(data.message);
                document.getElementById('broadcastForm').reset();
                document.getElementById('target_ids_container').style.display = 'none';
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
</script>
@endsection
