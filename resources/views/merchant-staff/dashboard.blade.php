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
                    <div class="nk-block">
                        <div class="row g-gs">
                            @if(hasPermission('manage_payment'))
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="card">
                                        <div class="nk-ecwg nk-ecwg6">
                                            <div class="card-inner">
                                                <div class="card-title-group">
                                                    <div class="card-title">
                                                        <h6 class="title">{{ __('current_balance') }}</h6>
                                                    </div>
                                                </div>
                                                <div class="data">
                                                    <div class="data-group">
                                                        <div class="amount"> {{number_format(\Sentinel::getUser()->staffMerchant->balance(\Sentinel::getUser()->staffMerchant->id),2).' '.__('tk') }}</div>
                                                    </div>
                                                </div>
                                            </div><!-- .card-inner -->
                                        </div><!-- .nk-ecwg -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                            @endif
                            @if(hasPermission('manage_parcel'))
                                <div class="col-xxl-{{ hasPermission('manage_payment') ? 3 : 4 }} col-sm-6">
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
                                                        <div class="amount"> {{number_format($data['cod'],2).' '.__('tk') }}</div>
                                                    </div>
                                                </div>
                                            </div><!-- .card-inner -->
                                        </div><!-- .nk-ecwg -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                                <div class="col-xxl-{{ hasPermission('manage_payment') ? 3 : 4 }} col-sm-6">
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
                                                        <div class="amount">{{ $data['all_parcels_count'] }}</div>
                                                    </div>
                                                </div>
                                            </div><!-- .card-inner -->
                                        </div><!-- .nk-ecwg -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                                <div class="col-xxl-{{ hasPermission('manage_payment') ? 3 : 4 }} col-sm-6">
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
                                                        <div class="amount">{{ $data['all_delivered_parcels'] }}</div>
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
                                                    <div class="card-tools">
                                                        {{ date('M, Y') }}
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
                                                <div class="chart-label-group pl-5">
                                                    <div class="chart-label">{{ date('01 '.'M, Y') }}</div>
                                                    <div class="chart-label">{{ date('t M, Y') }}</div>
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

                                                    <div class="card-tools">
                                                        {{ date('M, Y') }}
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
                                            <ul class="nk-store-statistics merchant">
                                                <li class="item">
                                                    <div class="info">
                                                        <div class="title">{{ __('total_parcels') }}</div>
                                                        <div class="count">{{ $counts['parcels_count'] }}</div>
                                                    </div>
                                                    <em class="icon ni ni-box bg-purple-dim"></em>
                                                </li>
                                                <li class="item">
                                                    <div class="info">
                                                        <div class="title">{{ __('delivered') }}</div>
                                                        <div class="count">{{ $counts['delivered'] }}</div>
                                                    </div>
                                                    <em class="icon ni ni-check-c bg-success-dim"></em>
                                                </li>
                                                <li class="item">
                                                    <div class="info">
                                                        <div class="title">{{ __('total_partially_delivered') }}</div>
                                                        <div class="count">{{ $counts['partial_delivered_count'] }}</div>
                                                    </div>
                                                    <em class="icon ni ni-check-c bg-green-dim"></em>
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

                                <div class="col-xxl-{{ hasPermission('manage_payment') ? 8 : 12 }}">
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
                                            @foreach($latest_parcel as $parcel)
                                                <div class="nk-tb-item">

                                                    <div class="nk-tb-col">
                                                        <div class="user-card">
                                                            <div class="user-name">
                                                                <a href="{{ route('merchant.parcel.detail',$parcel->parcel_no) }}">
                                                                    <tr><td>{{__('id')}}:#{{ $parcel->parcel_no }}</td></tr>
                                                                    <tr><td>{{__('invno')}}:{{$parcel->customer_invoice_no}}</td></tr>
                                                                </a>
                                                            </div>
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
                            @if(hasPermission('manage_payment'))
                                <div class="col-xxl-4 col-md-8 col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('recent_withdraws') }}</h6>
                                                </div>
                                            </div>
                                            <ul class="nk-top-products">
                                                @foreach(Sentinel::getUser()->staffMerchant->withdraws->where('status','processed')->take(5) as $withdraw)
                                                    <li class="item">
                                                        <div class="info">
                                                            <div class="title">{{ Str::limit($withdraw->note, 30, '...') }}</div>
                                                            <div class="price">{{@$withdraw->account->created_at != ""? date('M d, Y h:i a', strtotime($withdraw->created_at)):''}}</div>
                                                        </div>
                                                        <div class="total">
                                                            <div class="amount">{{ number_format($withdraw->amount,2) }}</div>
                                                            <div class="count">{{ __($withdraw->withdraw_to) }}</div>
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
                    dash : [5],
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
