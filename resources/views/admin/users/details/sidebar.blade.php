<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar>
        <div class="card-inner">
            <div class="user-card">
                <div class="user-avatar bg-primary">
                    @if(!blank($user->image) && file_exists($user->image->image_small_two))
                        <img src="{{asset($user->image->image_small_two)}}" alt="{{$user->first_name}}">
                    @else
                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$user->first_name}}">
                    @endif
                </div>
                <div class="user-info">
                    <span class="lead-text">{{$user->first_name.' '.$user->last_name}}</span>
                    <span class="sub-text">{{$user->email}}</span>
                </div>
                <div class="user-action">
                    <div class="dropdown">
                        <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                @if(hasPermission('user_update'))
                                <li><a href="{{route('user.edit', $user->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- .user-card -->
        </div><!-- .card-inner -->

        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li><a class="{{strpos(url()->current(),'/staff-personal-info')? 'active':''}}" href="{{route('detail.staff.personal.info', $user->id)}}"><em class="icon ni ni-user-fill-c"></em><span>{{__('personal_information')}}</span></a></li>
                @if(hasPermission('account_read') && !blank($user->accounts($user->id)))
                    <li><a class="{{strpos(url()->current(),'/staff-accounts')? 'active':''}}" href="{{route('staff.accounts', $user->id)}}"><em class="icon ni ni-wallet"></em><span>{{__('accounts')}}</span></a></li>
                @endif
                @if(hasPermission('user_account_activity_read'))
                    <li><a class="{{strpos(url()->current(),'/staff-account-activity')? 'active':''}}" href="{{route('detail.staff.account-activity', $user->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>{{__('login_activity')}}</span></a></li>
                @endif
                @if(hasPermission('user_logout_from_devices'))
                    <li><a href="javascript:void(0);" onclick="logout_user_devices('logout-user-all-devices/', {{$user->id}})" id="delete-btn"><em class="icon ni ni-signout"></em> <span> {{__('logout_from_all_devices')}} </span></a></li>
                @endif
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->
@include('common.script')
