@extends('master')

@section('title')
    {{__('dashboard')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{ __('dashboard') }}</h3>
                            </div><!-- .nk-block-head-content -->
                            @if(hasPermission('dashboard_statistics_read'))
                                <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="more-options">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span id="report-type">{{ __('today') }}</span></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="#" class="report-type active" id="today" data-report-type="today" data-ln="{{ __('today') }}"><span>{{ __('today') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="yesterday" data-report-type="yesterday" data-ln="{{ __('yesterday') }}"><span>{{ __('yesterday') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="this_week" data-report-type="this_week" data-ln="{{ __('this_week') }}"><span>{{ __('this_week') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="last_week" data-report-type="last_week" data-ln="{{ __('last_week') }}"><span>{{ __('last_week') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="this_month" data-report-type="this_month" data-ln="{{ __('this_month') }}"><span>{{ __('this_month') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="last_month" data-report-type="last_month" data-ln="{{ __('last_month') }}"><span>{{ __('last_month') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="last_3_month" data-report-type="last_3_month" data-ln="{{ __('last_3_month') }}"><span>{{ __('last_3_month') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="last_6_month" data-report-type="last_6_month" data-ln="{{ __('last_6_month') }}"><span>{{ __('last_6_month') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="this_year" data-report-type="this_year" data-ln="{{ __('this_year') }}"><span>{{ __('this_year') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="last_year" data-report-type="last_year" data-ln="{{ __('last_year') }}"><span>{{ __('last_year') }}</span></a></li>
                                                            <li><a href="#" class="report-type" id="lifetime" data-report-type="lifetime" data-ln="{{ __('lifetime') }}"><span>{{ __('lifetime') }}</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle btn dropdown-indicator btn btn-outline-light btn-white" data-toggle="dropdown">{{ __('custom') }}</a>
                                                    <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                        <div class="dropdown-head">
                                                            <span class="sub-title dropdown-title">{{ __('select_date_range') }}</span>
                                                        </div>
                                                        <div class="dropdown-body dropdown-body-rg">
                                                            <div class="row gx-6 gy-3">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ __('start_date') }}</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="form-icon form-icon-left">
                                                                                <em class="icon ni ni-calendar"></em>
                                                                            </div>
                                                                            <input type="text" class="form-control date-picker" name="start_date" value="" id="start_date" data-date-format="yyyy-mm-dd" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ __('end_date') }}</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="form-icon form-icon-left">
                                                                                <em class="icon ni ni-calendar"></em>
                                                                            </div>
                                                                            <input type="text" class="form-control date-picker" name="end_date" value="" id="end_date" data-date-format="yyyy-mm-dd" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 right">
                                                                    <div class="form-group">
                                                                        <div class="right">
                                                                            {{-- <a href="#" type="button" class="btn btn-secondary custom-filter">{{ __('report') }}</a> --}}
                                                                            <button type="button"  class="btn btn-secondary custom-filter">{{ __('report') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- .filter-wg -->
                                                </div><!-- .dropdown -->
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            @endif
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @foreach($notices as $notice)
                        <div class="example-alert mb-3">
                            <div class="alert {{ $notice->alert_class }} alert-icon alert-dismissible">
                                <em class="icon ni ni-notice"></em>
                                {{ $notice->details }}
                                <button class="close" data-dismiss="alert"></button>
                            </div>
                        </div>
                    @endforeach
                    <div class="nk-block" id="report-show">
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
                                                <div class="chart-label-group pl-4">
                                                    <div class="chart-label" id="start_date">12AM</div>
                                                    <div class="chart-label" id="end_date">12PM</div>
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

                                                            <div class="nk-tb-col"><span class="sub-text">{{__('new_merchant_registered')}}</span></div>
                                                            <div class="nk-tb-col"><span class="sub-text">{{ $profits['total_new_merchant'] }}</span></div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="row g-gs">
{{--                         render report here--}}
{{--                          rendering end--}}
                            @if(hasPermission('parcel_read'))
                                <div class="col-xxl-8">
                                <div class="card card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('recent_parcels') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-tb-list mt-n2">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span>{{__('no')}}</span></div>
                                            <div class="nk-tb-col tb-col-sm"><span>{{ __('customer') }}</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>{{ __('customer_phone') }}</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>{{ __('location') }}</span></div>
                                            <div class="nk-tb-col"><span class="d-sm-inline">Status</span></div>
                                        </div>
                                        @foreach($recent_parcels as $parcel)
                                            <div class="nk-tb-item">

                                                <div class="nk-tb-col">
                                                    <div class="user-card">
                                                        <div class="user-name">
                                                            <a href="{{ route('admin.parcel.detail',$parcel->parcel_no) }}">
                                                                <tr><td>{{__('id')}}:#{{ $parcel->parcel_no }}</td></tr>
                                                                <tr><td>{{__('invno')}}:{{$parcel->customer_invoice_no}}</td></tr>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="info">
                                                        <span class="title">@if(hasPermission('merchant_read'))<a href="{{route('detail.merchant.personal.info', $parcel->merchant->id)}}"> @endif{{$parcel->merchant->user->first_name.'('.$parcel->merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span>@if(hasPermission('merchant_read'))</a>@endif</span>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-sm">
                                                    <div class="user-card">
                                                        <div class="user-name">
                                                            <span class="tb-lead">{{ $parcel->customer_name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <div class="user-card">
                                                        <div class="user-name">
                                                            <span class="tb-lead">{{ $parcel->customer_phone_number }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span class="tb-sub">{{ __($parcel->location) }}</span>
                                                </div>
                                                <div class="nk-tb-col column-max-width">
                                                    @if($parcel->status == 'pending')
                                                        <span  class="badge text-warning">{{ __($parcel->status) }}</span><br>
                                                    @elseif($parcel->status == 'deleted')
                                                        <span  class="badge text-danger">{{ __('deleted') }}</span><br>
                                                    @elseif($parcel->status == 'cancel')
                                                        <span  class="badge text-danger">{{ __('cancelled') }}</span><br>
                                                    @elseif($parcel->status == 'pickup-assigned')
                                                        <span  class="badge text-pink">{{ __('pickup-assigned') }}</span><br>
                                                    @elseif($parcel->status == 're-schedule-pickup')
                                                        <span  class="badge text-purple">{{ __('re-schedule-pickup') }}</span><br>
                                                    @elseif($parcel->status == 'received-by-pickup-man')
                                                        <span  class="badge text-yale">{{ __('received-by-pickup-man') }}</span><br>
                                                    @elseif($parcel->status == 'received')
                                                        <span  class="badge text-blue">{{ __('received_by_warehouse') }}</span><br>
                                                    @elseif($parcel->status == 'transferred-to-hub')
                                                        <span  class="badge text-pigeon">{{ __('transferred-to-hub') }}</span><br>
                                                    @elseif($parcel->status == 'transferred-received-by-hub')
                                                        <span  class="badge text-prussian">{{ __('transferred-received-by-hub') }}</span><br>
                                                    @elseif($parcel->status == 'delivery-assigned')
                                                        <span  class="badge text-pear">{{ __('delivery-assigned') }}</span><br>
                                                    @elseif($parcel->status == 're-schedule-delivery')
                                                        <span  class="badge text-brown">{{ __('re-schedule-delivery') }}</span><br>
                                                    @elseif($parcel->status == 'returned-to-greenx')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-warning">{{ __('returned-to-greenx') }}</span><br>
                                                    @elseif($parcel->status == 'return-assigned-to-merchant')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-indigo">{{ __('return-assigned-to-merchant') }}</span><br>
                                                    @elseif($parcel->status == 'partially-delivered')
                                                        <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                    @elseif($parcel->status == 'delivered')
                                                        <span  class="badge text-success">{{ __('delivered') }}</span><br>
                                                    @elseif($parcel->status == 'delivered-and-verified')
                                                        <span  class="badge text-success">{{ __('delivered-and-verified') }}</span><br>
                                                    @elseif($parcel->status == 'returned-to-merchant')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-success">{{ __('returned-to-merchant') }}</span><br>
                                                    @elseif($parcel->status == 're-request')
                                                        <span  class="badge text-warning">{{ __('re-request') }}</span><br>
                                                    @endif
                                                    <span class="badge text-info">{{__($parcel->parcel_type)}}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div><!-- .card -->
                            </div>
                            @endif
                            @if(hasPermission('withdraw_read'))
                                <div class="col-xxl-4 col-md-8 col-lg-6">
                                <div class="card h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group mb-2">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('recent_withdraws') }}</h6>
                                            </div>
                                        </div>
                                        <ul class="nk-top-products">
                                            @foreach($withdraws as $withdraw)
                                                <li class="item">
                                                    <div class="info">
                                                        <span class="title">@if(hasPermission('merchant_read'))<a href="{{route('detail.merchant.personal.info', $withdraw->merchant->id)}}"> @endif{{$withdraw->merchant->user->first_name.'('.$withdraw->merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span>@if(hasPermission('merchant_read'))</a>@endif</span>
                                                        <div class="price">{{@$withdraw->account->created_at != ""? date('M d, Y h:i a', strtotime($withdraw->created_at)):''}}</div>
                                                    </div>
                                                    <div class="total">
                                                        <a href="{{route('admin.withdraw.details', $withdraw->id)}}">
                                                            <div class="amount text-success">{{ number_format($withdraw->amount,2) }}</div>
                                                            <div class="count">{{ __($withdraw->withdraw_to) }}</div>
                                                        </a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div><!-- .card-inner -->
                                </div><!-- .card -->
                            </div><!-- .col -->
                            @endif
                        </div><!-- .row -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {

            $('.report-type').on('click', function (e) {
                e.preventDefault();
                var report_type = $(this).attr('data-report-type');
                var report_ln = $(this).attr('data-ln');

                var formData = {
                    report_type : report_type
                }

                $.ajax({
                    type: "GET",
                    dataType: 'html',
                    data: formData,
                    async:false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.default.dashboard') }}',
                    success: function (data) {
                        $('#report-show').html(data);
                        $('.report-type').removeClass('active');
                        $('#'+report_type).addClass('active');
                        $('#report-type').html(report_ln)
                    },
                    error: function (data) {
                    }
                });
            });

            $('.custom-filter').on('click', function () {
                // e.preventDefault();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                //var report_ln = '{{ __('custom') }}';

                var formData = {
                    start_date : start_date,
                    end_date   : end_date
                }

                $.ajax({
                    type: "post",
                    //dataType: 'html',
                    data: formData,
                    //async:false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.default.dashboard.custom') }}',
                    success: function (data) {
                        $('#report-show').html(data);
                        //$('.report-type').removeClass('active');
                        //$('#report-type').html(report_ln)
                    },
                    error: function (data) {
                    }
                });
            });
        });
    </script>
@endpush
@push('script')
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
@endpush
