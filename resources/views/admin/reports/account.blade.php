@extends('master')

@section('title')
    {{__('account')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('account')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.search.account.report')}}" class="form-validate" method="GET">
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
                                                        <input type="text" class="form-control date-picker" name="start_date" value="{{ old('start_date') }}" autocomplete="off" required placeholder="{{__('start_date')}}">
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
                                                        <input type="text" class="form-control date-picker" name="end_date" value="{{ old('end_date') }}" autocomplete="off" required placeholder="{{__('end_date')}}">
                                                    </div>
                                                    @if($errors->has('end_date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('end_date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4" id="user-select-area">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('user')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="user" name="user" class="form-control form-control-lg user-live-search change-user">
{{--                                                        <select class="form-select form-control form-control-lg change-user" id="user" name="user">--}}
{{--                                                            <option value="">{{ __('select_user') }}</option>--}}
{{--                                                            @foreach($users as $user)--}}
{{--                                                                <option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>--}}
{{--                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                    @if($errors->has('user'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('user') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">

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
                                            <div class="col-sm-4 d-none">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('select_type')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg statement-type" name="type"  required=false>
                                                            <option value="">{{ __('select_type') }}</option>
                                                            <option value="income">{{__('income')}}</option>
                                                            <option value="expense">{{__('expense')}}</option>
                                                            <option value="fund_received">{{__('fund_received')}}</option>
                                                            <option value="fund_transfered">{{__('fund_transfered')}}</option>
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

    @if(isset($data))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">

                        {{-- @if($data['type'] == 'date-range-user')

                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('summery')}}</h3>

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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('opening_balance')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format($data['opening_balance'], 2)}}</span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('income')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format($data['total_income'], 2)}}</span></div>
                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('expense')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format(abs($data['total_expense']), 2)}}</span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('fund_received')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format($data['total_fund_received'], 2)}}</span></div>
                                            </div><!-- .nk-tb-item -->
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('fund_transfered')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format(abs($data['total_fund_transfered']), 2)}}</span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}} ({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text">{{ number_format($data['remaining_balance'], 2)}}</span></div>
                                            </div><!-- .nk-tb-item -->
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->

                        @else  --}}


                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                    <div class="nk-block-des text-soft">
                                        {{-- <p>{{__('you_have_total')}} {{ $account->total() }} {{__($type)}}.</p> --}}
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

                                    @if($type == "date-wise")

                                        <div class="card-inner p-0">
                                            <div class="nk-tb-list nk-tb-ulist">
                                                <div class="nk-tb-item nk-tb-head">
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account')}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('description')}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('debit')}}/{{__('income')}}({{__('tk')}})</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('credit')}}/{{__('expense')}}({{__('tk')}})</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('balance')}}({{__('tk')}})</strong></span></div>

                                                </div><!-- .nk-tb-item -->
                                                @php

                                                    $balance = 0;
                                                    $total_income = 0;
                                                    $total_expense = 0;

                                                @endphp
                                                @foreach($data as $key => $value)
                                                    @if($value['table'] == 'company_accounts')
                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col">
                                                            <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">

                                                                @if($value['data']->account->method == 'bank')
                                                                    <span>{{__('name')}}: {{$value['data']->account->account_holder_name}}</span><br>
                                                                    <span>{{__('account_no')}}: {{$value['data']->account->account_no}}</span><br>
                                                                    <span>{{__('bank')}}: {{__($value['data']->account->bank_name)}}</span><br>
                                                                    <span>{{__('branch')}}:{{$value['data']->account->bank_branch}}</span><br>
                                                                @elseif($value['data']->account->method == 'cash')
                                                                    <span>{{__('name')}}: {{$value['data']->account->user->first_name.' '.$value['data']->account->user->last_name}}({{__($value['data']->account->method)}})</span><br>
                                                                    <span>{{__('email')}}: {{$value['data']->account->user->email}}</span><br>
                                                                @else
                                                                    <span>{{__('name')}}: {{$value['data']->account->account_holder_name}}</span><br>
                                                                    <span>{{__('number')}}: {{$value['data']->account->number}}</span><br>
                                                                    <span>{{__('account_type')}}: {{__($value['data']->account->type)}}</span><br>
                                                                @endif
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                @if($value['data']->type == "income")
                                                                {{__($value['data']->details)}}
                                                                {{__($value['data']->note)}}
                                                                @else
                                                                {{__($value['data']->details)}}
                                                                {{__($value['data']->title)}}
                                                                @endif

                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                @if($value['data']->type == "income")
                                                                {{number_format($value['data']->amount,2)}}
                                                                @php
                                                                $balance += $value['data']->amount;
                                                                $total_income += $value['data']->amount;
                                                                @endphp
                                                                @else
                                                                -.--
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                @if($value['data']->type == "expense")
                                                                {{number_format(abs($value['data']->amount),2)}}
                                                                @php
                                                                $balance -= abs($value['data']->amount);
                                                                $total_expense += abs($value['data']->amount);
                                                                @endphp
                                                                @else
                                                                -.--
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                {{number_format($balance,2)}}
                                                            </span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    @else
                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col">
                                                            <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                                @if($value['data']->fromAccount->method == 'bank')
                                                                    <span>{{__('name')}}: {{$value['data']->fromAccount->account_holder_name}}</span><br>
                                                                    <span>{{__('account_no')}}: {{$value['data']->fromAccount->account_no}}</span><br>
                                                                    <span>{{__('bank')}}: {{__($value['data']->fromAccount->bank_name)}}</span><br>
                                                                    <span>{{__('branch')}}:{{$value['data']->fromAccount->bank_branch}}</span><br>
                                                                @elseif($value['data']->fromAccount->method == 'cash')
                                                                    <span>{{__('name')}}: {{$value['data']->fromAccount->user->first_name.' '.$value['data']->fromAccount->user->last_name}}({{__($value['data']->toAccount->method)}})</span><br>
                                                                    <span>{{__('email')}}: {{$value['data']->fromAccount->user->email}}</span><br>
                                                                @else
                                                                    <span>{{__('name')}}: {{$value['data']->fromAccount->account_holder_name}}</span><br>
                                                                    <span>{{__('number')}}: {{$value['data']->account->fromAccount}}</span><br>
                                                                    <span>{{__('account_type')}}: {{__($value['data']->fromAccount->type)}}</span><br>
                                                                @endif
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>Fund Transfered</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                -.--
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                {{number_format($value['data']->amount,2)}}
                                                                @php
                                                                $balance -= $value['data']->amount;
                                                                $total_expense += abs($value['data']->amount);
                                                                @endphp
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                {{number_format($balance,2)}}
                                                            </span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col">
                                                            <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">

                                                            @if($value['data']->toAccount->method == 'bank')
                                                                <span>{{__('name')}}: {{$value['data']->toAccount->account_holder_name}}</span><br>
                                                                <span>{{__('account_no')}}: {{$value['data']->toAccount->account_no}}</span><br>
                                                                <span>{{__('bank')}}: {{__($value['data']->toAccount->bank_name)}}</span><br>
                                                                <span>{{__('branch')}}:{{$value['data']->toAccount->bank_branch}}</span><br>
                                                            @elseif($value['data']->toAccount->method == 'cash')
                                                                <span>{{__('name')}}: {{$value['data']->toAccount->user->first_name.' '.$value['data']->toAccount->user->last_name}}({{__($value['data']->toAccount->method)}})</span><br>
                                                                <span>{{__('email')}}: {{$value['data']->toAccount->user->email}}</span><br>
                                                            @else
                                                                <span>{{__('name')}}: {{$value['data']->toAccount->account_holder_name}}</span><br>
                                                                <span>{{__('number')}}: {{$value['data']->toAccount->number}}</span><br>
                                                                <span>{{__('account_type')}}: {{__($value['data']->toAccount->type)}}</span><br>
                                                            @endif
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>Fund Received</span>
                                                        </div>

                                                        <div class="nk-tb-col">
                                                            <span>
                                                                {{number_format($value['data']->amount,2)}}
                                                                @php
                                                                $balance += $value['data']->amount;
                                                                $total_income += $value['data']->amount;
                                                                @endphp
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                -.--
                                                            </span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span>
                                                                {{number_format($balance,2)}}
                                                            </span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->

                                                    @endif
                                                @endforeach
                                                <div class="nk-tb-item nk-tb-head">
                                                    <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{__('grand_total')}}</strong></strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($total_income,2)}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($total_expense,2)}}</strong></span></div>
                                                    <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($balance,2)}}</strong></span></div>

                                                </div><!-- .nk-tb-item -->
                                            </div><!-- .nk-tb-list -->
                                        </div><!-- .card-inner -->
                                    @elseif($type == "date-user-wise")
                                    <div class="card-inner p-0">
                                        <div class="nk-tb-list nk-tb-ulist">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('description')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('debit')}}/{{__('income')}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('credit')}}/{{__('expense')}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('balance')}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                            @php

                                                $balance = 0;
                                                $total_income = 0;
                                                $total_expense = 0;

                                            @endphp
                                            @foreach($data as $key => $value)
                                                @if($value['table'] == 'company_accounts')
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col">
                                                        <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">

                                                            @if($value['data']->account->method == 'bank')
                                                                <span>{{__('name')}}: {{$value['data']->account->account_holder_name}}</span><br>
                                                                <span>{{__('account_no')}}: {{$value['data']->account->account_no}}</span><br>
                                                                <span>{{__('bank')}}: {{__($value['data']->account->bank_name)}}</span><br>
                                                                <span>{{__('branch')}}:{{$value['data']->account->bank_branch}}</span><br>
                                                            @elseif($value['data']->account->method == 'cash')
                                                                <span>{{__('name')}}: {{$value['data']->account->user->first_name.' '.$value['data']->account->user->last_name}}({{__($value['data']->account->method)}})</span><br>
                                                                <span>{{__('email')}}: {{$value['data']->account->user->email}}</span><br>
                                                            @else
                                                                <span>{{__('name')}}: {{$value['data']->account->account_holder_name}}</span><br>
                                                                <span>{{__('number')}}: {{$value['data']->account->number}}</span><br>
                                                                <span>{{__('account_type')}}: {{__($value['data']->account->type)}}</span><br>
                                                            @endif
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            @if($value['data']->type == "income")
                                                            {{__($value['data']->details)}}
                                                            {{__($value['data']->note)}}
                                                            @else
                                                            {{__($value['data']->details)}}
                                                            {{__($value['data']->title)}}
                                                            @endif

                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            @if($value['data']->type == "income")
                                                            {{number_format($value['data']->amount,2)}}
                                                            @php
                                                            $balance += $value['data']->amount;
                                                            $total_income += $value['data']->amount;
                                                            @endphp
                                                            @else
                                                            -.--
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            @if($value['data']->type == "expense")
                                                            {{number_format(abs($value['data']->amount),2)}}
                                                            @php
                                                            $balance -= abs($value['data']->amount);
                                                            $total_expense += abs($value['data']->amount);
                                                            @endphp
                                                            @else
                                                            -.--
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            {{number_format($balance,2)}}
                                                        </span>
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                                @elseif($value['table'] == 'fund_transfers')
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col">
                                                        <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                            @if($value['data']->fromAccount->method == 'bank')
                                                                <span>{{__('name')}}: {{$value['data']->fromAccount->account_holder_name}}</span><br>
                                                                <span>{{__('account_no')}}: {{$value['data']->fromAccount->account_no}}</span><br>
                                                                <span>{{__('bank')}}: {{__($value['data']->fromAccount->bank_name)}}</span><br>
                                                                <span>{{__('branch')}}:{{$value['data']->fromAccount->bank_branch}}</span><br>
                                                            @elseif($value['data']->fromAccount->method == 'cash')
                                                                <span>{{__('name')}}: {{$value['data']->fromAccount->user->first_name.' '.$value['data']->fromAccount->user->last_name}}({{__($value['data']->toAccount->method)}})</span><br>
                                                                <span>{{__('email')}}: {{$value['data']->fromAccount->user->email}}</span><br>
                                                            @else
                                                                <span>{{__('name')}}: {{$value['data']->fromAccount->account_holder_name}}</span><br>
                                                                <span>{{__('number')}}: {{$value['data']->account->fromAccount}}</span><br>
                                                                <span>{{__('account_type')}}: {{__($value['data']->fromAccount->type)}}</span><br>
                                                            @endif
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>Fund Transfered</span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            -.--
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            {{number_format($value['data']->amount,2)}}
                                                            @php
                                                            $balance -= $value['data']->amount;
                                                            $total_expense += abs($value['data']->amount);
                                                            @endphp
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            {{number_format($balance,2)}}
                                                        </span>
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                                @else
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col">
                                                        <span>{{$value['date'] != ""? date('M d, Y', strtotime($value['date'])):''}}</span>
                                                    </div>
                                                    <div class="nk-tb-col">

                                                        @if($value['data']->toAccount->method == 'bank')
                                                            <span>{{__('name')}}: {{$value['data']->toAccount->account_holder_name}}</span><br>
                                                            <span>{{__('account_no')}}: {{$value['data']->toAccount->account_no}}</span><br>
                                                            <span>{{__('bank')}}: {{__($value['data']->toAccount->bank_name)}}</span><br>
                                                            <span>{{__('branch')}}:{{$value['data']->toAccount->bank_branch}}</span><br>
                                                        @elseif($value['data']->toAccount->method == 'cash')
                                                            <span>{{__('name')}}: {{$value['data']->toAccount->user->first_name.' '.$value['data']->toAccount->user->last_name}}({{__($value['data']->toAccount->method)}})</span><br>
                                                            <span>{{__('email')}}: {{$value['data']->toAccount->user->email}}</span><br>
                                                        @else
                                                            <span>{{__('name')}}: {{$value['data']->toAccount->account_holder_name}}</span><br>
                                                            <span>{{__('number')}}: {{$value['data']->toAccount->number}}</span><br>
                                                            <span>{{__('account_type')}}: {{__($value['data']->toAccount->type)}}</span><br>
                                                        @endif
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>Fund Received</span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span>
                                                            {{number_format($value['data']->amount,2)}}
                                                            @php
                                                            $balance += $value['data']->amount;
                                                            $total_income += $value['data']->amount;
                                                            @endphp
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            -.--
                                                        </span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span>
                                                            {{number_format($balance,2)}}
                                                        </span>
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                                @endif
                                            @endforeach
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('grand_total')}}</strong></strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($total_income,2)}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($total_expense,2)}}({{__('tk')}})</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($balance,2)}}({{__('tk')}})</strong></span></div>

                                            </div><!-- .nk-tb-item -->
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->

                                    @endif


                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {{-- {!! $account->appends(Request::except('page','_token'))->links() !!} --}}
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
                            option += "<option value='"+account.id+"'>"+account.account_holder_name+', '+account.account_no+"</option>";
                        }else if(account.method == 'cash'){
                            option += "<option value='"+account.id+"'>"+account.user.first_name+' '+account.user.last_name+', '+account.user.email+"</option>";
                        }else{
                            option += "<option value='"+account.id+"'>"+account.account_holder_name+', '+account.number+"</option>";
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
