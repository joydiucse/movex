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
                                            <h4 class="nk-block-title">{{__('security_settings')}}</h4>
                                            <div class="nk-block-des">
                                                <p>{{__('security_settings_message')}}</p>
                                            </div>
                                        </div>
                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                            <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner-group">

                                            <div class="card-inner">
                                                <div class="between-center flex-wrap g-3">
                                                    <div class="nk-block-text">
                                                        <h6>{{__('change_password')}}</h6>
                                                        <p>{{__('change_password_message')}}</p>
                                                    </div>
                                                    <div class="nk-block-actions flex-shrink-sm-0">
                                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                                            <li class="order-md-last">
                                                                <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#change-password">{{__('change_password')}}</a>
                                                            </li>
                                                            <li>
                                                                <em class="text-soft text-date fs-12px">{{__('last_changed')}}: <span>{{\Sentinel::getUser()->last_password_change != ''? date('M d, Y'):''}}</span></em>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div><!-- .card-inner -->

                                        </div><!-- .card-inner-group -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div><!-- .card-inner -->

                            @include('common.profile.staff.profile-sidebar')

                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>


{{-- Change Password modal --}}
<div class="modal fade" tabindex="-1" role="dialog" id="change-password">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">{{__('change_password')}}</h5>

                <form action="{{route('staff.change-password')}}" class="form-validate" method="POST" enctype="multipart/form-data" id="change-password-form">
            		@csrf

                <div class="tab-content">
                    <div class="tab-pane active" id="personal">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="full-current_password">{{__('current_password')}}</label>
                                    <input type="password" name="current_password" class="form-control form-control-lg" id="full-current_password" placeholder="{{__('current_password')}}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="full-new_password">{{__('new_password')}}</label>
                                    <input type="password" name="new_password" class="form-control form-control-lg" id="full-new_password" placeholder="{{__('new_password')}}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="full-confirm_new_password">{{__('confirm_new_password')}}</label>
                                    <input type="password" name="confirm_new_password" class="form-control form-control-lg" id="full-confirm_new_password" placeholder="{{__('confirm_new_password')}}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                    <li>
                                        <button type="submit" class="btn btn-lg btn-primary">{{__('update')}}</button>
                                    </li>
                                    <li>
                                        <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .tab-pane -->
                </div><!-- .tab-content -->
                </form>


            </div><!-- .modal-body -->
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->
{{-- End Change Password modal --}}
@endsection

@push('script')
<script>
$(document).ready(function () {

    $('#change-password-form').on('submit', function () {

        if($('#full-new_password').val() != $('#full-confirm_new_password').val())
        {
            toastr.clear();
            NioApp.Toast('{{__("new_passwords_do_not_match.")}}', 'info', {position: 'top-right'});
            return false;
        }
    });

});
</script>
@endpush
