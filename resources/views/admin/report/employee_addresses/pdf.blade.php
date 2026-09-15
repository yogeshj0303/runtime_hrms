<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Addresses Report</title>
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
        <h2>Employee Addresses</h2>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">SN</th>
                <th>EMPLOYEE</th>
                <th>ADDRESS TYPE</th>
                <th>ADDRESS LINE 1</th>
                <th>ADDRESS LINE 2</th>
                <th>CITY</th>
                <th>PINCODE</th>
                <th>STATE</th>
                <th>COUNTRY</th>
            </tr>
        </thead>
        <tbody>
            @foreach($addresses as $index => $address)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ optional($address->employee)->employee_code }} - {{ optional($address->employee)->first_name }} {{ optional($address->employee)->last_name }}</td>
                <td style="text-transform: capitalize;">{{ $address->type }}</td>
                <td>{{ $address->address1 }}</td>
                <td>{{ $address->address2 }}</td>
                <td>{{ $address->city }}</td>
                <td>{{ $address->zipcode }}</td>
                <td>{{ $address->state }}</td>
                <td>{{ $address->country }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
