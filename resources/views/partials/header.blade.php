<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{route('dashboard')}}" class="logo-link">
                    <img class="logo-light logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo.png" srcset="{{ asset('admin/')}}/images/logo2x.png 2x" alt="logo">
                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo-dark.png" srcset="{{ asset('admin/')}}/images/logo-dark2x.png 2x" alt="logo-GreenX">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown language-dropdown">
                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-name dropdown-indicator">{{ LaravelLocalization::getCurrentLocale() == 'en'? __('english'):__('bangla') }} </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{url('/').'/en/'.str_replace("bn/","",Request::path())}}"><span>{{__('english')}}</span></a></li>
                                    @if(\Sentinel::getUser()->user_type == 'staff')
                                    <li><a href="{{url('/').'/bn/'.str_replace("bn/","",Request::path())}}"><span>{{__('bangla')}}</span></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </li>
{{--                    @if(\Sentinel::getUser()->user_type == 'staff')--}}
{{--                    <li class="dropdown notification-dropdown">--}}
{{--                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">--}}
{{--                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>--}}
{{--                        </a>--}}
{{--                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">--}}
{{--                            <div class="dropdown-head">--}}
{{--                                <span class="sub-title nk-dropdown-title">Notifications</span>--}}
{{--                                <a href="#">Mark All as Read</a>--}}
{{--                            </div>--}}
{{--                            <div class="dropdown-body">--}}
{{--                                <div class="nk-notification">--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="nk-notification-item dropdown-inner">--}}
{{--                                        <div class="nk-notification-icon">--}}
{{--                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>--}}
{{--                                        </div>--}}
{{--                                        <div class="nk-notification-content">--}}
{{--                                            <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>--}}
{{--                                            <div class="nk-notification-time">2 hrs ago</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div><!-- .nk-notification -->--}}
{{--                            </div><!-- .nk-dropdown-body -->--}}
{{--                            <div class="dropdown-foot center">--}}
{{--                                <a href="#">View All</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    @endif--}}
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    @if(!blank(Sentinel::getUser()->image) && file_exists(Sentinel::getUser()->image->image_small_one))
                                        <img src="{{asset(Sentinel::getUser()->image->image_small_one)}}" alt="{{Sentinel::getUser()->first_name}}">
                                    @else
                                        <img src="{{asset('admin/images/default/user32x32.jpg')}}" alt="{{Sentinel::getUser()->first_name}}">
                                    @endif
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-name dropdown-indicator">{{Sentinel::getUser()->first_name.' '.Sentinel::getUser()->last_name}}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        @if(!blank(Sentinel::getUser()->image) && file_exists(Sentinel::getUser()->image->image_small_two))
                                        <img src="{{asset(Sentinel::getUser()->image->image_small_two)}}" alt="{{Sentinel::getUser()->first_name}}">
                                    @else
                                        <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{Sentinel::getUser()->first_name}}">
                                    @endif
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{Sentinel::getUser()->first_name.' '.Sentinel::getUser()->last_name}}</span>
                                        <span class="sub-text">{{ Sentinel::getUser()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.profile') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.profile') : route('staff.profile'))}}"><em class="icon ni ni-user-alt"></em><span>{{__('profile')}}</span></a></li>
                                    <li><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.security-settings') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.security-settings') : route('staff.security-settings'))}}"><em class="icon ni ni-setting-alt"></em><span>{{__('security_settings')}}</span></a></li>
                                    @if(Sentinel::getUser()->user_type == 'merchant' || hasPermission('manage_payment_accounts'))
                                        <li><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.payment.accounts') : route('merchant.staff.payment.accounts') }}"><em class="icon ni ni-wallet"></em><span>{{__('payment_accounts')}}</span></a></li>
                                    @endif
                                    @if(\Sentinel::getUser()->user_type == 'merchant' || hasPermission('read_logs'))
                                        <li><a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.statements') : route('merchant.staff.statements')}}"><em class="icon ni ni-line-chart"></em><span>{{__('payment_logs')}}</span></a></li>
                                    @endif
                                    <li><a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.account-activity') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.account-activity') : route('staff.account-activity'))}}"><em class="icon ni ni-activity-alt"></em><span>{{__('login_activity')}}</span></a></li>
                                    <li><a class="dark-switch {{defaultModeCheck() == 'dark-mode'? 'active':''}}" href="#" id="switch-mode"><em class="icon ni ni-moon"></em><span>{{ __('dark_mode') }}</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.logout') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.logout'): route('logout') )}}"><em class="icon ni ni-signout"></em><span>{{ __('logout') }}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
