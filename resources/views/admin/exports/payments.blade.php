<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Payment ID</th>
        <th>Payment Date</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Status</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payments as $key => $payment)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $payment->withdraw_id }}</td>
            <td>{{ date('M d, Y h:i a', strtotime($payment->updated_at)) }}</td>
            <td>{{ $payment->amount }}</td>
            <td>@if($merchant_id =='1784') Cash @else {{$payment->withdraw_to}}@endif</td>
            <td>{{ $payment->status }}</td>
            <td>SMS Confirmed</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3">Total</td>
        <td>{{ $payments->sum('amount') }}</td>
        <td colspan="1"></td>
    </tr>
    </tbody>
</table>
