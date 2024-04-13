@extends('master')

@section('title')
    {{__('merchant_total_summary')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('merchant_total_summary')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.search.merchant.summary')}}" class="form-validate" method="GET">
                        {{--                        @csrf--}}
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-sm-4">
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
                                            <div class="col-sm-4">
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
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('merchant')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-sm-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn d-md-inline-flex btn-primary">{{{__('search')}}}</button>
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

    @if(isset($data))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('summery_between')}} {{$date['start_date'] != ""? date('M d, Y', strtotime($date['start_date'])):''}} - {{$date['end_date'] != ""? date('M d, Y', strtotime($date['end_date'])):''}}</h3>
                                    <div class="nk-block-des text-soft">
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block">
                            <div class="row g-gs">

                                <div class="col-xxl-6 col-md-6 col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('parcel_statistics') }}</h6>
                                                </div>
                                            </div>
                                            <ul class="nk-top-products">
                                                <div class="card-inner p-0">
                                                    <div class="nk-tb-list nk-tb-ulist">
                                                        <div class="nk-tb-item nk-tb-head">

                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('pcs')}}</strong></span></div>
                                                        </div><!-- .nk-tb-item -->
                                                        @foreach($data as $event => $count)
                                                            <div class="nk-tb-item">
                                                                <div class="nk-tb-col tb-col-md">
                                                                    <span class="sub-text"><strong>{{ __($event) }}</strong></span>
                                                                </div>
                                                                <div class="nk-tb-col tb-col-md">
                                                                    <span class="sub-text"><strong> {{ $count  }} {{__('pcs')}}</strong></span>
                                                                </div>
                                                            </div><!-- .nk-tb-item -->
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                                <div class="col-xxl-6 col-md-6 col-lg-6">
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
                                                    </div>
                                                </div>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@include('live_search.merchants')
