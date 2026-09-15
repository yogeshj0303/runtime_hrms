<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Inactive Employees Report</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>EMPLOYEE CODE</th>
                <th>EMPLOYEE NAME</th>
                <th>JOINING DATE</th>
                <th>LOCATION</th>
                <th>COST CENTER</th>
                <th>DEPARTMENT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $emp)
                @php
                    $wp = $emp->workProfiles->first();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emp->employee_code }}</td>
                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    <td>{{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('Y-m-d') : '-' }}</td>
                    <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
                    <td>{{ optional(optional($wp)->costCenter)->name ?? '-' }}</td>
                    <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
