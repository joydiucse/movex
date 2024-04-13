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
                                            <h4 class="nk-block-title">{{__('personal_information')}}</h4>
                                            <div class="nk-block-des">
                                                <p>{{__('personal_info_message')}}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#" data-toggle="modal" data-target="#update-profile" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-edit"></em><span>{{__('edit')}}</span></a>
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
                                                <span class="data-label">{{__('full_name')}}</span>
                                                <span class="data-value">{{\Sentinel::getUser()->first_name.' '.\Sentinel::getUser()->last_name}}</span>
                                            </div>
                                        </div><!-- data-item -->

                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('email')}}</span>
                                                <span class="data-value">{{\Sentinel::getUser()->email}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('status')}}</span>
                                                <span class="data-value">
                                                    @if(\Sentinel::getUser()->status == 0)
                                                        <span class="tb-status text-info">{{__('inactive')}}</span>
                                                    @elseif(\Sentinel::getUser()->status == 1)
                                                        <span class="tb-status text-success">{{__('active')}}</span>
                                                    @else
                                                        <span class="tb-status text-danger">{{__('suspend')}}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div><!-- data-item -->
                                        @if(isset(\Sentinel::getUser()->hub))
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">{{__('hub')}}</span>
                                                    <span class="data-value">{{\Sentinel::getUser()->hub->name.' ('.\Sentinel::getUser()->hub->address.')' }}</span>
                                                </div>
                                            </div><!-- data-item -->
                                        @endif
                                        <div class="data-item">
                                            <div class="data-col">
                                                <span class="data-label">{{__('last_login')}}</span>
                                                <span class="data-value">{{\Sentinel::getUser()->last_login != ""? date('M y, Y h:i a', strtotime(\Sentinel::getUser()->last_login)):''}}</span>
                                            </div>
                                        </div><!-- data-item -->
                                    </div><!-- data-list -->
                                </div><!-- .nk-block -->
                            </div>

                            @include('common.profile.staff.profile-sidebar')

                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
