@extends('admin.employee.profile.layout')

@section('profile_title', 'Strikes & Disciplinary')
@section('profile_description', 'Manage attendance strikes and disciplinary actions for this employee.')

@section('profile_actions')
    <button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addStrikeModal">
        <i class="ri-add-line"></i> Add Manual Strike
    </button>
@endsection

@section('profile_content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Rule Applied</th>
                <th>Penalty/Action</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($strikes as $strike)
                <tr>
                    <td>{{ $strike->strike_date->format('d M, Y') }}</td>
                    <td>
                        @if($strike->strikeRule)
                            <span class="badge bg-{{ strtolower($strike->strikeRule->strike_color) == 'red' ? 'danger' : (strtolower($strike->strikeRule->strike_color) == 'yellow' ? 'warning' : 'primary') }}">
                                {{ $strike->strikeRule->rule_type }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Manual Assignment</span>
                        @endif
                    </td>
                    <td>
                        @if($strike->strikeRule)
                            {{ $strike->strikeRule->deduction_type }} 
                            @if($strike->strikeRule->deduction_value)
                                ({{ $strike->strikeRule->deduction_value }})
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $strike->reason }}</td>
                    <td>
                        @if($strike->status == 'Active')
                            <span class="badge bg-success-subtle text-success">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Waived</span>
                        @endif
                    </td>
                    <td>
                        @if($strike->status == 'Active')
                            <form action="{{ route('employee.profile.strikes.waive', ['id' => $employee->id, 'strike' => $strike->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-soft-danger" onclick="return confirm('Are you sure you want to waive this strike?')">Waive</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">No strikes found for this employee.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Strike Modal -->
<div class="modal fade" id="addStrikeModal" tabindex="-1" aria-labelledby="addStrikeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employee.profile.strikes.store', $employee->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStrikeModalLabel">Add Manual Strike</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date of Strike <span class="text-danger">*</span></label>
                        <input type="date" name="strike_date" class="form-control" required max="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Linked Rule (Optional)</label>
                        <select name="strike_rule_id" class="form-select">
                            <option value="">-- Select Rule --</option>
                            @foreach($strikeRules as $rule)
                                <option value="{{ $rule->id }}">{{ $rule->rule_type }} ({{ $rule->strike_color }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason / Notes <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Describe why this strike is being assigned..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Strike</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
