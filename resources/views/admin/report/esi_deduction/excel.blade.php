<table border="1">
    <thead>
        <tr>
            <th colspan="10" style="text-align: center; font-weight: bold; font-size: 16px;">
                ESI Deduction Summary - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
            </th>
        </tr>
        <tr>
            <th>SN</th>
            <th>EMPLOYEE CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>IP NUMBER</th>
            <th>DAYS</th>
            <th>WAGES</th>
            <th>EMPLOYEE ESI</th>
            <th>EMPLOYER ESI</th>
            <th>REASON FOR 0 DAYS</th>
            <th>LAST WORKING DATE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['employee']->employee_code }}</td>
                <td>{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                <td>{{ $item['esi_number'] ?? '-' }}</td>
                <td>{{ $item['days'] }}</td>
                <td>{{ $item['gross_salary'] }}</td>
                <td>{{ $item['employee_esi'] }}</td>
                <td>{{ $item['employer_esi'] }}</td>
                <td>{{ $item['reason_for_0_days'] }}</td>
                <td>{{ $item['employee']->exit_date ? \Carbon\Carbon::parse($item['employee']->exit_date)->format('d-m-Y') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
