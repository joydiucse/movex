<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-avatar bg-primary">
                    @if(!blank($merchant->user->image) && file_exists($merchant->user->image->image_small_two))
                        <img src="{{asset($merchant->user->image->image_small_two)}}" alt="{{$merchant->user->first_name}}">
                    @else
                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$merchant->user->first_name}}">
                    @endif
                </div>
                <div class="user-info">
                    <span class="lead-text">{{$merchant->user->first_name.' '.$merchant->user->last_name}}</span>
                    <span class="sub-text">{{$merchant->user->email}}</span>
                </div>
            </div><!-- .user-card -->
        </div><!-- .card-inner -->
        <div class="card-inner">
            <div class="user-account-info py-0">
                <h6 class="overline-title-alt">{{ __('current_payable_amount') }}</h6>
                <div class="user-balance {{ $merchant->balance($merchant->id) < 0  ? 'text-danger': '' }}">{{ number_format($merchant->balance($merchant->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small></div>
            </div>
        </div><!-- .card-inner -->
        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li><a class="{{strpos(url()->current(),'/merchant-personal-info')? 'active':''}}" href="{{route('detail.merchant.personal.info', $merchant->id)}}"><em class="icon ni ni-user"></em><span>{{__('personal_information')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/merchant-company')? 'active':''}}" href="{{route('detail.merchant.company', $merchant->id)}}"><em class="icon ni ni-cart"></em><span>{{__('company_information')}}</span></a></li>
                @if(hasPermission('merchant_staff_read'))
                <li><a class="{{strpos(url()->current(),'/merchant-staffs')? 'active':''}}" href="{{route('detail.merchant.staffs', $merchant->id)}}"><em class="icon ni ni-users"></em><span>{{__('staffs')}}</span></a></li>
                @endif
                @if(hasPermission('merchant_account_activity_read'))
                <li><a class="{{strpos(url()->current(),'/merchant-account-activity')? 'active':''}}" href="{{route('detail.merchant.account-activity', $merchant->id)}}"><em class="icon ni ni-activity-round"></em><span>{{__('login_activity')}}</span></a></li>
                @endif
                @if(hasPermission('merchant_payment_account_read'))
                <li><a class="{{strpos(url()->current(),'/merchant-payment-accounts')? 'active':''}}" href="{{route('detail.merchant.payment.accounts', $merchant->id)}}"><em class="icon ni ni-wallet"></em><span>{{__('availabe_payment_accounts')}}</span></a></li>
                @endif
                @if(hasPermission('merchant_charge_read'))
                <li><a class="{{strpos(url()->current(),'/merchant-charge')? 'active':''}}" href="{{route('detail.merchant.charge', $merchant->id)}}"><em class="icon ni ni-property-add"></em><span>{{__('delivery_charge')}}</span></a></li>
                @endif
                {{-- @if(hasPermission('merchant_cod_charge_read'))
                    <li><a class="{{strpos(url()->current(),'/merchant-cod-charge')? 'active':''}}" href="{{route('detail.merchant.cod.charge', $merchant->id)}}"><em class="icon ni ni-property-add"></em><span>{{__('cash_on_delivery_charge')}}</span></a></li>
                @endif --}}
                @if(hasPermission('merchant_payment_logs_read'))
                    <li><a class="{{strpos(url()->current(),'/merchant-statements')? 'active':''}}" href="{{route('detail.merchant.statements', $merchant->id)}}"><em class="icon ni ni-wallet-out"></em><span>{{__('payment_logs')}}</span></a></li>
                @endif
                @if(hasPermission('merchant_shop_read'))
                    <li><a class="{{strpos(url()->current(),'/merchant-shops')? 'active':''}}" href="{{route('detail.merchant.shops', $merchant->id)}}"><em class="icon ni ni-home-alt"></em><span>{{__('shops')}}</span></a></li>
                @endif
                @if(hasPermission('merchant_api_credentials_read'))
                    <li><a class="{{strpos(url()->current(),'/merchant-permissions')? 'active':''}}" href="{{route('detail.merchant.permissions', $merchant->id)}}"><em class="icon ni ni-check-round-cut"></em><span>{{__('permissions')}}</span></a></li>
                @endif
                {{-- @if(hasPermission('merchant_api_credentials_read'))
                    <li><a class="{{strpos(url()->current(),'/merchant-api-credentials')? 'active':''}}" href="{{route('detail.merchant.api.credentials', $merchant->id)}}"><em class="icon ni ni-unlock"></em><span>{{__('api_credentials')}}</span></a></li>
                @endif --}}
                {{-- @if(hasPermission('user_logout_from_devices'))
                    <li><a href="javascript:void(0);" onclick="logout_user_devices('logout-user-all-devices/', {{$merchant->user->id}})" id="delete-btn"><em class="icon ni ni-signout"></em> <span> {{__('logout_from_all_devices')}} </span></a></li>
                @endif --}}
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->
@include('common.script')
