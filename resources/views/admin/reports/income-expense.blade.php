@extends('master')

@section('title')
    {{__('income').'/'.__('expense')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('income').'/'.__('expense')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.search.income.expense')}}" class="form-validate" method="GET">
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
                                            {{-- users --}}
                                            <div class="col-sm-4" id="user-select-area">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('user')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg change-user" id="user" name="user">
                                                            <option value="">{{ __('select_user') }}</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end user --}}
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('account')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg account-change" name="account">
                                                            <option value="">{{ __('select_account') }}</option>

                                                        </select>
                                                    </div>
                                                    @if($errors->has('report_type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('report_type') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('select_type')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg search-type" name="type">
                                                            <option value="">{{ __('select_type') }}</option>
                                                            <option value="income">{{__('income')}}</option>
                                                            <option value="expense">{{__('expense')}}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('type') }}</p>
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

    @if(isset($accounts))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $accounts->total() }} {{__($type)}}.</p>
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
                                                    <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>#</strong></span></div>
                                                    <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                    <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('source')}} / {{ __('details') }}</strong></span></div>
                                                    <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>

                                                </div><!-- .nk-tb-item -->
                                                @php $balance = 0; @endphp
                                                @foreach($accounts as $key => $company_account)
                                                    <div class="nk-tb-item" id="row_{{$company_account->id}}">
                                                        <div class="nk-tb-col column-max-width">
                                                            <span>{{$key + 1}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            {{$company_account->date != ""? date('M d, Y', strtotime($company_account->date)):''}}
                                                        </div>

                                                        <div class="nk-tb-col column-max-width">
                                                            {{ __($company_account->source)  }} <br>
                                                            {{ __($company_account->details)  }}
                                                        </div>
                                                        @if($company_account->type == 'income')
                                                            <div class="nk-tb-col column-max-width">
                                                                {{number_format($company_account->amount,2)}}
                                                            </div>
                                                        @elseif($company_account->type == 'expense')
                                                        <div class="nk-tb-col text-danger column-max-width">
                                                            {{number_format($company_account->amount,2)}}
                                                        </div>
                                                        @endif
                                                    </div><!-- .nk-tb-item -->
                                                @endforeach

                                                <div class="nk-tb-item nk-tb-head">
                                                    <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{ number_format($grand_total, 2) }}({{__('tk')}})</strong></span></div>

                                                </div><!-- .nk-tb-item -->
                                            </div><!-- .nk-tb-list -->
                                        </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $accounts->appends(Request::except('page','_token'))->links() !!}
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
    @if($type == 'income' && isset($summery_accounts))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $summery_accounts->total() }} {{__($type)}}.</p>
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
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('credit')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php $balance = 0; $i = 1; @endphp
                                            @foreach($main_datas as $key => $main_data)
                                                @php
                                                    @endphp

                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col column-max-width">
                                                        <span>{{$i++}}</span>
                                                    </div>
                                                    <div class="nk-tb-col column-max-width">
                                                        {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                                    </div>

                                                    <div class="nk-tb-col column-max-width">
                                                        @if(data_get($main_data, 'data1.type') == 'income')

                                                            {{number_format(data_get($main_data, 'data1.amount'),2)}}
                                                            @php $balance += data_get($main_data, 'data1.amount'); @endphp

                                                        @elseif(data_get($main_data, 'data2.type') == 'income')

                                                            {{number_format(data_get($main_data, 'data2.amount'),2)}}
                                                            @php $balance += data_get($main_data, 'data2.amount'); @endphp

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

                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head ">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{__('remaining_balance')}}</h6></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{number_format($data['grand_total'],2)}}</h6></span></div>

                                            </div><!-- .nk-tb-item -->
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $summery_accounts->appends(Request::except('page','_token'))->links() !!}
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

    @elseif($type == 'expense' && isset($summery_accounts))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $summery_accounts->total() }} {{__($type)}}.</p>
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
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('debit')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php $balance = 0; $i = 1; @endphp
                                            @foreach($main_datas as $key => $main_data)
                                            @php
                                            @endphp

                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col column-max-width">
                                                        <span>{{$i++}}</span>
                                                    </div>
                                                    <div class="nk-tb-col column-max-width">
                                                        {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                                    </div>
                                                    <div class="nk-tb-col column-max-width text-danger">

                                                        @if(data_get($main_data, 'data1.type') == 'expense')

                                                        {{number_format(data_get($main_data, 'data1.amount'),2)}}
                                                        @php $balance -= data_get($main_data, 'data1.amount'); @endphp

                                                        @elseif(data_get($main_data, 'data2.type') == 'expense')

                                                        {{number_format(data_get($main_data, 'data2.amount'),2)}}
                                                        @php $balance -= data_get($main_data, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif

                                                    </div>


                                                </div><!-- .nk-tb-item -->
                                            @endforeach
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($data['expense'],2)}}</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head ">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{__('remaining_balance')}}</h6></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><h6>{{number_format($data['grand_total'],2)}}</h6></span></div>
                                            </div><!-- .nk-tb-item -->
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $summery_accounts->appends(Request::except('page','_token'))->links() !!}
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
    @elseif(isset($summery_accounts))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>{{__('you_have_total')}} {{ $summery_accounts->total() }} {{__($type)}}.</p>
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
                                            @foreach($main_datas as $key => $main_data)
                                            @php
                                            @endphp

                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col column-max-width">
                                                        <span>{{$i++}}</span>
                                                    </div>
                                                    <div class="nk-tb-col column-max-width">
                                                        {{$key != ""? date('M d, Y', strtotime($key)):''}}
                                                    </div>

                                                    <div class="nk-tb-col column-max-width">
                                                        @if(data_get($main_data, 'data1.type') == 'income')

                                                        {{number_format(data_get($main_data, 'data1.amount'),2)}}
                                                        @php $balance += data_get($main_data, 'data1.amount'); @endphp

                                                        @elseif(data_get($main_data, 'data2.type') == 'income')

                                                        {{number_format(data_get($main_data, 'data2.amount'),2)}}
                                                        @php $balance += data_get($main_data, 'data2.amount'); @endphp

                                                        @else
                                                        <span>-.--</span>
                                                        @endif
                                                    </div>

                                                    <div class="nk-tb-col column-max-width text-danger">

                                                        @if(data_get($main_data, 'data1.type') == 'expense')

                                                        {{number_format(data_get($main_data, 'data1.amount'),2)}}
                                                        @php $balance -= data_get($main_data, 'data1.amount'); @endphp

                                                        @elseif(data_get($main_data, 'data2.type') == 'expense')

                                                        {{number_format(data_get($main_data, 'data2.amount'),2)}}
                                                        @php $balance -= data_get($main_data, 'data2.amount'); @endphp

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
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $summery_accounts->appends(Request::except('page','_token'))->links() !!}
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
                if($(this).val() == 'cash'){
                    $('#user-area').removeClass('d-none');
                    $('#user-select-area').removeClass('d-none');
                }else{
                    $('#user-area').addClass('d-none');
                }
            })
        });


        $(document).ready(function(){
            $('.change-user').on('change', function(){

                var token = "{{ csrf_token() }}";
                var url = "{{url('')}}"+'/admin/get-accounts';

                var formData = {
                    id: $(this).val()
                }

                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                })


                .done(function(response) {
                    console.log(response);

                    var option = '';
                    $( response ).each(function( index, account ) {

                        if(account.method == 'bank'){
                            option += "<option value='"+account.id+"'>("+account.method+") "+account.account_holder_name+', '+account.account_no+"</option>";
                        }else if(account.method == 'cash'){
                            option += "<option value='"+account.id+"'>("+account.method+") "+account.user.first_name+' '+account.user.last_name+', '+account.user.email+"</option>";
                        }else{
                            option += "<option value='"+account.id+"'>("+account.method+") "+account.account_holder_name+', '+account.number+"</option>";
                        }

                    });

                    $('.account-change').find('option').not(':first').remove();
                    $('.account-change').append(option);

                })
                .fail(function(error) {
                    Swal.fire('{{ __('opps') }}...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                })

            });
        });



    </script>


@endpush
@include('live_search.user')

