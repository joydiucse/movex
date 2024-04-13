<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="description"
          content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('admin/')}}/images/favicon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('admin/')}}/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('admin/')}}/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('admin/')}}/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/')}}/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('admin/')}}/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('admin/')}}/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('admin/')}}/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('admin/')}}/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/')}}/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="{{ asset('admin/')}}/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/')}}/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('admin/')}}/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/')}}/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('admin/')}}/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('admin/')}}/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Page Title  -->
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin/')}}/css/dashlite.css?ver=2.3.0">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/custom.css">

    <title>{{__('Payment').' '.__('invoice').' '.__('print')}} | {{__('app_name')}}</title>
    <style>
        body{
            background-color: #ffffff !important;
        }
        @page  {
            margin: 25mm;
        }
    </style>
</head>

<body class="nk-body npc-default has-sidebar">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main print">
        <div class="nk-content pt-0">
            <div class="nk-content-inner">
                <div class="nk-block ">
                        <div class="col-12 mt-5">
                            <div class="row g-gs">
                                <div class="col-xxl-12 col-sm-12 col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card assign-delivery border-bottom m-0">
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
                                                    <div class="greenx-details d-block">
                                                        <h3 class="page-title">{{__('merchant')}}:</h3>
                                                        <h6>{{ $withdraw->merchant->company }}</h6>
                                                        {{ $withdraw->merchant->address }}<br>
                                                        {{ $withdraw->merchant->phone_number }}
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
                                                        <td>{{ __($merchant_account->details) }}</td>
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
                                <div class="col-12 mt-5">
                                        <center>
                                            <h5 class="card-title mb-3">{{ __('processed_from_account_details') }}</h5>
                                            <p>{{ __('account_info_from_which_this_transaction_processed') }}</p>
                                            <hr width="50%">
                                        </center>

                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th scope="row">{{ __('request_created_by') }}</th>
                                                <td>{{ __($withdraw->companyAccount->user->first_name.' '.$withdraw->companyAccount->user->last_name.' ') }}({{ __($withdraw->companyAccount->user->user_type) }})</td>
                                            </tr>
                                            @isset($withdraw->companyAccount->account)
                                                <tr>
                                                    <th scope="row">{{ __('payment_method') }}</th>
                                                    <td>{{ __($withdraw->companyAccount->account->method) }}</td>
                                                </tr>
                                                @if($withdraw->companyAccount->account->method != 'cash')
                                                    <tr>
                                                        <th scope="row">{{ __('account_holder_name') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->account_holder_name) }}</td>
                                                    </tr>
                                                @endif
                                                @if($withdraw->companyAccount->account->method == 'bank')

                                                    <tr>
                                                        <th scope="row">{{ __('bank_name') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->bank_name) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('branch') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->bank_branch) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('account_no') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->account_no) }}</td>
                                                    </tr>
                                                @elseif($withdraw->companyAccount->account->method == 'cash')

                                                    <tr>
                                                        <th scope="row">{{ __('account_holder_name') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->user->first_name.' '.$withdraw->companyAccount->account->user->last_name) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('email') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->user->email) }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <th scope="row">{{ __('account_type') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->account->type) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('account_number') }}</th>
                                                        <td>{{ $withdraw->companyAccount->account->number }}</td>
                                                    </tr>
                                                @endif

                                                @if($withdraw->companyAccount->transaction_id != '')
                                                    <tr>
                                                        <th scope="row">{{ __('transaction_id') }}</th>
                                                        <td>{{ __($withdraw->companyAccount->transaction_id) }}</td>
                                                    </tr>
                                                @endif
                                            @endisset
                                            </tbody>
                                        </table>
                                    </div>
                            </div>

                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- main @e -->
<!-- app-root @e -->
<!-- JavaScript -->
@include('partials.footer-assets')
<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>

</body>

</html>

