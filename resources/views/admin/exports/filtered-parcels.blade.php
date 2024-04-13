<table>
    <thead>
        <tr style="background-color: red">
            <th>SL</th>
            <th>Created Date</th>
            <th>Parcel ID</th>
            <th>Merchant Name</th>
            <th>Customer Name</th>
            <th>Invoice No</th>
            <th>Customer Phone</th>
            <th>Customer Address</th>
            <th>Selling Price</th>
            <th>COD</th>
            <th>Delivery Charge</th>
            <th>Payable</th>
            <th>Return Charge</th>
            <th>Weight</th>
            <th>Location</th>
            <th>Status</th>
            <th>Target Hub</th>
            <th>Status Updated At</th>
            <th>Iteam Type</th>
            <th>Reason</th>
        </tr>
    </thead>
    <tbody>
        @php
        $total_cash_collection=0;
        $total_charge=0;
        $total_marchant_payable=0;
        @endphp
        @foreach($parcels as $key => $parcel)
        @php
            if($return_charge_type == 'on_demand'):
                if($parcel->location == 'sub_city'):
                    $return_charge = $return_charge_sub_city;
                elseif($parcel->location == 'outside_dhaka'):
                    $return_charge = $return_charge_outside_dhaka;
                else:
                    $return_charge = $return_charge_dhaka;
                endif;
                $delivery_charge = 0;
            else:
                $delivery_charge = $parcel->total_delivery_charge;
                $return_charge = 0;

            endif;
            $total_cash_collection += $parcel->price;
            $total_charge += $delivery_charge;
            $total_marchant_payable += $parcel->payable;
        @endphp
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $parcel->created_at }}</td>
            <td>{{ $parcel->parcel_no }}</td>
            <td>{{ $parcel->merchant->company }}</td>
            <td>{{ $parcel->customer_name }}</td>
            <td>{{ $parcel->customer_invoice_no }}</td>
            <td>{{ $parcel->customer_phone_number }}</td>
            <td>{{ $parcel->customer_address }}</td>
            <td>{{ $parcel->selling_price }}</td>
            <td>{{ $parcel->price }}</td>
            <td>{{ $delivery_charge }}</td>
            <td>{{ $parcel->payable }}</td>
            <td>{{ ($parcel->status =='returned-to-merchant') ? $return_charge : 0 }}</td>
            <td>{{ $parcel->weight }}</td>
            <td>{{ $parcel->location }}</td>
            <td>{{ $parcel->status }}</td>
            <td>{{ ($parcel->hub_id) ? $parcel->hub->name : '' }}</td>
            <td>{{ ($parcel->date)? $parcel->date : ''  }}</td>
            <td>{{ __('parcel') }}</td>
            <td>{{  ( $parcel->status == 'returned-to-merchant') ?  $parcel->returnEvent->cancel_note : '' }}</td>
        </tr>
        @endforeach
        <tr>
            <td>Total Items</td>
            <td>{{ $parcels->count() }}</td>
        </tr>
        <tr>
            <td>Total Cash Collection</td>
            <td>{{ sprintf("%.2f", $total_cash_collection) }}</td>
        </tr>
        <tr>
            <td>Total Delivery Charge </td>
            <td>{{ sprintf("%.2f", $total_charge) }}</td>
        </tr>

        <tr>
            <td>Total Merchant Payble</td>
            <td>{{ sprintf("%.2f", $total_marchant_payable) }}</td>
        </tr>
    </tbody>
</table>
