@extends('master')

@section('title')
    {{__('payment_invoice')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('payment_invoice')}}</h3>
                                <div class="nk-block-des text-soft">

                                </div>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block ">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card assign-delivery border-bottom">
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <div class="right-content d-block">
                                                                <img src="{{ asset('admin/images/logo-green.png') }}" alt="logo" class="img-fluid image">
                                                                <div class="greenx-details d-block mt-2">
                                                                    {{ __('address_1st') }} <br>
                                                                    {{ __('address_2nd') }} <br>
                                                                    {{ __('app_phone_number') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="invoice d-block">
                                                                <center>
                                                                    <h4 >{{ __('INVOICE') }}</h4>
                                                                    <p>{{ __('id') }}# {{ $withdraw->withdraw_id }}</p>
                                                                    {{ __('date').': '.date('d.m.Y') }}
                                                                </center>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="greenx-details d-block">
                                                                    <h3 class="page-title">{{__('merchant')}}:</h3>
                                                                    <h6>{{ $withdraw->merchant->company }}</h6>
                                                                    {{ $withdraw->merchant->address }}<br>
                                                                    {{ $withdraw->merchant->phone_number }}
                                                                </div>
                                                                <div class="card-title">
                                                                    <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.invoice.print',$withdraw->id) : route('merchant.staff.invoice.print',$withdraw->id) }}" target="_blank"
                                                                       class="btn btn-icon btn-warning"> <em class="icon ni ni-printer"></em> </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row" colspan="2"><span class="center"> {{ __('payment_details') }} </span></th>
                                                    </tr>
                                                    @php
                                                        $total_price = 0;
                                                        $total_cod_charge = 0;
                                                        $total_vat = 0;
                                                        $total_charge = 0;
                                                        $total_fragile_charge = 0;
                                                        $total_packaging_charge = 0;
                                                        $total_delivery_charge_with_vat = 0;
                                                        $total_delivery_charge_without_vat = 0;
                                                        if(!blank($withdraw->parcels)):
                                                            foreach($withdraw->parcels as $key=>$parcel):
                                                                $total_price            += $parcel->price;
                                                                $total_cod_charge       += floor($parcel->price / 100 * $parcel->cod_charge);
                                                                $total_charge           += $parcel->charge;

                                                                $total_fragile_charge   += $parcel->fragile_charge;
                                                                $total_packaging_charge += $parcel->packaging_charge;
                                                            endforeach;

                                                        $total_delivery_charge_with_vat = $withdraw->parcels->sum('total_delivery_charge');

                                                        $total_delivery_charge_without_vat = $total_cod_charge + $total_fragile_charge + $total_packaging_charge + $total_charge;
                                                        $total_vat = $total_delivery_charge_with_vat - $total_delivery_charge_without_vat;
                                                        endif
                                                    @endphp
                                                    @if(!blank($withdraw->parcels))
                                                        <tr>
                                                            <th scope="row">{{ __('total_cash_collection') }}</th>
                                                            <td>{{ number_format($total_price,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('delivery_charge') }}</th>
                                                            <td> {{ number_format($total_charge, 2) }}</td>
                                                        </tr>
                                                        @if($total_cod_charge > 0)
                                                            <tr>
                                                                <th scope="row">{{ __('cod_charge') }}</th>
                                                                <td>{{ number_format(floor($total_cod_charge), 2) }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($total_fragile_charge > 0)
                                                            <tr>
                                                                <th scope="row">{{ __('fragile_liquid_charge') }}</th>
                                                                <td>{{ number_format($total_fragile_charge, 2) }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($total_packaging_charge > 0)
                                                            <tr>
                                                                <th scope="row">{{ __('packaging_charge') }}</th>
                                                                <td>{{ number_format($total_packaging_charge, 2) }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($total_vat > 0)
                                                            <tr>
                                                                <th scope="row">{{ __('vat') }}</th>
                                                                <td>{{ number_format(floor($total_vat), 2) }}</td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <th scope="row">{{ __('total_charge') }}</th>
                                                            <td>{{ number_format($total_delivery_charge_with_vat, 2)  }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('total_payable') }}</th>
                                                            <td>{{ number_format($withdraw->parcels->sum('payable'), 2) }}</td>
                                                        </tr>
                                                    @endif
                                                    @if(!blank($withdraw->merchantAccounts))

                                                        <tr>
                                                            <th scope="row">{{ __('previous_balance') }}
                                                            @php
                                                                $income = $withdraw->merchantAccounts->where('type', 'income')->sum('amount');
                                                                $expense = $withdraw->merchantAccounts->where('type', 'expense')->sum('amount');
                                                            @endphp
                                                            <td>{{ number_format($income - $expense, 2) }}</td>
                                                        </tr>
                                                    @endif
                                                    @if(!blank($withdraw->paidReverseParcels))
                                                        <tr>
                                                            <th scope="row">{{ __('paid_parcels_reverse_adjustment_amount') }}</th>
                                                            <td>{{ number_format($withdraw->paidReverseParcels->sum('amount'), 2) }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th scope="row">{{ __('withdraw_amount') }}</th>
                                                        <td>{{ number_format($withdraw->amount, 2) }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-6">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row" colspan="2"><span class="center"> {{ __('withdraw_details') }} </span></th>
                                                    </tr>
                                                    @php
                                                        $account_details = json_decode($withdraw->account_details);
                                                    @endphp
                                                    @if($withdraw->withdraw_to == 'bank')
                                                        <tr>
                                                            <th scope="row">{{ __('payment_method') }}</th>
                                                            <td>{{ __('bank') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('bank_name') }}</th>
                                                            <td>{{ @$account_details[0] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('branch') }}</th>
                                                            <td>{{ @$account_details[1] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('account_holder') }}</th>
                                                            <td>{{ @$account_details[2] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">{{ __('account_no') }}</th>
                                                            <td>{{ @$account_details[3] }}</td>
                                                        </tr>
                                                        @if(@$account_details[4] != '')
                                                            <tr>
                                                                <th scope="row">{{ __('routing_no') }}</th>
                                                                <td>{{ @$account_details[4] }}</td>
                                                            </tr>
                                                        @endif
                                                    @else
                                                        <tr>
                                                            <th scope="row">{{ __('payment_method') }}</th>
                                                            <td>{{ __(@$withdraw->withdraw_to) }}</td>
                                                        </tr>
                                                        @if(@$withdraw->withdraw_to == 'bKash' || @$withdraw->withdraw_to == 'nogod' || @$withdraw->withdraw_to == 'rocket')
                                                            <tr>
                                                                <th scope="row">{{ __('account_type') }}</th>
                                                                <td>{{ __(@$account_details[2]) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">{{ __('account_number') }}</th>
                                                                <td>{{ @$account_details[1] }}</td>
                                                            </tr>

                                                        @endif
                                                    @endif
                                                    </tbody>
                                                </table>
                                                @if($withdraw->note != '' or $withdraw->note != null)
                                                    <div class="note mt-2">
                                                        <textarea class="form-control">{{ __('note') }}: {{ $withdraw->note }}</textarea>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if(!blank($withdraw->parcels))
                                        <div class="col-12 mt-5">
                                            <center>
                                                <h5 class="card-title mb-3">{{ __('cleared_payment_parcels') }}</h5>
                                                <p>{{ __('payment_invoice_notice') }}</p>
                                                <hr width="50%">
                                            </center>

                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">{{ __('parcel_id') }}</th>
                                                    <th scope="col">{{ __('invoice_no') }}</th>
                                                    <th scope="col">{{ __('customer_name') }}</th>
                                                    <th scope="col">{{ __('cod') }}</th>
                                                    <th scope="col">{{ __('delivery_charge') }}</th>
                                                    <th scope="col">{{ __('cod_charge') }}</th>
                                                    <th scope="col">{{ __('fragile_liquid_charge') }}</th>
                                                    <th scope="col">{{ __('packaging_charge') }}</th>
                                                    <th scope="col">{{ __('vat') }}</th>
                                                    <th scope="col">{{ __('total_charge') }}</th>
                                                    <th scope="col">{{ __('payable') }}</th>
                                                </tr>
                                                @foreach($withdraw->parcels as $key=>$parcel)

                                                    @php
                                                        $total_vat             = $parcel->total_delivery_charge / 100 * $parcel->vat;
                                                        $cod_charge            = $parcel->price / 100 * $parcel->cod_charge
                                                    @endphp
                                                    <tr>
                                                        <th scope="row">{{ $key+1 }}</th>
                                                        <td>{{ $parcel->parcel_no }}</td>
                                                        <td>{{ $parcel->customer_invoice_no }}</td>
                                                        <td>{{ $parcel->customer_name }}</td>
                                                        <td>{{ number_format($parcel->price, 2) }}</td>
                                                        <td>{{ number_format($parcel->charge, 2) }}</td>
                                                        <td>{{ number_format(floor($cod_charge), 2) }}</td>
                                                        <td>{{ number_format($parcel->fragile_charge, 2) }}</td>
                                                        <td>{{ number_format($parcel->packaging_charge, 2) }}</td>
                                                        <td>{{ number_format(floor($total_vat), 2) }}</td>
                                                        <td>{{ number_format($parcel->total_delivery_charge, 2) }}</td>
                                                        <td>{{ number_format($parcel->payable, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th scope="row" colspan="11">{{ __('total_payable') }}</th>
                                                    <td>{{ number_format($withdraw->parcels->sum('payable'), 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if(!blank($withdraw->paidReverseParcels))
                                        <div class="col-12 mt-5">
                                            <center>
                                                <h5 class="card-title mb-3">{{ __('delivery_reversed_parcels') }}</h5>
                                                <p>{{ __('paid_parcels_invoice_notice') }}</p>
                                                <hr width="50%">
                                            </center>

                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">{{ __('parcel_id') }}</th>
                                                    <th scope="col">{{ __('paid_amount') }}</th>
                                                </tr>
                                                @foreach($withdraw->paidReverseParcels as $key=>$withdrawParcel)
                                                    <tr>
                                                        <th scope="row">{{ $key+1 }}</th>
                                                        <td>{{ $withdrawParcel->parcel->parcel_no }}</td>
                                                        <td>{{ number_format($withdrawParcel->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th scope="row" colspan="2">{{ __('total_paid') }}</th>
                                                    <td>{{ number_format($withdraw->paidReverseParcels->sum('amount'), 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if(!blank($withdraw->merchantAccounts))
                                        <div class="col-12 mt-5">
                                            <center>
                                                <h5 class="card-title mb-3">{{ __('previous_balance_calculations') }}</h5>
                                                <p>{{ __('previous_balance_calculations_notice') }}</p>
                                                <hr width="50%">
                                            </center>

                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">{{ __('source') }}</th>
                                                    <th scope="col">{{ __('details') }}</th>
                                                    <th scope="col">{{ __('date') }}</th>
                                                    <th scope="col">{{ __('credit').'/'.__('debit') }}</th>
                                                    <th scope="col">{{ __('amount') }}</th>
                                                </tr>
                                                @foreach($withdraw->merchantAccounts as $key=>$merchant_account)
                                                    <tr>
                                                        <th scope="row">{{ $key+1 }}</th>
                                                        <td>{{ __($merchant_account->source) }}</td>
                                                        <td>{{@$merchant_account->parcel->parcel_no}} <br>{{ __($merchant_account->details) }}</td>
                                                        <td>{{$merchant_account->created_at != ""? date('M d, Y h:i a', strtotime($merchant_account->created_at)):''}}</td>
                                                        <td>{{ $merchant_account->type == 'income' ? __('credit') : __('debit') }}</td>
                                                        <td {{ $merchant_account->type == 'income' ? '' : 'class=text-danger' }}>{{ number_format($merchant_account->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th scope="row" colspan="5">{{ __('previous_balance') }}</th>
                                                    <td>{{ number_format($income - $expense, 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
