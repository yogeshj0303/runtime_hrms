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
        <td colspan="9" class="title">Employee Addresses Report</td>
    </tr>
    <tr>
        <td colspan="9">Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
    </tr>
    <tr>
        <th>SN</th>
        <th>EMPLOYEE</th>
        <th>ADDRESS TYPE</th>
        <th>ADDRESS LINE 1</th>
        <th>ADDRESS LINE 2</th>
        <th>CITY</th>
        <th>PINCODE</th>
        <th>STATE</th>
        <th>COUNTRY</th>
    </tr>
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
</table>
