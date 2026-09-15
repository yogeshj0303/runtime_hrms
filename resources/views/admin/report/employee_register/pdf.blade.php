<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Register Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2F75B5;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #2F75B5;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #2F75B5;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Employee Register</h2>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($selectedColumns as $c)
                    <th>{{ $columnsMap[$c] ?? ucfirst($c) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>

</body>
</html>
