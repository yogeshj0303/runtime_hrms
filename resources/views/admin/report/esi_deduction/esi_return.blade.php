<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 16px;">
                ESI Return Format - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
            </th>
        </tr>
        <tr>
            <th>IP Number</th>
            <th>IP Name</th>
            <th>No of Days for which wages paid/payable during the month</th>
            <th>Total Monthly Wages</th>
            <th>Reason for Zero working days (if any)</th>
            <th>Last Working Day</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $item)
            <tr>
                <td>{{ $item['esi_number'] ?? '-' }}</td>
                <td>{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                <td>{{ $item['days'] }}</td>
                <td>{{ $item['gross_salary'] }}</td>
                <td>{{ $item['reason_for_0_days'] }}</td>
                <td>{{ $item['employee']->exit_date ? \Carbon\Carbon::parse($item['employee']->exit_date)->format('d-m-Y') : '-' }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
