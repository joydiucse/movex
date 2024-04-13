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

{{--        @if(\Sentinel::getUser()->bankAccount != null)--}}
{{--        <div class="card-inner">--}}
{{--            <div class="user-account-info py-0">--}}
{{--                <h6 class="overline-title-alt">{{ __('current_amount') }}</h6>--}}
{{--                <div class="user-balance {{ \Sentinel::getUser()->companyAccount->where('type', 'income')->sum('amount') + \Sentinel::getUser()->toAccount->sum('amount')  - \Sentinel::getUser()->companyAccount->where('type', 'expense')->sum('amount') - \Sentinel::getUser()->fromAccount->sum('amount')  < 0  ? 'text-danger': '' }}">{{ number_format(\Sentinel::getUser()->companyAccount->where('type', 'income')->sum('amount') + \Sentinel::getUser()->toAccount->sum('amount')  - \Sentinel::getUser()->companyAccount->where('type', 'expense')->sum('amount') - \Sentinel::getUser()->fromAccount->sum('amount') , 2) }} <small class="currency currency-btc">{{ __('tk') }}</small></div>--}}
{{--            </div>--}}
{{--        </div><!-- .card-inner -->--}}
{{--        @endif--}}

        <div class="card-inner p-0">
            <ul class="link-list-menu">
                <li><a class="{{strpos(url()->current(),'/profile')? 'active':''}}" href="{{route('staff.profile')}}"><em class="icon ni ni-user"></em><span>{{__('personal_information')}}</span></a></li>
                @if(!blank(Sentinel::getUser()->accounts(Sentinel::getUser()->id)))
                    <li><a class="{{strpos(url()->current(),'/user-accounts')? 'active':''}}" href="{{route('user.accounts')}}"><em class="icon ni ni-signout"></em><span>{{__('accounts')}}</span></a></li>
                @endif
                <li><a class="{{strpos(url()->current(),'/payment-logs')? 'active':''}}" href="{{route('staff.payment.logs')}}"><em class="icon ni ni-wallet"></em><span>{{__('payment_logs')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/account-activity')? 'active':''}}" href="{{route('staff.account-activity')}}"><em class="icon ni ni-activity-round"></em><span>{{__('login_activity')}}</span></a></li>
                <li><a class="{{strpos(url()->current(),'/security-settings')? 'active':''}}" href="{{route('staff.security-settings')}}"><em class="icon ni ni-lock-alt"></em><span>{{__('security_settings')}}</span></a></li>
                <li><a href="javascript:void(0);" onclick="logout_user_devices('/logout-other-devices', '')" id="delete-btn"><em class="icon ni ni-signout"></em> <span> {{__('logout_from_other_devices')}} </span></a></li>
            </ul>
        </div><!-- .card-inner -->
    </div><!-- .card-inner-group -->
</div><!-- card-aside -->
@include('common.script')
{{-- Update Profile modal --}}
<div class="modal fade" tabindex="-1" id="update-profile">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('update_profile')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{route('staff.update.profile')}}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" id="update-profile-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="first_name">{{ __('first_name')  }}</label>
                        <input type="text" hidden name="id" id="id" value="{{ \Sentinel::getUser()->id }}">
                        <input type="text" name="first_name" class="form-control form-control-lg" id="first_name" value="{{ \Sentinel::getUser()->first_name }}" placeholder="{{__('first_name')}}" required>
                    </div>
                    @if($errors->has('first_name'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('first_name') }}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="last_name">{{__('last_name')}}</label>
                        <input type="text" name="last_name" class="form-control form-control-lg" value="{{ \Sentinel::getUser()->last_name }}" id="last_name" placeholder="{{__('last_name')}}" required>
                    </div>
                    @if($errors->has('last_name'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('last_name') }}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="email">{{__('email')}}</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="{{ \Sentinel::getUser()->email }}" id="last_name" placeholder="{{__('email')}}" required>
                    </div>
                    @if($errors->has('email'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('email') }}</p>
                        </div>
                    @endif
                    <div class="form-group text-center mt-2">
                        @if(Sentinel::getUser()->image )
                            <img src="{{ Sentinel::getUser()->image->original_image }}" id="img_profile" class="img-thumbnail user-profile" >
                        @else
                            <img src="{{asset('admin/images/default/user.jpg') }}" id="img_profile" class="img-thumbnail user-profile">
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-06">{{__('profile_image')}}</label>
                        <div class="form-control-wrap">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input image_pick" data-image-for="profile" id="image" name="image">
                                <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                            </div>
                        </div>
                        @if($errors->has('image'))
                            <div class="nk-block-des text-danger">
                                <p>{{ $errors->first('image') }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-right mt-3">
                            <button type="submit" class="btn btn-lg btn-primary">{{__('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
