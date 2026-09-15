<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000000;
        padding: 5px;
    }
    th {
        background-color: #133C5A;
        color: #ffffff;
        font-weight: bold;
    }
</style>

<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">Employee Exits Report</th>
        </tr>
        <tr>
            <th>SN</th>
            <th>E-CODE</th>
            <th>EMPLOYEE</th>
            <th>LOCATION</th>
            <th>DEPARTMENT</th>
            <th>DESIGNATION</th>
            <th>JOINING</th>
            <th>EXIT</th>
            <th>REASON OF EXIT</th>
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
                <td>{{ $exit->exit_date->format('d M, Y') }}</td>
                <td>{{ $exit->exitReason ? $exit->exitReason->reason_name : '-' }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" style="text-align: center;">No exits found for the selected filters.</td>
            </tr>
        @endif
    </tbody>
</table>
