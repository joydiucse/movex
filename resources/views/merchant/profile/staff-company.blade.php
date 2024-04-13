@extends('master')

@section('title')
{{__('profile')}}
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
                                            <h4 class="nk-block-title">{{__('company_information')}}</h4>
                                            <div class="nk-block-des">
                                                <p>{{__('company_info_message')}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#" data-toggle="modal" data-target="#update-merchant" class="btn btn-primary d-md-inline-flex ml-2"><em class="icon ni ni-edit"></em><span>{{__('edit')}}</span></a>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->


                                <div class="nk-block">
                                    <div class="nk-data data-list">
                                        <div class="data-head">
                                            <h6 class="overline-title">{{__('basics')}}</h6>
                                        </div>
                                        <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                            <div class="data-col">
                                                <span class="data-label">{{__('company_name')}}</span>


                                                    <span class="data-value text-info">{{ $merchant->company }}</span>
                                                    @if(isset($merchant->nameChange->status) && $merchant->nameChange->status == "accept")
                                                    <a href="javascript:void" data-toggle="modal" data-target="#name-change-request"  class="badge badge-xs ml-2"><span class="p-1">{{__('change')}}</span></a>
                                                    <a href="javascript:void" data-toggle="modal" data-target="#name-change-history"    class="badge badge-xs ml-2"><span class="p-1">{{__('history')}}</span></a>

                                                    @elseif(isset($merchant->nameChange->status) && $merchant->nameChange->status == "pending" )
                                                     <a href="javascript:void"   class="badge badge-xs ml-2"><span class="p-1">{{__('request_pending')}}</span></a>

                                                     @elseif(isset($merchant->nameChange->status) && $merchant->nameChange->status == "decline")
                                                    <a href="javascript:void" data-toggle="modal" data-target="#name-change-request"  class="badge badge-xs ml-2"><span class="p-1">{{__('change')}}</span></a>
                                                    <a href="javascript:void" data-toggle="modal" data-target="#name-change-history"    class="badge badge-xs ml-2"><span class="p-1">{{__('history')}}</span></a>

                                                    @else
                                                     <a href="javascript:void" data-toggle="modal" data-target="#name-change-request"  class="badge badge-xs ml-2"><span class="p-1">{{__('change')}}</span></a>
                                                    @endif

                                            </div>
                                        </div><!-- data-item -->

                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('phone')}}</span>
                                                <span class="data-value">{{$merchant->phone_number}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('account')}}</span>
                                                <span class="data-value">
                                                    @if($merchant->status == 0)
                                                        <span class="tb-status text-info">{{__('inactive')}}</span>
                                                    @elseif($merchant->status == 1)
                                                        <span class="tb-status text-success">{{__('active')}}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('registration_status')}}</span>
                                                <span class="data-value">
                                                    @if($merchant->registration_confirmed == 0)
                                                        <span class="tb-status text-info">{{__('not_confirmed')}}</span>
                                                    @elseif($merchant->status == 1)
                                                        <span class="tb-status text-success">{{__('confirmed')}}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('website')}}</span>
                                                <a href="{{$merchant->website}}">
                                                    <span class="data-value">
                                                        <span class="tb-status text-info">{{$merchant->website ?? __('not_available')}}</span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('trade_license')}}</span>
                                                <a href="{{$merchant->trade_license}}">
                                                    <span class="data-value">
                                                        <span class="tb-status text-info">{{$merchant->trade_license ? __('trade_license'): __('not_available')}} @php if($merchant->trade_license !='') echo '<em class="icon ni ni-link-alt"></em>' @endphp</span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('nid')}}</span>
                                                <a href="{{$merchant->nid}}">
                                                    <span class="data-value">
                                                        <span class="tb-status text-info">{{$merchant->nid ? __('nid') : __('not_available')}} @php if($merchant->nid !='') echo '<em class="icon ni ni-link-alt"></em>' @endphp</span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div><!-- data-item -->
                                    </div><!-- data-list -->
                                </div><!-- .nk-block -->
                            </div>
                            @include('merchant.profile.profile-sidebar')

                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
