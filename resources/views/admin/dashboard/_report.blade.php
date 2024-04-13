
    <link rel="stylesheet" href="{{ asset('admin/')}}/css/dashlite.css?ver=2.3.0">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/skins/theme-green.css?ver=2.3.0">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/custom.css">

<div class="row g-gs">
    @if(hasPermission('withdraw_read'))
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">{{ __('total_withdraw_by_merchant') }}</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount" id="merchant_total_withdraw"> {{ number_format($data['merchant_total_withdraw'], 2) }} {{ __('tk') }}</div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                </div><!-- .nk-ecwg -->
            </div><!-- .card -->
        </div><!-- .col -->
    @endif
    @if(hasPermission('dashboard_statistics_read'))
        <div class="col-xxl-{{ hasPermission('withdraw_read') ? 3 : 4 }} col-sm-6">
            <div class="card">
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">{{ __('total_cod') }}</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount"> {{ $counts['total_cod'] }}</div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                </div><!-- .nk-ecwg -->
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xxl-{{ hasPermission('withdraw_read') ? 3 : 4 }} col-sm-6">
            <div class="card">
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">{{ __('total_parcels') }}</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $counts['parcels_count'] }}</div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                </div><!-- .nk-ecwg -->
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xxl-{{ hasPermission('withdraw_read') ? 3 : 4 }} col-sm-6">
            <div class="card">
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">{{ __('total_delivered') }}</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $data['totalParcelDelivered'] }}</div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                </div><!-- .nk-ecwg -->
            </div><!-- .card -->
        </div><!-- .col -->

        <div class="col-xxl-6">
            <div class="card card-full">
                <div class="nk-ecwg nk-ecwg8 h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h6 class="title">{{ __('parcel_statistics') }}</h6>
                            </div>
                        </div>
                        <ul class="nk-ecwg8-legends">
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#6576ff"></span>
                                    <span>{{ __('total_parcels') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#24bc7e"></span>
                                    <span>{{ __('delivered') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#3bb143"></span>
                                    <span>{{ __('partially_delivered') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#13c9f2"></span>
                                    <span>{{ __('processing') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#eb6459"></span>
                                    <span>{{ __('cancelled') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#FF0000"></span>
                                    <span>{{ __('deleted') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#f4bd0e"></span>
                                    <span>{{ __('returned') }}</span>
                                </div>
                            </li>
                        </ul>
                        <div class="nk-ecwg8-ck">
                            <canvas class="ecommerce-line-chart-s4" id="salesStatistics"></canvas>
                        </div>

                    </div><!-- .card-inner -->
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xxl-3 col-md-6">
            <div class="card card-full overflow-hidden">
                <div class="nk-ecwg nk-ecwg7 h-100">
                    <div class="card-inner flex-grow-1">
                        <div class="card-title-group mb-4">
                            <div class="card-title">
                                <h6 class="title">{{ __('parcel_statistics') }}</h6>
                            </div>
                        </div>
                        <div class="nk-ecwg7-ck">
                            <canvas class="ecommerce-doughnut-s1" id="orderStatistics"></canvas>
                        </div>
                        <ul class="nk-ecwg7-legends">
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#6576ff"></span>
                                    <span>{{ __('total_parcels') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#24bc7e"></span>
                                    <span>{{ __('delivered') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#3bb143"></span>
                                    <span>{{ __('partially_delivered') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#13c9f2"></span>
                                    <span>{{ __('processing') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#eb6459"></span>
                                    <span>{{ __('cancelled') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#FF0000"></span>
                                    <span>{{ __('deleted') }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#f4bd0e"></span>
                                    <span>{{ __('returned') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div><!-- .card-inner -->
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xxl-3 col-md-6">
            <div class="card h-100">
                <div class="card-inner">
                    <ul class="nk-store-statistics">
                         <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_parcels') }}</div>
                                <div class="count">{{ $counts['parcels_count'] }}</div>
                            </div>
                            <em class="icon ni ni-box bg-purple-dim"></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title"> {{__('total') }}  {{__('delivered') }} </div>
                                <div class="count">{{ $counts['delivery_count'] }}</div>
                            </div>
                            <em class="icon ni ni-box bg-purple-dim"></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_partially_delivered') }}</div>
                                <div class="count">{{ $counts['partial_delivered_count'] }}</div>
                            </div>
                            <em class="icon ni ni-box bg-purple-dim"></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_processing') }}</div>
                                <div class="count">{{ $counts['processing_count'] }}</div>
                            </div>
                            <em class="icon ni ni-check-c bg-info-dim "></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_cancelled') }}</div>
                                <div class="count">{{ $counts['cancelled_count'] }}</div>
                            </div>
                            <em class="icon bg-pink-dim ni ni-box"></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_deleted') }}</div>
                                <div class="count">{{ $counts['deleted_count'] }}</div>
                            </div>
                            <em class="icon bg-red-dim ni ni-cross"></em>
                        </li>
                        <li class="item">
                            <div class="info">
                                <div class="title">{{ __('total_returned') }}</div>
                                <div class="count">{{ $counts['returned_count'] }}</div>
                            </div>
                            <em class="icon ni ni-box bg-primary-dim "></em>
                        </li>
                    </ul>
                </div><!-- .card-inner -->
            </div><!-- .card -->
        </div><!-- .col -->
    @endif

    {{--                            profit report--}}
    @if(hasPermission('profit_summary_report_read'))
            <div class="col-xxl-4 col-md-8 col-lg-6">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">{{ __('profit_info') }}</h6>
                            </div>
                        </div>
                        <ul class="nk-top-products">
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_charge_including_vat')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format(abs($profits['total_charge_vat']), 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_delivery_charge')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_delivery_charge'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_vat')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_vat'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('fragile_liquid_charge')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_fragile_charge'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('packaging_charge')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_packaging_charge'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('total_profit')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_profit'], 2)}}</strong></span></div>
                                    </div><!-- .nk-tb-item -->

                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
            <div class="col-xxl-4 col-md-8 col-lg-6">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">{{ __('payable_to_merchant') }}</h6>
                            </div>
                        </div>
                        <ul class="nk-top-products">
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_payable_to_merchant')}}({{__('cod')}})</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_payable_to_merchant'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_to_merchant')}}({{__('with_pending')}})</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_paid_to_merchant'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_by_merchant')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_paid_by_merchant'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_charge_including_vat')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_charge_vat'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->

                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_payable_to_merchant')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['current_payable'], 2) }}</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('pending_payments')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['pending_payments'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_payable_with_pending')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['current_payable'] + $profits['pending_payments'], 2) }}</strong></span></div>
                                    </div><!-- .nk-tb-item -->

                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
            <div class="col-xxl-4 col-md-8 col-lg-6">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">{{ __('cash_collection_info') }}</h6>
                            </div>
                        </div>
                        <ul class="nk-top-products">
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_cash_on_delivery')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_cash_on_delivery'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_by_delivery_man')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_paid_by_delivery_man'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_to_delivery_charge')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_delivery_charge'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->

                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_due_to_delivery_man')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_cash_on_delivery'] - $profits['total_paid_by_delivery_man'] - $profits['total_delivery_charge'], 2) }}</strong></span></div>
                                    </div><!-- .nk-tb-item -->

                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
            <div class="col-xxl-12 col-md-12 col-lg-12">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">{{ __('bank_cash_info') }}</h6>
                            </div>
                        </div>
                        <ul class="nk-top-products">
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">

                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_by_delivery_man')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_paid_by_delivery_man'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_by_merchant')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_paid_by_merchant'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('pending_payments')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['pending_payments'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_bank_opening_balance')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span> {{ number_format($profits['total_bank_opening_balance'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->


                                    <div class="nk-tb-item text-danger">

                                        <div class="nk-tb-col">
                                            <span>{{__('total_paid_to_merchant')}}({{__('with_pending')}})</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_paid_to_merchant'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item text-danger">

                                        <div class="nk-tb-col">
                                            <span>{{__('expense')}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ number_format($profits['total_expense_from_account'], 2)}}</span>
                                        </div>
                                    </div><!-- .nk-tb-item -->

                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_cash_balance')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_paid_by_delivery_man'] + $profits['total_paid_by_merchant'] + $profits['pending_payments'] + $profits['total_bank_opening_balance'] - $profits['total_paid_to_merchant'] - $profits['total_expense_from_account'], 2) }}</strong></span></div>
                                    </div><!-- .nk-tb-item -->

                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
        @endif
</div><!-- .row -->
    <script id="fa" src="{{ asset('admin/')}}/js/bundle.js?ver=2.3.0"></script>
    <script src="{{ asset('admin/')}}/js/scripts.js?ver=2.3.0"></script>
    <script src="{{ asset('admin/')}}/js/custom.js"></script>
    <script type="text/javascript">
        !(function (NioApp, $) {
            "use strict";

            var salesStatistics = {
                labels : {!! json_encode($data['dates']) !!},
                dataUnit : '',
                lineTension : .4,
                datasets : [{
                    label : "{{ __('total_parcels') }}",
                    color : "#6576ff",
                    dash : 0,
                    background : NioApp.hexRGB('#9d72ff',.15),
                    data: {!! json_encode($data['totalParcel']) !!}
                },{
                    label : "{{ __('cancelled') }}",
                    color : "#eb6459",
                    dash : [3],
                    background : "transparent",
                    data: {!! json_encode($data['cancelled']) !!}
                },{
                    label : "{{ __('deleted') }}",
                    color : "#FF0000",
                    dash : [8],
                    background : "transparent",
                    data: {!! json_encode($data['deleted']) !!}
                },{
                    label : "{{__('delivered')}}",
                    color : "#24bc7e",
                    dash : [5],
                    background : "transparent",
                    data: {!! json_encode($data['delivered']) !!}
                },{
                    label : "{{__('partially_delivered')}}",
                    color : "#3bb143",
                    dash : [5],
                    background : "transparent",
                    data: {!! json_encode($data['partially_delivered']) !!}
                },{
                    label : "{{__('processing')}}",
                    color : "#13c9f2",
                    dash : [7],
                    background : "transparent",
                    data: {!! json_encode($data['processing']) !!}
                },{
                    label : "{{__('returned')}}",
                    color : "#f4bd0e",
                    dash : [10],
                    background : "transparent",
                    data: {!! json_encode($data['returned']) !!}
                }]
            };

            function ecommerceLineS4(selector, set_data){
                var $selector = (selector) ? $(selector) : $('.ecommerce-line-chart-s4');
                $selector.each(function(){
                    var $self = $(this), _self_id = $self.attr('id'), _get_data = (typeof set_data === 'undefined') ? eval(_self_id) : set_data;
                    var selectCanvas = document.getElementById(_self_id).getContext("2d");

                    var chart_data = [];
                    for (var i = 0; i < _get_data.datasets.length; i++) {
                        chart_data.push({
                            label: _get_data.datasets[i].label,
                            tension:_get_data.lineTension,
                            backgroundColor: _get_data.datasets[i].background,
                            borderWidth:2,
                            borderDash:_get_data.datasets[i].dash,
                            borderColor: _get_data.datasets[i].color,
                            pointBorderColor: 'transparent',
                            pointBackgroundColor: 'transparent',
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: _get_data.datasets[i].color,
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 2,
                            pointRadius: 4,
                            pointHitRadius: 4,
                            data: _get_data.datasets[i].data,
                        });
                    }
                    var chart = new Chart(selectCanvas, {
                        type: 'line',
                        data: {
                            labels: _get_data.labels,
                            datasets: chart_data,
                        },
                        options: {
                            legend: {
                                display: (_get_data.legend) ? _get_data.legend : false,
                                rtl: NioApp.State.isRTL,
                                labels: {
                                    boxWidth:12,
                                    padding:20,
                                    fontColor: '#6783b8',
                                }
                            },
                            maintainAspectRatio: false,
                            tooltips: {
                                enabled: true,
                                rtl: NioApp.State.isRTL,
                                callbacks: {
                                    title: function(tooltipItem, data) {
                                        return data['labels'][tooltipItem[0]['index']];
                                    },
                                    label: function(tooltipItem, data) {
                                        return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
                                    }
                                },
                                backgroundColor: '#1c2b46',
                                titleFontSize: 13,
                                titleFontColor: '#fff',
                                titleMarginBottom: 6,
                                bodyFontColor: '#fff',
                                bodyFontSize: 12,
                                bodySpacing:4,
                                yPadding: 10,
                                xPadding: 10,
                                footerMarginTop: 0,
                                displayColors: false
                            },
                            scales: {
                                yAxes: [{
                                    display: true,
                                    stacked: (_get_data.stacked) ? _get_data.stacked : false,
                                    position : NioApp.State.isRTL ? "right" : "left",
                                    ticks: {
                                        beginAtZero:true,
                                        fontSize:11,
                                        fontColor:'#9eaecf',
                                        padding:10,
                                        callback: function(value, index, values) {
                                            return value;
                                        },
                                        min:0,
                                        stepSize:3000
                                    },
                                    gridLines: {
                                        color: NioApp.hexRGB("#526484",.2),
                                        tickMarkLength:0,
                                        zeroLineColor: NioApp.hexRGB("#526484",.2)
                                    },

                                }],
                                xAxes: [{
                                    display: false,
                                    stacked: (_get_data.stacked) ? _get_data.stacked : false,
                                    ticks: {
                                        fontSize:9,
                                        fontColor:'#9eaecf',
                                        source: 'auto',
                                        padding:10,
                                        reverse: NioApp.State.isRTL
                                    },
                                    gridLines: {
                                        color: "transparent",
                                        tickMarkLength: 0,
                                        zeroLineColor: 'transparent',
                                    },
                                }]
                            }
                        }
                    });
                })
            }
            // init chart
            NioApp.coms.docReady.push(function(){ ecommerceLineS4(); });

            var orderStatistics = {
                labels : ["{{ __('delivered') }}","{{ __('partially_delivered') }}", "{{ __('processing') }}", "{{ __('cancelled') }}", "{{ __('deleted') }}", "{{ __('returned') }}", "{{ __('total_parcels') }}"],
                dataUnit : '',
                legend: false,
                datasets : [{
                    borderColor : "#fff",
                    background : ["#24bc7e","#3bb143","#13c9f2","#eb6459","#FF0000","#f4bd0e", "#6576ff"],
                    data: [{!! json_encode($data['totalDelivered']) !!}, {!! json_encode($data['totalPartialDelivered']) !!}, {!! json_encode($data['totalProcessing']) !!}, {!! json_encode($data['totalCancelled']) !!},{!! json_encode($data['totalDeleted']) !!}, {!! json_encode($data['totalReturned']) !!}, {!! json_encode($data['totalParcels']) !!}]
                }]
            };

            function ecommerceDoughnutS1(selector, set_data){
                var $selector = (selector) ? $(selector) : $('.ecommerce-doughnut-s1');
                $selector.each(function(){
                    var $self = $(this), _self_id = $self.attr('id'), _get_data = (typeof set_data === 'undefined') ? eval(_self_id) : set_data;
                    var selectCanvas = document.getElementById(_self_id).getContext("2d");

                    var chart_data = [];
                    for (var i = 0; i < _get_data.datasets.length; i++) {
                        chart_data.push({
                            backgroundColor: _get_data.datasets[i].background,
                            borderWidth:2,
                            borderColor: _get_data.datasets[i].borderColor,
                            hoverBorderColor: _get_data.datasets[i].borderColor,
                            data: _get_data.datasets[i].data,
                        });
                    }
                    var chart = new Chart(selectCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: _get_data.labels,
                            datasets: chart_data,
                        },
                        options: {
                            legend: {
                                display: (_get_data.legend) ? _get_data.legend : false,
                                rtl: NioApp.State.isRTL,
                                labels: {
                                    boxWidth:12,
                                    padding:20,
                                    fontColor: '#6783b8',
                                }
                            },
                            rotation: -1.5,
                            cutoutPercentage:70,
                            maintainAspectRatio: false,
                            tooltips: {
                                enabled: true,
                                rtl: NioApp.State.isRTL,
                                callbacks: {
                                    title: function(tooltipItem, data) {
                                        return data['labels'][tooltipItem[0]['index']];
                                    },
                                    label: function(tooltipItem, data) {
                                        return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                                    }
                                },
                                backgroundColor: '#1c2b46',
                                titleFontSize: 13,
                                titleFontColor: '#fff',
                                titleMarginBottom: 6,
                                bodyFontColor: '#fff',
                                bodyFontSize: 12,
                                bodySpacing:4,
                                yPadding: 10,
                                xPadding: 10,
                                footerMarginTop: 0,
                                displayColors: false
                            },
                        }
                    });
                })
            }
            // init chart
            NioApp.coms.docReady.push(function(){ ecommerceDoughnutS1(); });

        })(NioApp, jQuery);

    </script>
