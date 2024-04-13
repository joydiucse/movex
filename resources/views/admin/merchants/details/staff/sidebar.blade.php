<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-avatar bg-primary">
                    @if(($staff->image) && file_exists($staff->image->image_small_two))
                        <img src="{{asset($staff->image->image_small_two)}}" alt="{{$staff->first_name}}">
                    @else
                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$staff->first_name}}">
                    @endif
                </div>
                <div class="user-info">
                    <span class="lead-text">{{$staff->first_name.' '.$staff->last_name}}</span>
                    <span class="sub-text">{{$staff->email}}</span>
                </div>

            </div><!-- .user-card -->
        </div><!-- .card-inner -->
        <div class="card-inner">
            <div class="user-account-info py-0">
                <h6 class="overline-title-alt">{{ __('current_balance') }}</h6>
                <div class="user-balance {{ $staff->balance($staff->id) < 0  ? 'text-danger': '' }}">
                    {{ number_format($staff->balance($staff->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small>
                </div>
            </div>
        </div><!-- .card-inner -->
        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li>
                    <a class="{{strpos(url()->current(),'merchant-staff-personal-info') ? 'active':''}}"
                       href="{{route('detail.merchant.staff.personal.info', $staff->id)}}">
                        <em class="icon ni ni-user"></em><span>{{__('personal_information')}}</span>
                    </a>
                </li>
                <li>
                    <a class="{{strpos(url()->current(),'merchant-staff-account-activity') ? 'active':''}}"
                       href="{{ route('detail.merchant.staffs.account-activity', $staff->id) }}">
                        <em class="icon ni ni-activity-round"></em><span>{{__('login_activity')}}</span>
                    </a>
                </li>
                @if(hasPermission('user_logout_from_devices'))
                    <li>
                        <a href="javascript:void(0);" onclick="logout_user_devices('logout-user-all-devices/',{{$staff->id}})" id="delete-btn">
                            <em class="icon ni ni-signout"></em> <span> {{__('logout_from_all_devices')}} </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->

@include('common.script')
