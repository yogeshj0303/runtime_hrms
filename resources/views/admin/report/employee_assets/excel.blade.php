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
        background-color: #2F75B5;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
    }
    .title {
        font-size: 18px;
        font-weight: bold;
        text-align: left;
        color: #2F75B5;
    }
</style>

<table>
    <tr>
        <td colspan="8" class="title">Employee Assets Report</td>
    </tr>
    <tr>
        <td colspan="8">Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <th>SN</th>
        <th>EMPLOYEE</th>
        <th>ASSET TYPE</th>
        <th>ASSET NAME</th>
        <th>SERIAL NO</th>
        <th>ISSUE DATE</th>
        <th>EXPIRY DATE</th>
        <th>EST VALUE</th>
    </tr>
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
                    {{ \Carbon\Carbon::parse($asset->expiry_date)->format('d-M-Y') }} (Expired)
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
</table>
