@extends('master')

@section('title')
    {{__('account_summary')}}
@endsection

@section('mainContent')
@if(!isset(request()->start_date))
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('account_summary')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="col-md-4">
                        <form action="{{ route('admin.account.summary-info')}}" class="form-validate" method="GET">
                            {{--                        @csrf--}}
                            <div class="card">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-inner">
                                            <div class="row g-gs">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('start_date')}} *</label>
                                                        <div class="form-control-wrap focused">
                                                            <div class="form-icon form-icon-right">
                                                                <em class="icon ni ni-calendar-alt"></em>
                                                            </div>
                                                            <input type="text" class="form-control date-picker" name="start_date" autocomplete="off" required placeholder="{{__('start_date')}}">
                                                        </div>
                                                        @if($errors->has('start_date'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('start_date') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('end_date')}} *</label>
                                                        <div class="form-control-wrap focused">
                                                            <div class="form-icon form-icon-right">
                                                                <em class="icon ni ni-calendar-alt"></em>
                                                            </div>
                                                            <input type="text" class="form-control date-picker" name="end_date" autocomplete="off" required placeholder="{{__('end_date')}}">
                                                        </div>
                                                        @if($errors->has('end_date'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('end_date') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-sm-12 text-right">
                                                    <div class="form-group">
                                                        <button type="submit" name="download" value="1" class="btn d-md-inline-flex btn-primary">{{{__('download')}}}</button>
                                                        <button type="submit" class="btn d-md-inline-flex btn-primary">{{{__('view')}}}</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @elseif(isset(request()->start_date))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between" style="line-height: 10px;">
                                <div class="nk-block-head-content">
                                    <p class="nk-block-title page-title">{{__('account_summary')}}</p>
                                    <p>{{__('Daily Profit/Loss')}}</p>
                                    <p>{{$start_date != ""? date('M d, Y', strtotime($start_date)):''}} - {{$end_date != ""? date('M d, Y', strtotime($end_date)):''}}</p>
                                    <div class="nk-block-des text-soft">
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block">
                            <div class="row g-gs">

                                <div class="col-xxl-12 col-md-12 col-lg-12">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <ul class="nk-top-products">
                                                <div class="card-inner p-0">
                                                    <div class="nk-tb-list nk-tb-ulist">
                                                        <div class="nk-tb-item nk-tb-head">

                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('delivered')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('returned')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('cash_collection')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('cash_from_merchant')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('charges')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('return_charge')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total_charge')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant_payable')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('rider_commission')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('cash_received_from_rider')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('payment_request')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('payment_processed')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('expense')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('balance')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Profit/Loss')}}</strong></span></div>
                                                        </div><!-- .nk-tb-item -->

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
                                                                    <div class="nk-tb-item">
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
                                                                                <div class="nk-tb-col tb-col-md">
                                                                                    <span class="sub-text">{{ $summary_info[$key][$i] }}</span>
                                                                                </div>
                                                                        @endforeach
                                                                    </div><!-- .nk-tb-item -->
                                                                @endfor
                                                                <div class="nk-tb-item">
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>Total</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ $total_parcel }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ $returned }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_cash_collection, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_cash_from_merchant, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_charges, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($return_charge, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_deliver_return_charge, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_merchant_payable, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_rider_commisssion, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_cash_received_from_rider, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ $total_payment_request }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_payment_processed, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_expense, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_balance, 2) }}</strong></span>
                                                                    </div>
                                                                    <div class="nk-tb-col tb-col-md">
                                                                        <span class="sub-text"><strong>{{ number_format($total_profit_loss, 2) }}</strong></span>
                                                                    </div>
                                                                </div><!-- .nk-tb-item -->



                                                    </div>
                                                </div>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col -->

                                <!-- sales details --->
                                {{-- <div class="col-xxl-6 col-md-6 col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('parcel_statistics') }} {{__('details')}}</h6>
                                                </div>
                                            </div>
                                            <ul class="nk-top-products">
                                                <div class="card-inner p-0">
                                                    @foreach($details as $data)

                                                        <span class="sub-text"> <strong>{{__('merchant')}} :</strong>  <strong class="text-info font-weight-bold"> {{__($data['merchant'])}}</strong></span>
                                                        <div class="nk-tb-list nk-tb-ulist">
                                                            <div class="nk-tb-item nk-tb-head">
                                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('pcs')}}</strong></span></div>
                                                            </div><!-- .nk-tb-item -->
                                                            @foreach($data as $event => $count)
                                                                @if($event !=='merchant')
                                                                    <div class="nk-tb-item">
                                                                        <div class="nk-tb-col tb-col-md">
                                                                            <span class="sub-text"><strong>{{ __($event) }}</strong></span>
                                                                        </div>
                                                                        <div class="nk-tb-col tb-col-md">
                                                                            <span class="sub-text"><strong> {{ $count  }} {{__('pcs')}}</strong></span>
                                                                        </div>
                                                                    </div><!-- .nk-tb-item -->
                                                                @endif
                                                            @endforeach
                                                        </div>

                                                        <hr>
                                                    @endforeach
                                                </div>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col --> --}}
                                <!-- sales details --->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@include('live_search.staff')
