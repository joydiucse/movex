@extends('master')

@section('title')
    {{__('merchant').' '.__('bank_account')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">

                                <div class="card-inner card-inner-lg">
                                    <ul class="nav nav-tabs mt-n3">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tabItem1">{{__('bank_account')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tabItem2">{{__('others_account')}}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tabItem1">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">{{__('bank_information')}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>{{__('bank_info_message')}}</p>
                                                        </div>
                                                    </div>
                                                    @if(hasPermission('merchant_payment_account_update'))
                                                    <div class="d-flex">
                                                        <a href="{{route('detail.merchant.payment.bank.edit', $merchant->id)}}"  class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-edit"></em><span>{{__('edit')}}</span></a>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block">
                                                <div class="nk-data data-list">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('selected_bank')}}</span>
                                                            <span class="data-value">{{ __($payment_account->selected_bank) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('bank_branch')}}</span>
                                                            <span class="data-value">{{ __($payment_account->bank_branch) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('bank_ac_name')}}</span>
                                                            <span class="data-value">{{ __($payment_account->bank_ac_name) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('bank_ac_number')}}</span>
                                                            <span class="data-value">{{ __($payment_account->bank_ac_number) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('routing_no')}}</span>
                                                            <span class="data-value">{{ __($payment_account->routing_no) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-list -->
                                            </div><!-- .nk-block -->
                                        </div>
                                        <div class="tab-pane" id="tabItem2">

                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">{{__('others_account_information')}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>{{__('others_account_info_message')}}</p>
                                                        </div>
                                                    </div>
                                                    @if(hasPermission('merchant_payment_account_update'))
                                                    <div class="d-flex">
                                                        <a href="{{route('detail.merchant.payment.others.edit', $merchant->id)}}"  class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-edit"></em><span>{{__('edit')}}</span></a>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('bkash').' '.__('number')}}</span>
                                                            <span class="data-value">{{ $payment_account->bkash_number }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('bkash').' '.__('account_type')}}</span>
                                                            <span class="data-value">{{ __($payment_account->bkash_ac_type) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('rocket').' '.__('number')}}</span>
                                                            <span class="data-value">{{ $payment_account->rocket_number }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('rocket').' '.__('account_type')}}</span>
                                                            <span class="data-value">{{ __($payment_account->rocket_ac_type) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('nogod').' '.__('number')}}</span>
                                                            <span class="data-value">{{ $payment_account->nogod_number }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                                <div class="col-md-6">
                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                        <div class="data-col">
                                                            <span class="data-label">{{__('nogod').' '.__('account_type')}}</span>
                                                            <span class="data-value">{{ __($payment_account->nogod_ac_type) }}</span>
                                                        </div>
                                                    </div><!-- data-item -->
                                                </div><!-- data-item -->
                                            </div><!-- data-item -->
                                        </div>
                                    </div>
                                </div>

                                @include('admin.merchants.details.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
