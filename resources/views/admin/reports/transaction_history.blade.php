@extends('master')

@section('title')
    {{__('transaction_history')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('transaction_history')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.search.transaction')}}" class="form-validate" method="GET" enctype="multipart/form-data">
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
                                                        <input type="text" class="form-control date-picker" required name="start_date" autocomplete="off" placeholder="{{__('start_date')}}">
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
                                                        <input type="text" class="form-control date-picker" required name="end_date" autocomplete="off" placeholder="{{__('end_date')}}">
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
                                                    <label class="form-label">{{__('report_type')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="report_type" required>
                                                            <option value="">{{ __('select_report_type') }}</option>
                                                            <option value="statement">{{__('statement')}}</option>
                                                            <option value="summery" selected>{{__('summery')}}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('type') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('select_purpose')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg search-type" required name="purpose">
                                                            <option value="">{{ __('select_type') }}</option>
                                                            <option value="total-charge-with-vat">{{__('total_charge_with_vat')}}</option>
                                                            <option value="charge">{{__('charge')}}</option>
                                                            <option value="vat">{{__('vat')}}</option>
                                                            <option value="delivery_man">{{__('delivery_man')}}</option>
                                                            <option value="merchant">{{__('merchant')}}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('type') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 d-none" id="merchant-area">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('merchant')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search">
                                                            {{--                                                    <select class="form-select form-control form-control-lg" name="merchant">--}}
                                                            {{--                                                        <option value="">{{ __('select_merchant') }}</option>--}}
                                                            {{--                                                        @foreach($merchants as $merchant)--}}
                                                            {{--                                                                <option value="{{ $merchant->id }}">{{ $merchant->user->first_name.' '.$merchant->user->last_name }} ({{$merchant->company}})</option>--}}
                                                            {{--                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 d-none" id="delivery-area">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('delivery_man')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="delivery-man-live-search" name="delivery_man" class="form-control form-control-lg delivery-man-live-search">
                                                            {{--                                                    <select class="form-select form-control form-control-lg" name="delivery_man">--}}
                                                            {{--                                                        <option value="">{{ __('select_delivery_man') }}</option>--}}
                                                            {{--                                                        @foreach($delivery_men as $delivery_man)--}}
                                                            {{--                                                                <option value="{{ $delivery_man->id }}">{{ $delivery_man->user->first_name.' '.$delivery_man->user->last_name }}</option>--}}
                                                            {{--                                                            @endforeach--}}
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
                </div>
                </form>
            </div>
        </div>
    </div>

@if(isset($charges))
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{__('you_have_total')}} {{ $charges->total() }} {{__('charges')}}.</p>
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('parcel_no')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    @php $balance = 0; @endphp
                                    @foreach($charges as $key => $charge)
                                    <div class="nk-tb-item" id="row_{{$charge->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            {{$charge->date != ""? date('M d, Y', strtotime($charge->date)):''}}
                                        </div>
                                        <div class="nk-tb-col">
                                            <a href="{{ route('admin.parcel.detail',$charge->parcel->id) }}">
                                                <table>
                                                    <tr><td>{{__('id')}}:#{{ $charge->parcel->parcel_no }}</td></tr>
                                                    <tr><td>{{__('invno')}}:{{$charge->parcel->customer_invoice_no}}</td></tr>

                                                </table>
                                            </a>
                                        </div>
                                        @if($charge->type == 'income')
                                        <div class="nk-tb-col">

                                            {{number_format($charge->amount,2)}}

                                        </div>
                                        @else
                                        <div class="nk-tb-col text-danger">

                                            {{number_format($charge->amount,2)}}

                                        </div>
                                        @endif
                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['grand_total'],2)}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                </div>
                                <!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! $charges->appends(Request::except('page'))->links() !!}
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(isset($summery_charges))
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{__('you_have_total')}} {{ $summery_charges->total() }} {{__('charges')}}.</p>
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('credit')}}({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('debit')}}({{__('tk')}})</strong></span></div>

                                    </div><!-- .nk-tb-item -->
                                    @php $balance = 0; $i = 0; @endphp
                                    @foreach($main_datas as $key => $charge)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{++$i}}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                            </div>
                                            <div class="nk-tb-col">
                                                @if(data_get($charge, 'data1.type') == 'expense')

                                                {{number_format(data_get($charge, 'data1.amount'),2)}}
                                                @php $balance += data_get($charge, 'data1.amount'); @endphp

                                                @elseif(data_get($charge, 'data2.type') == 'expense')

                                                {{number_format(data_get($charge, 'data2.amount'),2)}}
                                                @php $balance += data_get($charge, 'data2.amount'); @endphp

                                                @else
                                                <span>-.--</span>
                                                @endif
                                            </div>
                                            <div class="nk-tb-col text-danger">
                                                @if(data_get($charge, 'data1.type') == 'income')

                                                {{number_format(data_get($charge, 'data1.amount'),2)}}
                                                @php $balance -= data_get($charge, 'data1.amount'); @endphp

                                                @elseif(data_get($charge, 'data2.type') == 'income')

                                                {{number_format(data_get($charge, 'data2.amount'),2)}}
                                                @php $balance -= data_get($charge, 'data2.amount'); @endphp

                                                @else
                                                <span>-.--</span>
                                                @endif
                                            </div>
                                        </div><!-- .nk-tb-item -->
                                    @endforeach
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['income'],2)}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['expense'],2)}}</strong></span></div>

                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head ">
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                        <div class="nk-tb-col"><span class="sub-text"><h6>{{__('remaining_balance')}}</h6></span></div>
                                        <div class="nk-tb-col text-center ml-2"><span class="sub-text"><h6>{{number_format($data['grand_total'],2)}}</h6></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                    </div><!-- .nk-tb-item -->
                                </div>
                                <!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! $summery_charges->appends(Request::except('page'))->links() !!}
                                    </div>
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($vats))
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{__('you_have_total')}} {{ $vats->total() }} {{__('vats')}}.</p>
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>

                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>

                                    </div><!-- .nk-tb-item -->
                                    @php $balance = 0; @endphp
                                    @foreach($vats as $key => $vat)
                                        <div class="nk-tb-item" id="row_{{$vat->id}}">
                                            <div class="nk-tb-col">
                                                <span>{{$key + 1}}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                {{$vat->date != ""? date('M d, Y', strtotime($vat->date)):''}}
                                            </div>
                                            <div class="nk-tb-col">
                                                <span>{{__($vat->details)}}</span>
                                                <a href="{{ route('admin.parcel.detail',$vat->parcel->id) }}">
                                                    <table>
                                                        <tr><td>{{__('parcel_id')}}:#{{ $vat->parcel->parcel_no }}</td></tr>
                                                        <tr><td>{{__('invno')}}:{{$vat->parcel->customer_invoice_no}}</td></tr>

                                                    </table>
                                                </a>
                                            </div>
                                            @if($vat->type == 'income')
                                            <div class="nk-tb-col">

                                                {{number_format($vat->amount,2)}}


                                            </div>
                                            @else
                                            <div class="nk-tb-col text-danger">

                                                {{number_format($vat->amount,2)}}


                                            </div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                    @endforeach
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['grand_total'],2)}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                </div>
                                <!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! $vats->appends(Request::except('page'))->links() !!}
                                    </div>
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    @endif

    @if(isset($summery_vats))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $summery_vats->total() }} {{__('charges')}}.</p>
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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('credit')}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('debit')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php $balance = 0; $i = 0; @endphp
                                            @foreach($main_datas as $key => $vat)
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col">
                                                        <span>{{++$i}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        @if(data_get($vat, 'data1.type') == 'income')

                                                        {{number_format(data_get($vat, 'data1.amount'),2)}}
                                                        @php $balance += data_get($vat, 'data1.amount'); @endphp

                                                        @elseif(data_get($vat, 'data2.type') == 'income')

                                                        {{number_format(data_get($vat, 'data2.amount'),2)}}
                                                        @php $balance += data_get($vat, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif
                                                    </div>
                                                    <div class="nk-tb-col text-danger">
                                                        @if(data_get($vat, 'data1.type') == 'expense')

                                                        {{number_format(data_get($vat, 'data1.amount'),2)}}
                                                        @php $balance -= data_get($vat, 'data1.amount'); @endphp

                                                        @elseif(data_get($vat, 'data2.type') == 'expense')

                                                        {{number_format(data_get($vat, 'data2.amount'),2)}}
                                                        @php $balance -= data_get($vat, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                            @endforeach
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['income'],2)}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['expense'],2)}}</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head ">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{__('remaining_balance')}}</h6></span></div>
                                                <div class="nk-tb-col text-center ml-2"><span class="sub-text"><h6>{{number_format($data['grand_total'],2)}}</h6></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                        </div>
                                        <!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $summery_vats->appends(Request::except('page'))->links() !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($mer_deli_transactions))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $mer_deli_transactions->total() }} {{__('statements')}}.</p>
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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}/{{__('parcel_no')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php $balance = 0; @endphp
                                            @foreach($mer_deli_transactions as $key => $transaction)
                                                <div class="nk-tb-item" id="row_{{$transaction->id}}">
                                                    <div class="nk-tb-col">
                                                        <span>{{$key + 1}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        {{$transaction->date != ""? date('M d, Y', strtotime($transaction->date)):''}}
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>{{__($transaction->details)}}</span>
                                                        @if(!blank($transaction->parcel))
                                                            <a href="{{ route('admin.parcel.detail',@$transaction->parcel->id) }}">
                                                                <table>
                                                                    <tr><td>{{__('id')}}:#{{ @$transaction->parcel->parcel_no }}</td></tr>
                                                                    <tr><td>{{__('invno')}}:{{@$transaction->parcel->customer_invoice_no}}</td></tr>

                                                                </table>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    @if($transaction->type == 'income')
                                                    <div class="nk-tb-col">
                                                        {{number_format($transaction->amount,2)}}
                                                    </div>
                                                    @else
                                                    <div class="nk-tb-col text-danger">
                                                        {{number_format($transaction->amount,2)}}

                                                    </div>
                                                    @endif

                                                </div><!-- .nk-tb-item -->
                                            @endforeach
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['grand_total'],2)}}({{__('tk')}})</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                        </div>
                                        <!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $mer_deli_transactions->appends(Request::except('page'))->links() !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($summery_mer_deli_transactions))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $summery_mer_deli_transactions->total() }} {{__('statements')}}.</p>
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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('credit')}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('debit')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php $balance = 0; $i = 1; @endphp
                                            @foreach($main_datas as $key => $transaction)
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col">
                                                        <span>{{$i++}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        @if(data_get($transaction, 'data1.type') == 'income')

                                                        {{number_format(data_get($transaction, 'data1.amount'),2)}}
                                                        @php $balance += data_get($transaction, 'data1.amount'); @endphp

                                                        @elseif(data_get($transaction, 'data2.type') == 'income')

                                                        {{number_format(data_get($transaction, 'data2.amount'),2)}}
                                                        @php $balance += data_get($transaction, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif
                                                    </div>
                                                    <div class="nk-tb-col text-danger">
                                                        @if(data_get($transaction, 'data1.type') == 'expense')

                                                        {{number_format(data_get($transaction, 'data1.amount'),2)}}
                                                        @php $balance -= data_get($transaction, 'data1.amount'); @endphp

                                                        @elseif(data_get($transaction, 'data2.type') == 'expense')

                                                        {{number_format(data_get($transaction, 'data2.amount'),2)}}
                                                        @php $balance -= data_get($transaction, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                            @endforeach
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['income'],2)}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['expense'],2)}}</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head ">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{__('remaining_balance')}}</h6></span></div>
                                                <div class="nk-tb-col text-center ml-2"><span class="sub-text"><h6>{{number_format($data['grand_total'],2)}}</h6></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                        </div>
                                        <!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $summery_mer_deli_transactions->appends(Request::except('page'))->links() !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    @endif



@endsection
@push('script')

    <script>
        $(document).ready(function(){
            $('.search-type').on('change', function(){
                if($(this).val() == 'merchant'){
                    $('#merchant-area').removeClass('d-none');
                    $('#delivery-area').addClass('d-none');
                }else if($(this).val() == 'delivery_man'){
                    $('#delivery-area').removeClass('d-none');
                    $('#merchant-area').addClass('d-none');
                }else{
                    $('#merchant-area').addClass('d-none');
                    $('#delivery-area').addClass('d-none');
                }
            })
        })
    </script>

@endpush
@include('live_search.merchants')
@include('live_search.delivery-man')
