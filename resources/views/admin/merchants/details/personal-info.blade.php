@extends('master')

@section('title')
{{__('personal_information')}}
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
                                <div class="nk-block-head nk-block-head-lg">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">{{__('personal_information')}}</h4>
                                            <div class="nk-block-des">
                                                <p>{{__('personal_info_message')}}</p>
                                            </div>
                                        </div>
                                        @if(hasPermission('merchant_update'))
                                        <div class="d-flex">
                                            <a href="{{route('merchant.edit', $merchant->id)}}"  class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-edit"></em><span>{{__('edit')}}</span></a>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="nk-data data-list">
                                        <div class="data-head">
                                            <h6 class="overline-title">{{__('basics')}}</h6>
                                        </div>
                                        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                            <div class="data-col">
                                                <span class="data-label">{{__('full_name')}}</span>
                                                <span class="data-value">{{$merchant->user->first_name.' '.$merchant->user->last_name}}</span>
                                            </div>
                                        </div><!-- data-item -->

                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('email')}}</span>
                                                <span class="data-value">{{(hasPermission('merchant_read_email'))? $merchant->user->email : Str::mask($merchant->user->email, "*", 3)}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('phone')}}</span>
                                                <span class="data-value">{{ (hasPermission('merchant_read_phone')) ? $merchant->phone_number: Str::mask($merchant->phone_number, "*", 3).Str::substr($merchant->phone_number, 0, 2) }}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('status')}}</span>
                                                <span class="data-value">
                                                    @if($merchant->user->status == 0)
                                                        <span class="tb-status text-info">{{__('inactive')}}</span>
                                                    @elseif($merchant->user->status == 1)
                                                        <span class="tb-status text-success">{{__('active')}}</span>
                                                    @else
                                                        <span class="tb-status text-danger">{{__('suspend')}}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('last_login')}}</span>
                                                <span class="data-value">{{$merchant->user->last_login != ""? date('M y, Y h:i a', strtotime($merchant->user->last_login)):''}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('trade_license')}}</span>
                                                <span class="data-value">
                                                    @if(!blank($merchant->trade_license) && file_exists($merchant->trade_license))
                                                        <a href="{{ asset($merchant->trade_license) }}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('trade_license') }}</a>
                                                    @else
                                                        {{ __('not_available') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div><!-- data-item -->
                                    </div><!-- data-list -->
                                </div><!-- .nk-block -->
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
