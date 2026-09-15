<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Relatives Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        h3 { text-align: center; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h3>Employee Relatives Report</h3>
    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>CODE</th>
                <th>EMPLOYEE NAME</th>
                <th>RELATIONSHIP</th>
                <th>RELATIVE NAME</th>
                <th>DATE OF BIRTH</th>
                <th>DEPENDENT</th>
                <th>PHONE</th>
                <th>E-MAIL</th>
                <th>NOTES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatives as $index => $relative)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($relative->employee)->employee_code ?? '-' }}</td>
                    <td>{{ optional($relative->employee)->first_name }} {{ optional($relative->employee)->last_name }}</td>
                    <td>{{ $relative->relation ?? '-' }}</td>
                    <td>{{ $relative->name ?? '-' }}</td>
                    <td>{{ $relative->dob ? \Carbon\Carbon::parse($relative->dob)->format('d M Y') : '-' }}</td>
                    <td>{{ $relative->is_dependent ? 'Yes' : 'No' }}</td>
                    <td>{{ $relative->phone ?? '-' }}</td>
                    <td>{{ $relative->email ?? '-' }}</td>
                    <td>{{ $relative->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
