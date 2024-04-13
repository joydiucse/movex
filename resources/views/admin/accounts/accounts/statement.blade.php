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
                            <h3 class="nk-block-title page-title">{{__('you_have_total')}} {{ !blank($accounts)? $accounts->total():'0' }} {{__('statement')}}</h3>
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('source')}} / {{ __('details') }}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    @php $balance = 0; @endphp
                                    @foreach($accounts as $key => $account)
                                    <div class="nk-tb-item" id="row_{{$account->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            {{ $account->date != "" ? date('M d, Y', strtotime($account->date)) : '' }}<br>
                                            {{$account->updated_at != ""? date('M d, Y h:i a', strtotime($account->updated_at)):''}}
                                        </div>

                                        <div class="nk-tb-col">
                                            {{ __($account->source)  }}
                                            @if(@$account->companyAccount->source == 'delivery_charge_receive_from_merchant')
                                                {{ __('from') }}<br>
                                                <span>{{__('merchant_name')}}: {{$account->companyAccount->merchant->user->first_name.' '.$account->companyAccount->merchant->user->last_name.' ('.$account->companyAccount->merchant->company.')'}}</span><br>
                                                <span>{{__('phone_number')}}: {{$account->companyAccount->merchant->phone_number}}</span>
                                            @endif
                                            @if(@$account->companyAccount->source == 'cash_receive_from_delivery_man')
                                                <span>{{__('delivery_man')}}: {{$account->companyAccount->deliveryMan->user->first_name.' '.$account->companyAccount->deliveryMan->user->last_name}}</span><br>
                                                <span>{{__('phone_number')}}: {{$account->companyAccount->deliveryMan->phone_number}}</span>
                                            @endif

                                            @if ($account->source == 'withdraw' && @$account->companyAccount->withdraw)
                                                <a href="{{route('admin.withdraw.invoice', $account->companyAccount->withdraw->id)}}">
                                                    {{ __('id').'#'.__($account->companyAccount->withdraw->withdraw_id) }}
                                                </a>
                                            @endif
                                            <br>
                                            {{ __($account->details)  }} <br>
                                            {{ $account->deliveryMan }}
                                            @if($account->fundTransfer != "")
                                                @if($account->fundTransfer->toAccount->method == 'bank')
                                                    <span>{{__('name')}}: {{$account->fundTransfer->toAccount->account_holder_name}}</span><br>
                                                    <span>{{__('account_no')}}: {{$account->fundTransfer->toAccount->account_no}}</span><br>
                                                    <span>{{__('bank')}}: {{__($account->fundTransfer->toAccount->bank_name)}}</span><br>
                                                    <span>{{__('branch')}}:{{$account->fundTransfer->toAccount->bank_branch}}</span><br>
                                                @elseif($account->fundTransfer->toAccount->method == 'cash')
                                                    <span>{{__('name')}}: {{$account->fundTransfer->toAccount->user->first_name.' '.$account->fundTransfer->toAccount->user->last_name}}</span><br>
                                                    <span>{{__('email')}}: {{$account->fundTransfer->toAccount->user->email}}</span><br>
                                                @else
                                                    <span>{{__('name')}}: {{$account->fundTransfer->toAccount->account_holder_name}}</span><br>
                                                    <span>{{__('number')}}: {{$account->fundTransfer->toAccount->number}}</span><br>
                                                    <span>{{__('account_type')}}: {{__($account->fundTransfer->toAccount->type)}}</span><br>
                                                @endif

                                            @endif
                                        </div>


                                        @if($account->type == 'income')
                                        <div class="nk-tb-col">
                                            {{number_format($account->amount,2)}}
                                        </div>
                                        @else
                                        <div class="nk-tb-col text-danger">
                                            {{number_format($account->amount,2)}}
                                        </div>
                                        @endif

                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('remaining_balance')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong></strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{number_format($grand_total,2)}}</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! !blank($accounts)? $accounts->links():'' !!}
                                    </div>
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
