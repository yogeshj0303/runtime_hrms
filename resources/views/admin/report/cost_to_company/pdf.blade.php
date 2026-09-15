<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cost To Company Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; font-size: 9px; }
        td { font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Cost To Company Report</h2>
        <p>Generated on {{ date('d-M-Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>CODE</th>
                <th>NAME</th>
                <th>DESIGNATION</th>
                <th>DEPARTMENT</th>
                <th>REV. DATE</th>
                <th>BASIC</th>
                <th>HRA</th>
                <th>ALLOWANCES</th>
                <th>GROSS</th>
                <th>PF</th>
                <th>ESI</th>
                <th>PT</th>
                <th>TDS</th>
                <th>NET</th>
                <th>CTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exportData as $data)
                @php
                    $emp = $data['employee'];
                    $rev = $data['revision'];
                    $wp = $emp->workProfiles->first();
                @endphp
                <tr>
                    <td>{{ $emp->employee_code }}</td>
                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
                    <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($rev->effective_from)->format('d-M-Y') }}</td>
                    <td>{{ $rev->basic_salary }}</td>
                    <td>{{ $rev->hra }}</td>
                    <td>{{ $rev->allowances }}</td>
                    <td>{{ $rev->gross_salary }}</td>
                    <td>{{ $rev->pf }}</td>
                    <td>{{ $rev->esi }}</td>
                    <td>{{ $rev->pt }}</td>
                    <td>{{ $rev->tds }}</td>
                    <td>{{ $rev->net_salary }}</td>
                    <td>{{ $rev->ctc }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
