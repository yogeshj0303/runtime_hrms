<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000000;
        padding: 5px;
    }
    th {
        background-color: #2F75B5;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
    }
    .title {
        font-size: 18px;
        font-weight: bold;
        text-align: left;
        color: #2F75B5;
    }
</style>

<table>
    <tr>
        <td colspan="{{ count($selectedColumns) }}" class="title">Employee Register Report</td>
    </tr>
    <tr>
        <td colspan="{{ count($selectedColumns) }}">Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</td>
    </tr>
    <tr>
        <td colspan="{{ count($selectedColumns) }}"></td>
    </tr>
    <tr>
        @foreach($selectedColumns as $c)
            <th>{{ $columnsMap[$c] ?? ucfirst($c) }}</th>
        @endforeach
    </tr>
    @foreach($employees as $emp)
    <tr>
        @php $wp = $emp->workProfiles->firstWhere('is_current', true); @endphp
        @foreach($selectedColumns as $c)
            @if($c == 'empCode') <td>{{ $emp->employee_code }}</td>
            @elseif($c == 'empName') <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
            @elseif($c == 'gender') <td>{{ optional($emp->profile)->gender ?? '-' }}</td>
            @elseif($c == 'dob') <td>{{ optional($emp->profile)->dob ? \Carbon\Carbon::parse($emp->profile->dob)->format('d-M-Y') : '-' }}</td>
            @elseif($c == 'doj') <td>{{ $emp->joining_date ? $emp->joining_date->format('d-M-Y') : '-' }}</td>
            @elseif($c == 'exitDate') <td>{{ optional($emp->profile)->resignation_date ? \Carbon\Carbon::parse($emp->profile->resignation_date)->format('d-M-Y') : '-' }}</td>
            @elseif($c == 'empStatus') <td>{{ ucfirst($emp->status) }}</td>
            @elseif($c == 'empType') <td>{{ optional($emp->profile)->employment_type ?? '-' }}</td>
            @elseif($c == 'mobile') <td>{{ $emp->phone ?? '-' }}</td>
            @elseif($c == 'email') <td>{{ $emp->email ?? '-' }}</td>
            @elseif($c == 'personalEmail') <td>{{ optional($emp->profile)->personal_email ?? '-' }}</td>
            @elseif($c == 'currentAddress') <td>{{ optional($emp->addresses->where('type', 'current')->first())->address1 ?? '-' }}</td>
            @elseif($c == 'permanentAddress') <td>{{ optional($emp->addresses->where('type', 'permanent')->first())->address1 ?? '-' }}</td>
            @elseif($c == 'city') <td>{{ optional($emp->addresses->first())->city ?? '-' }}</td>
            @elseif($c == 'state') <td>{{ optional($emp->addresses->first())->state ?? '-' }}</td>
            @elseif($c == 'pincode') <td>{{ optional($emp->addresses->first())->zipcode ?? '-' }}</td>
            @elseif($c == 'businessUnit') <td>{{ optional(optional($wp)->businessUnit)->name ?? optional($emp->business)->name ?? '-' }}</td>
            @elseif($c == 'location') <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
            @elseif($c == 'costCenter') <td>{{ optional(optional($wp)->costCenter)->name ?? optional(optional($wp)->costCenter)->code ?? '-' }}</td>
            @elseif($c == 'department') <td>{{ optional(optional($wp)->department)->name ?? $emp->department ?? '-' }}</td>
            @elseif($c == 'designation') <td>{{ optional(optional($wp)->designation)->name ?? $emp->designation ?? '-' }}</td>
            @elseif($c == 'grade') <td>{{ optional(optional($wp)->grade)->name ?? '-' }}</td>
            @elseif($c == 'manager') <td>{{ optional(optional($wp)->reportingManager)->first_name ?? '-' }}</td>
            @elseif($c == 'shiftPolicy') <td>{{ optional(optional($emp->policyAssignment)->shift)->name ?? '-' }}</td>
            @elseif($c == 'pan') <td>{{ optional($emp->identity)->pan_number ?? '-' }}</td>
            @elseif($c == 'aadhar') <td>{{ optional($emp->identity)->aadhaar_number ?? '-' }}</td>
            @elseif($c == 'pf') <td>{{ optional($emp->identity)->pf_uan ?? '-' }}</td>
            @elseif($c == 'esi') <td>{{ optional($emp->identity)->esi_number ?? '-' }}</td>
            @elseif($c == 'bankName') <td>{{ optional($emp->identity)->bank_name ?? '-' }}</td>
            @elseif($c == 'ifsc') <td>{{ optional($emp->identity)->ifsc ?? '-' }}</td>
            @elseif($c == 'account') <td>{{ optional($emp->identity)->account_number ?? '-' }}</td>
            @else <td>-</td>
            @endif
        @endforeach
    </tr>
    @endforeach
</table>
