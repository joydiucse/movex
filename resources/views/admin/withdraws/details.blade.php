@extends('master')

@section('title')
    {{__('payment').' '.__('details')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('payment').' '.__('details')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <div class="drodown">
                                                    <a href="{{url()->previous()}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="card">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-inner card-inner-lg">

                                    <div class="nk-block">
                                        <div class="nk-data data-list">
                                            <div class="data-head">
                                                <h6 class="overline-title">{{__('details')}}</h6>
                                            </div>
                                            <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('merchant')}}</span>
                                                    <a href="{{route('detail.merchant.personal.info', $withdraw->merchant->id)}}">
                                                        <span class="data-value text-success">{{ $withdraw->merchant->company }}</span>
                                                    </a>
                                                </div>
                                            </div><!-- data-item -->

                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('payment_to')}}</span>
                                                    <span class="data-value">{{ $withdraw->account_details }}</span>
                                                </div>
                                            </div><!-- data-item -->

                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('amount').' ('.__('tk')}})</span>
                                                    <span class="data-value">{{ $withdraw->amount }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('status')}}</span>
                                                    <span class="data-value">
                                                        @if($withdraw->status == "processed")
                                                            <span class="tb-status text-success">{{__('processed')}}</span>
                                                        @elseif($withdraw->status == 'rejected')
                                                            <span class="tb-status text-danger">{{__('rejected')}}</span> <br>
                                                            <span  class="text-warning">{{ __('reject_reason').': ' }}{{ $withdraw->companyAccountReason->reject_reason!= '' ? __($withdraw->companyAccountReason->reject_reason): '' }}</span><br>
                                                            <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                        @elseif($withdraw->status == 'cancelled')
                                                            <span  class="text-danger">{{ __('cancelled').': ' }}</span><br>
                                                            <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                        @elseif($withdraw->status == 'pending')
                                                            <span class="tb-status text-warning">{{__('pending')}}</span> <br>
                                                        @endif
                                                </span>
                                                </div>
                                            </div><!-- data-item -->
                                            @if($withdraw->status == "processed")
                                                <div class="data-item">
                                                    <div class="data-col">
                                                        <span class="data-label">{{__('payment_from')}}</span>
                                                        @if($withdraw->companyAccount->cash_received_by != '')
                                                            <span>{{__('name')}}: {{$withdraw->companyAccount->cashCollected->first_name.' '.$withdraw->companyAccount->cashCollected->last_name}}</span><br>
                                                        @elseif($withdraw->companyAccount->account_id != '')
                                                            @if(@$withdraw->companyAccount->account->method == 'bank')
                                                                {{__(@$withdraw->companyAccount->account->method)}},
                                                                {{__('account_holder')}}: {{$withdraw->companyAccount->account->account_holder_name.' '}},
                                                                {{' '.__('account_no')}}: {{$withdraw->companyAccount->account->account_no.' '}},
                                                                {{' '.__('bank')}}: {{__($withdraw->companyAccount->account->bank_name).' '}},
                                                                {{' '.__('branch')}}:{{$withdraw->companyAccount->account->bank_branch}}({{ $withdraw->companyAccount->account->user->first_name.' '.$withdraw->companyAccount->account->user->last_name }})
                                                            @elseif(@$withdraw->companyAccount->account->method == 'cash')
                                                                {{__('cash')}} ({{ $withdraw->companyAccount->account->user->first_name.' '.$withdraw->companyAccount->account->user->last_name }})
                                                            @else
                                                                {{__(@$withdraw->companyAccount->account->method)}},
                                                                {{__('number')}}: {{@$withdraw->companyAccount->account->number.' '}},
                                                                {{__('account_type')}}: {{__(@$withdraw->companyAccount->account->type)}}({{ $withdraw->companyAccount->account->user->first_name.' '.$withdraw->companyAccount->account->user->last_name }})
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div><!-- data-item -->
                                                <div class="data-item">
                                                    <div class="data-col">
                                                        <span class="data-label">{{__('transaction_id')}}</span>
                                                        <span class="data-value">{{ $withdraw->companyAccountReason->transaction_id }}</span>
                                                    </div>
                                                </div><!-- data-item -->
                                                <div class="data-item">
                                                    <div class="data-col">
                                                        <span class="data-label">{{__('receipt')}}</span>
                                                        @if(!blank($withdraw->companyAccount->receipt) && file_exists($withdraw->companyAccount->receipt))
                                                            <span class="data-value"><a href="{{asset($withdraw->companyAccount->receipt)}}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('receipt') }}</a></span>
                                                        @else
                                                            {{ __('not_available') }}
                                                        @endif
                                                    </div>
                                                </div><!-- data-item -->
                                            @endif
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('details')}}</span>
                                                    <span class="data-value">{{ $withdraw->account->details }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                        </div><!-- data-list -->
                                    </div><!-- .nk-block -->
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

