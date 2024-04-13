@extends('master')

@section('title')
    {{__('total_summery')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('summery')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.total_summery.report')}}" class="form-validate" method="GET">
{{--                        @csrf--}}
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-sm-6">
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
                                            <div class="col-sm-6">
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
                                    <h3 class="nk-block-title page-title">{{__('summery_between')}} {{$data['start_date'] != ""? date('M d, Y', strtotime($data['start_date'])):''}} - {{$data['end_date'] != ""? date('M d, Y', strtotime($data['end_date'])):''}}</h3>
                                    <div class="nk-block-des text-soft">
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->

                        <div class="nk-block" id="report-show">
                            <div class="row g-gs">

                                <div class="col-xxl-3 col-md-8 col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('parcel_info') }}</h6>
                                                </div>
                                            </div>
                                            <ul class="nk-top-products">
                                                <div class="card-inner p-0">
                                                    <div class="nk-tb-list nk-tb-ulist">
                                                        <div class="nk-tb-item nk-tb-head">

                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('pcs')}}</strong></span></div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('total_parcels')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['total_parcels']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel_delivered')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['delivered']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel').' '.__('partially-delivered')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['partially-delivered']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel').' '.__('pending-return')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['pending-return'] }} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel_returned_to_merchant')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['returned_to_merchant']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel').' '.__('deleted')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['deleted']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                        <div class="nk-tb-item">
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong>{{__('parcel_cancelled')}}</strong></span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span class="sub-text"><strong> {{ $data['cancelled']}} {{__('pcs')}}</strong></span>
                                                            </div>
                                                        </div><!-- .nk-tb-item -->
                                                    </div>
                                                </div>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div><!-- .card -->
                                </div><!-- .col -->

                                <div class="col-xxl-3 col-md-8 col-lg-6">
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

                                <div class="col-xxl-3 col-md-8 col-lg-6">
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
                                <div class="col-xxl-3 col-md-8 col-lg-6">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
