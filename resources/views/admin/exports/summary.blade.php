<table>
    <thead>
    <tr>
        <th colspan="4">Parcel Statistics</th>
    </tr>
    <tr>

    </tr>
    <tr>
        <th>#</th>
        <th>Title</th>
        <th>pcs</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @php
      $key = 0
    @endphp
    @foreach($data as $event => $count)
        <tr>
            <td>{{ $key+=1 }}</td>
            <td>{{ $event }}</td>
            <td>{{ $count  }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>


<table>
    <thead>
    <tr>
        <th colspan="4">Payment Information</th>
    </tr>
    <tr>

    </tr>
    <tr>
        <th>#</th>
        <th>Title</th>
        <th>Amount (tk)</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>Total Cash Collection(COD)</td>
        <td>{{ number_format($profits['total_payable_to_merchant'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Cash Paid To MoveX</td>
        <td>{{ number_format($profits['total_paid_by_merchant'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Payment Completed</td>
        <td>{{ number_format($profits['total_paid_to_merchant'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>4</td>
        <td>Return Charge</td>
        <td>{{ number_format($profits['total_parcel_return_charge'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>5</td>
        <td>Partial Parcel Return Charge</td>
        <td>{{ number_format($profits['total_partially_return_charge'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>6</td>
        <td>Partial Delivery Charge</td>
        <td>{{ number_format($profits['total_partial_delivery_charge_vat'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>7</td>
        <td>Delivery Charge</td>
        <td>{{ number_format($profits['total_delivery_charge_vat'], 2)}}</td>
        <td></td>
    </tr>

    <tr>
        <td>8</td>
        <td>Total Charges</td>
        <td>{{ number_format($profits['total_charge'], 2)}}</td>
        <td></td>
    </tr>

    <tr>
        <td>9</td>
        <td>Payment Pending</td>
        <td>{{ number_format($profits['pending_payments'], 2)}}</td>
        <td></td>
    </tr>
    <tr>
        <td>10</td>
        <td>Available Wallet Balance</td>
        @if($profits['current_payable'] < 0)
            <td>0</td>
        @else
            <td>{{ number_format($profits['available_balance'], 2)}}</td>
        @endif
        <td></td>
    </tr>
    <tr>
        <td>11</td>
        <td>Payable</td>
        @if($profits['current_payable'] < 0)
            <td>0</td>
        @else
            <td>{{ number_format($profits['current_payable'], 2)}}</td>
        @endif
        <td></td>
    </tr>
    <tr>
        <td>12</td>
        <td>Payable To MoveX</td>
        @if($profits['current_payable'] > 0)
            <td>0</td>
        @else
            <td>{{ number_format($profits['current_payable'] * -1, 2)}} </td>
        @endif
        <td></td>
    </tr>
    </tbody>
</table>
