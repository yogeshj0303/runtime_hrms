<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Exits Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #133C5A;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #133C5A;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #133C5A;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .exit-col {
            font-weight: bold;
            color: #d9534f;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Employee Exits Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">SN</th>
                <th width="8%">E-CODE</th>
                <th width="15%">EMPLOYEE</th>
                <th width="12%">LOCATION</th>
                <th width="12%">DEPARTMENT</th>
                <th width="15%">DESIGNATION</th>
                <th width="10%">JOINING</th>
                <th width="10%">EXIT</th>
                <th width="13%">REASON OF EXIT</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($exits) && $exits->count() > 0)
                @foreach($exits as $index => $exit)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $exit->employee_code }}</td>
                    <td>{{ $exit->first_name }} {{ $exit->last_name }}</td>
                    <td>
                        @php $wp = $exit->workProfiles->where('is_current', true)->first(); @endphp
                        {{ $wp && $wp->location ? $wp->location->name : '-' }}
                    </td>
                    <td>{{ $wp && $wp->department ? $wp->department->name : '-' }}</td>
                    <td>{{ $wp && $wp->designation ? $wp->designation->name : '-' }}</td>
                    <td>{{ $exit->joining_date ? $exit->joining_date->format('d M, Y') : '-' }}</td>
                    <td class="exit-col">{{ $exit->exit_date->format('d M, Y') }}</td>
                    <td>{{ $exit->exitReason ? $exit->exitReason->reason_name : '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">No exits found for the selected filters.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        SomyaHRMS - Employee Exits Report &copy; {{ date('Y') }}
    </div>

</body>
</html>
