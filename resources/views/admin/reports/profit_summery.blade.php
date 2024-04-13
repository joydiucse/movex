@extends('master')

@section('title')
    {{__('profit')}}
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
                    <form action="{{ route('admin.profit.summery')}}" class="form-validate" method="POST">
                        @csrf
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

    @if(isset($profits))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="row account-income-expense">
                    <div class="col-md-4 mt-2">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">{{__('profit_info')}}</h3>
                                            <div class="nk-block-des text-soft">
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-inner position-relative card-tools-toggle">
                                                <div class="card-title-group">
                                                    <div class="card-tools">

                                                    </div><!-- .card-tools -->
                                                </div><!-- .card-title-group -->
                                            </div><!-- .card-inner -->
                                            <div class="card-inner p-0">
                                                <div class="nk-tb-list nk-tb-ulist">
                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_charge')}}</span>
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
                                                            <span> - {{ number_format($profits['total_delivery_charge'], 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_vat')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span> - {{ number_format($profits['total_vat'], 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('total_profit')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_profit'], 2)}}</strong></span></div>
                                                    </div><!-- .nk-tb-item -->

                                                </div><!-- .nk-tb-list -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card-inner-group -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">{{__('payable_to_merchant')}}</h3>
                                            <div class="nk-block-des text-soft">
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-inner position-relative card-tools-toggle">
                                                <div class="card-title-group">
                                                    <div class="card-tools">

                                                    </div><!-- .card-tools -->
                                                </div><!-- .card-title-group -->
                                            </div><!-- .card-inner -->
                                            <div class="card-inner p-0">
                                                <div class="nk-tb-list nk-tb-ulist">
                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                        <div class="nk-tb-col "><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_payable_to_merchant')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>{{ number_format($profits['total_payable_to_merchant'], 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_paid_to_merchant')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span> - {{ number_format($profits['total_paid_to_merchant'], 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->

                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_payable')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_payable_to_merchant'] - $profits['total_paid_to_merchant'], 2) }}</strong></span></div>
                                                    </div><!-- .nk-tb-item -->

                                                </div><!-- .nk-tb-list -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card-inner-group -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">{{__('cash_collection_info')}}</h3>
                                            <div class="nk-block-des text-soft">
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-inner position-relative card-tools-toggle">
                                                <div class="card-title-group">
                                                    <div class="card-tools">

                                                    </div><!-- .card-tools -->
                                                </div><!-- .card-title-group -->
                                            </div><!-- .card-inner -->
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
                                                            <span> - {{ number_format($profits['total_paid_by_delivery_man'], 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->

                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_due_to_delivery_man')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($profits['total_cash_on_delivery'] - $profits['total_paid_by_delivery_man'], 2) }}</strong></span></div>
                                                    </div><!-- .nk-tb-item -->

                                                </div><!-- .nk-tb-list -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card-inner-group -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
