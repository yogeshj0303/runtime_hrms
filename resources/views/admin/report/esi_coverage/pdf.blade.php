<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ESI Coverage Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        h3 { text-align: center; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h3>ESI Coverage Report ({{ strtoupper(\Carbon\Carbon::parse($month)->format('M-Y')) }})</h3>
    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>CODE</th>
                <th>EMPLOYEE NAME</th>
                <th>DEPARTMENT</th>
                <th>LOCATION</th>
                <th>GROSS SALARY</th>
                <th>ESI NUMBER</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['employee']->employee_code ?? '-' }}</td>
                    <td>{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                    <td>{{ optional(optional($item['employee']->workProfiles->first())->department)->name ?? '-' }}</td>
                    <td>{{ optional(optional($item['employee']->workProfiles->first())->location)->name ?? '-' }}</td>
                    <td>{{ $item['gross_salary'] }}</td>
                    <td>{{ $item['esi_number'] ?? '-' }}</td>
                    <td>{{ $item['status_str'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
