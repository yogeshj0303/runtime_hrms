<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Assets Report</title>
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
        <h2>Employee Assets</h2>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">SN</th>
                <th>EMPLOYEE</th>
                <th>ASSET TYPE</th>
                <th>ASSET NAME</th>
                <th>SERIAL NO</th>
                <th>ISSUE DATE</th>
                <th>EXPIRY DATE</th>
                <th>EST VALUE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ optional($asset->employee)->employee_code }} - {{ optional($asset->employee)->first_name }} {{ optional($asset->employee)->last_name }}</td>
                <td>{{ $asset->asset_type }}</td>
                <td>{{ $asset->asset_name }}</td>
                <td>{{ $asset->serial_number }}</td>
                <td>{{ $asset->issue_date ? \Carbon\Carbon::parse($asset->issue_date)->format('d-M-Y') : '-' }}</td>
                <td>
                    @if($asset->expiry_date)
                        @if(\Carbon\Carbon::parse($asset->expiry_date)->isPast())
                            <span style="color: red; font-weight: bold;">{{ \Carbon\Carbon::parse($asset->expiry_date)->format('d-M-Y') }} (Expired)</span>
                        @else
                            {{ \Carbon\Carbon::parse($asset->expiry_date)->format('d-M-Y') }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ $asset->estimated_value ? 'Rs. '.$asset->estimated_value : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
