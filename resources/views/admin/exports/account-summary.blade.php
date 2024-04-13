<table>
    <tr>
        <th>{{__('account_summary')}}</th>
    </tr>
    <tr>
        <td>{{__('Daily Profit/Loss')}}</td>
    </tr>
    <tr>
            {{-- <td>{{$start_date != ""? date('M d, Y', strtotime($start_date)):''}} - {{$end_date != ""? date('M d, Y', strtotime($end_date)):''}}<</td> --}}
            <td>{{ date('M d, Y', strtotime($start_date)) }} - {{ date('M d, Y', strtotime($end_date)) }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th> {{__('date')}} </th>
            <th> {{__('delivered')}} </th>
            <th> {{__('returned')}} </th>
            <th> {{__('cash_collection') }} </th>
            <th> {{__('cash_from_merchant')}} </th>
            <th> {{__('charges')}} </th>
            <th> {{__('return_charge')}} </th>
            <th> {{__('total_charge')}} </th>
            <th> {{__('merchant_payable')}} </th>
            <th> {{__('rider_commission')}} </th>
            <th> {{__('cash_received_from_rider')}} </th>
            <th> {{__('payment_request')}} </th>
            <th> {{__('payment_processed')}} </th>
            <th> {{__('expense')}} </th>
            <th> {{__('balance')}} </th>
            <th> {{__('profit/loss')}} </th>

        </tr>
    </thead>
       <tbody>
        @php
            $total_parcel = 0;
            $returned = 0;
            $total_cash_collection = 0;
            $total_cash_from_merchant = 0;
            $total_charges = 0;
            $return_charge = 0;
            $total_deliver_return_charge=0;
            $total_merchant_payable = 0;
            $total_rider_commisssion = 0;
            $total_cash_received_from_rider = 0;
            $total_payment_request = 0;
            $total_payment_processed = 0;
            $total_expense = 0;
            $total_balance = 0;
            $total_profit_loss = 0;
        @endphp
        @for($i=0; $i< count($summary_info['date']); $i++)
            <tr>
                @foreach($summary_info as $key => $summary)
                    @php
                        if($key == "parcel"):
                            $total_parcel +=   $summary_info[$key][$i];
                        elseif($key == "returned"):
                            $returned +=   $summary_info[$key][$i];
                        elseif($key == "cash_collection"):
                            $total_cash_collection +=   $summary_info[$key][$i];
                        elseif($key == "cash_from_merchant"):
                            $total_cash_from_merchant +=   $summary_info[$key][$i];
                        elseif($key == "charge"):
                            $total_charges +=   $summary_info[$key][$i];
                        elseif($key == "return_charge"):
                            $return_charge +=   $summary_info[$key][$i];
                        elseif($key == "payable"):
                            $total_merchant_payable +=   $summary_info[$key][$i];
                        elseif($key == "total_charge"):
                            $total_deliver_return_charge +=   $summary_info[$key][$i];
                        elseif($key == "rider_commission"):
                            $total_rider_commisssion +=   $summary_info[$key][$i];
                        elseif($key == "cash_receive_from_rider"):
                            $total_cash_received_from_rider +=   $summary_info[$key][$i];
                        elseif($key == "payment_request"):
                            $total_payment_request +=   $summary_info[$key][$i];
                        elseif($key == "payment_processed"):
                            $total_payment_processed +=   $summary_info[$key][$i];
                        elseif($key == "expense"):
                            $total_expense +=   $summary_info[$key][$i];
                        elseif($key == "balance"):
                            $total_balance +=   $summary_info[$key][$i];
                        elseif($key == "profit"):
                            $total_profit_loss +=   $summary_info[$key][$i];
                        endif;
                    @endphp



                    <td>{{  $summary_info[$key][$i] }} </td>
                @endforeach
            </tr>
        @endfor
        <tr>

            <th>Total</th>
            <th>{{ $total_parcel }}</th>
            <th>{{ $returned }}</th>
            <th>{{ sprintf("%.2f", $total_cash_collection) }}</th>
            <th>{{ sprintf("%.2f", $total_cash_from_merchant) }}</th>
            <th>{{ sprintf("%.2f", $total_charges) }}</th>
            <th>{{ sprintf("%.2f", $return_charge) }}</th>
            <th>{{ sprintf("%.2f", $total_deliver_return_charge) }}</th>
            <th>{{ sprintf("%.2f",  $total_merchant_payable) }}</th>
            <th>{{ sprintf("%.2f", $total_rider_commisssion) }}</th>
            <th>{{ sprintf("%.2f", $total_cash_received_from_rider) }}</th>
            <th>{{ $total_payment_request }}</th>
            <th>{{ sprintf("%.2f", $total_payment_processed) }}</th>
            <th>{{ sprintf("%.2f", $total_expense) }}</th>
            <th>{{ sprintf("%.2f", $total_balance) }}</th>
            <th>{{ sprintf("%.2f", $total_profit_loss) }}</th>

        </tr>

       </tbody>
</table>
