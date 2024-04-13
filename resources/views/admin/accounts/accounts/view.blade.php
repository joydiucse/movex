@extends('master')

@section('title')
{{__('account')}} {{__('lists')}}
@endsection

@section('mainContent')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('account_info')}}</h3>

                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('account_create'))
                                <a href="{{route('admin.account')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            @endif
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
                                        @if($account->method == 'bank')
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account_name')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->account_holder_name}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account_no')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->account_no}}</span></div>
                                        @elseif($account->method == 'cash')
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('name')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->user->first_name.' '.$account->user->last_name}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('email')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->user->email}}</span></div>
                                        @else
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account_name')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->account_holder_name}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('mobile_no')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ $account->number}}</span></div>
                                        @endif

                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('opening_balance')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format($account->balance, 2)}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('income')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format($total_income, 2)}}</span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('expense')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format(abs($total_expense), 2)}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('fund_received')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format($total_fund_received, 2)}}</span></div>
                                    </div><!-- .nk-tb-item -->
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('fund_transfered')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format(abs($total_fund_transfered), 2)}}</span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}} ({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text">{{ number_format($remaining_balance, 2)}}</span></div>
                                    </div><!-- .nk-tb-item -->
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
                <div class="row mt-3 account-income-expense">
                    <div class="col-xxl-6">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('you_have_total')}} {{ !blank($total_incomes)? $total_incomes->total():'0' }} {{__('income_lists')}}</h3>
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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{__('tk')}})</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                            @foreach($total_incomes as $total_income)
                                            <div class="nk-tb-item">
                                                <div class="nk-tb-col">
                                                    <span>
                                                        @if ($total_income->note != "")
                                                            {{__($total_income->note)}}<br>
                                                        @endif
                                                        @if ($total_income->details != "")
                                                            {{__($total_income->details)}}<br>
                                                        @endif
                                                        @if ($total_income->parcel != "")
                                                            <span>{{__('parcel_id')}} : {{$total_income->parcel->parcel_no}}</span><br>
                                                        @endif
                                                        @if ($total_income->deliveryMan != "")
                                                            <span>{{__('from_delivery_nam')}} : {{$total_income->deliveryMan->user->first_name .' '.$total_income->deliveryMan->user->last_name}}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{$total_income->date != ""? date('M d, Y', strtotime($total_income->date)):''}}
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{ number_format($total_income->amount, 2)}}
                                                </div>
                                            </div>
                                            @endforeach
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! !blank($total_incomes)? $total_incomes->links():'' !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('you_have_total')}} {{ !blank($total_expenses)? $total_expenses->total():'0' }} {{__('expense_lists')}}</h3>
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
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{__('date')}})</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                            @foreach($total_expenses as $total_expense)
                                            <div class="nk-tb-item">
                                                <div class="nk-tb-col">
                                                    <span>
                                                        @if ($total_expense->title != "")
                                                            {{$total_expense->title}}<br>
                                                        @endif
                                                        @if ($total_expense->source != "")
                                                            {{__($total_expense->source)}}<br>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{$total_expense->date != ""? date('M d, Y', strtotime($total_expense->date)):''}}
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{ number_format(abs($total_expense->amount), 2)}}
                                                </div>
                                            </div>
                                            @endforeach
                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! !blank($total_expenses)? $total_expenses->links():'' !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>


@endsection
