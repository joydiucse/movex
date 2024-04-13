<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-info">
                    <span class="lead-text">{{ __('settings') }}</span>
                </div>
            </div><!-- .user-card -->
        </div><!-- .card-inner -->
        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li><a class="{{strpos(url()->current(),'/sms-setting')? 'active':''}}" href="{{route('sms.setting')}}"><em class="icon ni ni-msg"></em><span>{{__('sms')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/preference-setting')? 'active':''}}" href="{{route('preference.setting')}}"><em class="icon ni ni-property"></em><span>{{__('preference')}}</span></a></li>
{{--                <li><a class="{{strpos(url()->current(),'/pagination-setting')? 'active':''}}" href="{{route('pagination.setting')}}"><em class="icon ni ni-view-list-fill"></em><span>{{__('pagination')}}</span></a></li>--}}
                <li><a class="{{strpos(url()->current(),'/charges-setting')? 'active':''}}" href="{{route('charges.setting')}}"><em class="icon ni ni-sign-mxn"></em><span>{{__('charges')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/packaging-charge-setting')? 'active':''}}" href="{{route('packaging.charge.setting')}}"><em class="icon ni ni-sign-mxn"></em><span>{{__('packaging_type_and_charges')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/time-and-days-setting')? 'active':''}}" href="{{route('time-and-days.setting')}}"><em class="icon ni ni-clock"></em><span>{{__('pickup_time_delivery_days')}}</span></a></li>
{{--                <li><a class="{{strpos(url()->current(),'/mobile-app-setting')? 'active':''}}" href="{{route('mobile.app.setting')}}"><em class="icon ni ni-mobile"></em><span>{{__('mobile_app')}}</span></a></li>--}}
{{--                <li><a class="{{strpos(url()->current(),'/database-backup-storage-setting')? 'active':''}}" href="{{route('database.backup.storage.setting')}}"><em class="icon ni ni-db"></em><span>{{__('database_backup_storage')}}</span></a></li>--}}
                <li><a class="{{strpos(url()->current(),'/sip-domain-setting')? 'active':''}}" href="{{route('sip_domain.setting')}}"><em class="icon ni ni-check"></em><span>{{__('sip_domain')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/app-info-setting')? 'active':''}}" href="{{route('app_info.setting')}}"><em class="icon ni ni-info-i"></em><span>{{__('app_info')}}</span></a></li>

            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->
