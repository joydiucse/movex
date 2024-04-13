<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-avatar bg-primary">
                    @if(!blank(\Sentinel::getUser()->image) && file_exists(\Sentinel::getUser()->image->image_small_two))
                        <img src="{{asset(\Sentinel::getUser()->image->image_small_two)}}" alt="{{\Sentinel::getUser()->first_name}}">
                    @else
                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{\Sentinel::getUser()->first_name}}">
                    @endif
                </div>
                <div class="user-info">
                    <span class="lead-text">{{\Sentinel::getUser()->first_name.' '.\Sentinel::getUser()->last_name}}</span>
                    <span class="sub-text">{{\Sentinel::getUser()->email}}</span>
                </div>

            </div><!-- .user-card -->
        </div><!-- .card-inner -->
        @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('manage_payment')))
            <div class="card-inner">
                <div class="user-account-info py-0">
                    <h6 class="overline-title-alt">{{ __('current_balance') }}</h6>
                    @if(Sentinel::getUser()->user_type == 'merchant')
                        <div class="user-balance {{ Sentinel::getUser()->merchant->balance(\Sentinel::getUser()->merchant->id) < 0  ? 'text-danger': '' }}">
                            {{ number_format(Sentinel::getUser()->merchant->balance(\Sentinel::getUser()->merchant->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small>
                        </div>
                    @else
                        <div class="user-balance {{ Sentinel::getUser()->balance(\Sentinel::getUser()->id) < 0  ? 'text-danger': '' }}">
                            {{ number_format(Sentinel::getUser()->balance(\Sentinel::getUser()->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small>
                        </div>
                    @endif
                </div>
            </div><!-- .card-inner -->
        @endif
        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li>
                    <a class="{{strpos(url()->current(),'merchant/profile') || strpos(url()->current(),'staff/profile') ? 'active':''}}"
                       href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.profile') : route('merchant.staff.profile') }}">
                        <em class="icon ni ni-user"></em><span>{{__('personal_information')}}</span>
                    </a>
                </li>
                @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('manage_company_information')))
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/company') || strpos(url()->current(),'staff/company') ? 'active':''}}"
                           href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.company') : route('merchant.staff.company') }}">
                            <em class="icon ni ni-cart"></em><span>{{__('company_information')}}</span>
                        </a>
                    </li>
                @endif
{{--                <li><a class="{{strpos(url()->current(),'merchant/notifications')? 'active':''}}" href="{{route('merchant.notifications')}}"><em class="icon ni ni-bell"></em><span>{{__('notifications')}}</span></a></li>--}}
                {{-- <li>
                    <a class="{{strpos(url()->current(),'merchant/account-activity') || strpos(url()->current(),'staff/account-activity')? 'active':''}}"
                       href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.account-activity') : route('merchant.staff.account-activity')}}">
                        <em class="icon ni ni-activity-round"></em><span>{{__('login_activity')}}</span>
                    </a>
                </li> --}}
                <li>
                    <a class="{{strpos(url()->current(),'merchant/security-settings') || strpos(url()->current(),'staff/security-settings')? 'active':''}}"
                       href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.security-settings') : route('merchant.staff.security-settings')}}">
                        <em class="icon ni ni-lock-alt"></em><span>{{__('password_and_security')}}</span>
                    </a>
                </li>
                @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('manage_payment_accounts')))
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/payment/accounts') || strpos(url()->current(),'staff/payment/accounts') ? 'active':''}}"
                           href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.payment.accounts') : route('merchant.staff.payment.accounts')}}">
                            <em class="icon ni ni-wallet"></em><span>{{__('availabe_payment_accounts')}}</span>
                        </a>
                    </li>
                @endif
                @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('delivery_charge')))
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/charge') || strpos(url()->current(),'staff/charge') ? 'active':''}}"
                           href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.charge') : route('merchant.staff.charge')}}">
                            <em class="icon ni ni-property-add"></em><span>{{__('delivery_charge')}}</span>
                        </a>
                    </li>
                @endif
                {{-- @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('cash_on_delivery_charge')))
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/cod-charge') || strpos(url()->current(),'staff/cod-charge') ? 'active':''}}"
                           href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.cod.charge') : route('merchant.staff.cod.charge')}}">
                            <em class="icon ni ni-property-add"></em><span>{{__('cash_on_delivery_charge')}}</span>
                        </a>
                    </li>
                @endif --}}
                @if(Sentinel::getUser()->user_type == 'merchant' || (hasPermission('manage_shops')))
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/shops') || strpos(url()->current(),'staff/shops') ? 'active':''}}"
                           href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.shops') : route('merchant.staff.shops')}}">
                            <em class="icon ni ni-home-alt"></em><span>{{__('my_shops')}}</span>
                        </a>
                    </li>
                @endif
                @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','read_merchant_api')->first()->merchant)
                    <li>
                        <a class="{{strpos(url()->current(),'merchant/api-credentials')? 'active':''}}"
                           href="{{route('merchant.api.credentials')}}">
                            <em class="icon ni ni-unlock"></em><span>{{__('api_credentials')}}</span>
                        </a>
                    </li>
                @endif
                {{-- <li>
                    <a href="javascript:void(0);" onclick="logout_user_devices('/logout-other-devices', '')" id="delete-btn">
                        <em class="icon ni ni-signout"></em> <span> {{__('logout_from_other_devices')}} </span>
                    </a>
                </li> --}}
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->

@include('merchant.profile.modals')
@include('common.script')
