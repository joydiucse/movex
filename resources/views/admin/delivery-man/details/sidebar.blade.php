<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-avatar bg-primary">
                    @if(!blank($delivery_man->user->image) && file_exists($delivery_man->user->image->image_small_two))
                        <img src="{{asset($delivery_man->user->image->image_small_two)}}" alt="{{$delivery_man->user->first_name}}">
                    @else
                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$delivery_man->user->first_name}}">
                    @endif
                </div>
                <div class="user-info">
                    <span class="lead-text">{{$delivery_man->user->first_name.' '.$delivery_man->user->last_name}}</span>
                    <span class="sub-text">{{$delivery_man->user->email}}</span>
                </div>
            </div><!-- .user-card -->
        </div><!-- .card-inner -->
        <div class="card-inner">
            <div class="user-account-info py-0">
                <h6 class="overline-title-alt">{{ __('current_amount') }}</h6>
                <div class="user-balance {{ $delivery_man->balance($delivery_man->id) < 0  ? 'text-danger': '' }}">{{ number_format($delivery_man->balance($delivery_man->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small></div>
            </div>
        </div><!-- .card-inner -->
        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li><a class="{{strpos(url()->current(),'/delivery-man-personal-info')? 'active':''}}" href="{{route('detail.delivery.man.personal.info', $delivery_man->id)}}"><em class="icon ni ni-user"></em><span>{{__('personal_information')}}</span></a></li>
                @if(hasPermission('deliveryman_account_activity_read'))
                    <li><a class="{{strpos(url()->current(),'/delivery-man-account-activity')? 'active':''}}" href="{{route('detail.delivery.man.account-activity', $delivery_man->id)}}"><em class="icon ni ni-activity-round"></em><span>{{__('login_activity')}}</span></a></li>
                @endif
                @if(hasPermission('deliveryman_payment_logs_read'))
                    <li><a class="{{strpos(url()->current(),'/delivery-man-statements')? 'active':''}}" href="{{route('detail.delivery.man.statements', $delivery_man->id)}}"><em class="icon ni ni-wallet-out"></em><span>{{__('payment_logs')}}</span></a></li>
                @endif
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->
