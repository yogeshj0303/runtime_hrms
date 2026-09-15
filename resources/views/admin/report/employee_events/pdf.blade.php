<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Events Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
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
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #133C5A;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
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
        .date-col {
            font-weight: bold;
            color: #2F75B5;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Employee Events Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">DATE</th>
                <th width="18%">EVENT TYPE</th>
                <th width="25%">EMPLOYEE</th>
                <th width="15%">LOCATION</th>
                <th width="15%">DEPARTMENT</th>
                <th width="15%">DESIGNATION</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($events) && $events->count() > 0)
                @foreach($events as $event)
                <tr>
                    <td class="date-col">{{ $event['date'] }}</td>
                    <td>{{ $event['event_type'] }}</td>
                    <td>{{ $event['employee'] }}</td>
                    <td>{{ $event['location'] }}</td>
                    <td>{{ $event['department'] }}</td>
                    <td>{{ $event['designation'] }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No events found for the selected filters.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        SomyaHRMS - Employee Events Report &copy; {{ date('Y') }}
    </div>

</body>
</html>
