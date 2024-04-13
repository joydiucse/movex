@extends('master')

@section('title')
{{__('login_activity')}}
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
                                            <h4 class="nk-block-title">{{__('login_activity')}}</h4>
                                            <div class="nk-block-des">
                                                <p>{{__('here_is_login_activity')}}. <span class="text-soft"><em class="icon ni ni-info"></em></span></p>
                                            </div>
                                        </div>
                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block card">
                                    <table class="table table-ulogs">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="tb-col-os"><span class="overline-title">{{__('browser')}}</th>
                                                <th class="tb-col-ip"><span class="overline-title">{{__('platform')}}</span></th>
                                                <th class="tb-col-time"><span class="d-none d-sm-inline-block overline-title">{{__('ip')}}</span></th>
                                                <th class="tb-col-action"><span class="overline-title">{{__('time')}}</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($login_activities as $login_activity)
                                                <tr>
                                                    <td class="tb-col-os">{{$login_activity->browser}}</td>
                                                    <td class="tb-col-ip"><span class="sub-text">{{$login_activity->platform}}</span></td>
                                                    <td class="tb-col-time"><span class="d-none d-sm-inline-block">{{$login_activity->ip}}</span></td>
                                                    <td class="tb-col-action">{{$login_activity->created_at != ""? date('M d, Y h:i a', strtotime($login_activity->created_at)):''}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div><!-- .nk-block-head -->
                            </div><!-- .card-inner -->

                            @include('admin.merchants.details.staff.sidebar')

                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
