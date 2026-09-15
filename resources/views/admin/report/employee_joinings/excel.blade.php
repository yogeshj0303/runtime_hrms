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
            <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">Employee Joinings Report</th>
        </tr>
        <tr>
            <th>SN</th>
            <th>E-CODE</th>
            <th>EMPLOYEE</th>
            <th>JOINING</th>
            <th>CONFIRMATION</th>
            <th>LOCATION</th>
            <th>DEPARTMENT</th>
            <th>DESIGNATION</th>
            <th>YEARLY CTC</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($joinings) && $joinings->count() > 0)
            @foreach($joinings as $index => $joining)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $joining->employee_code }}</td>
                <td>{{ $joining->first_name }} {{ $joining->last_name }}</td>
                <td>{{ $joining->joining_date ? $joining->joining_date->format('d M, Y') : '-' }}</td>
                <td>{{ $joining->profile && $joining->profile->confirmation_date ? \Carbon\Carbon::parse($joining->profile->confirmation_date)->format('d M, Y') : '-' }}</td>
                <td>
                    @php $wp = $joining->workProfiles->where('is_current', true)->first(); @endphp
                    {{ $wp && $wp->location ? $wp->location->name : '-' }}
                </td>
                <td>{{ $wp && $wp->department ? $wp->department->name : '-' }}</td>
                <td>{{ $wp && $wp->designation ? $wp->designation->name : '-' }}</td>
                <td>{{ $joining->salary ? number_format($joining->salary, 2) : '-' }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" style="text-align: center;">No joinings found for the selected filters.</td>
            </tr>
        @endif
    </tbody>
</table>
