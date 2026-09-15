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
            <th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold;">Employee Events Report</th>
        </tr>
        <tr>
            <th>DATE</th>
            <th>EVENT TYPE</th>
            <th>EMPLOYEE</th>
            <th>LOCATION</th>
            <th>DEPARTMENT</th>
            <th>DESIGNATION</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($events) && $events->count() > 0)
            @foreach($events as $event)
            <tr>
                <td>{{ $event['date'] }}</td>
                <td>{{ $event['event_type'] }}</td>
                <td>{{ $event['employee'] }}</td>
                <td>{{ $event['location'] }}</td>
                <td>{{ $event['department'] }}</td>
                <td>{{ $event['designation'] }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" style="text-align: center;">No events found for the selected filters.</td>
            </tr>
        @endif
    </tbody>
</table>
