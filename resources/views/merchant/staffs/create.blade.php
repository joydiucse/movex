@extends('master')

@section('title')
    {{__('add')}} {{__('staff')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('add')}} {{__('staff')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('merchant.staff.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('first_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" name="first_name" required value="{{old('first_name')}}">
                                                    </div>
                                                    @if($errors->has('first_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('first_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('last_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" name="last_name" value="{{old('last_name')}}">
                                                        <input type="hidden" class="form-control" name="merchant" value="{{ \Sentinel::getUser()->merchant->id }}">
                                                    </div>
                                                    @if($errors->has('last_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('last_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone_number">{{__('phone_number')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone_number" name="phone_number" required value="{{old('phone_number')}}">
                                                    </div>
                                                    @if($errors->has('phone_number'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('phone_number') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-email">{{__('email')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="fv-email" name="email" required value="{{old('email')}}">
                                                    </div>
                                                    @if($errors->has('email'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('email') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-email">{{__('password')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="password" class="form-control" id="fv-email" name="password" required value="{{old('password')}}">
                                                    </div>
                                                    @if($errors->has('password'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('password') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <img src="{{asset('admin/images/default/user.jpg') }}"   id="img_profile" class="img-thumbnail user-profile ">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="default-06">{{__('profile_image')}}</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input image_pick" data-image-for="profile" id="customFile" name="image">
                                                            <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('image'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('image') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-inner">
                                        <table class="table table-striped role-create-table role-permission" id="permissions-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{__('modules')}}</th>
                                                    <th scope="col">{{__('permission')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><span class="text-capitalize">{{__('parcel')}}</span></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'manage_parcel'}}" name="permissions[]" value="manage_parcel">
                                                            <label class="custom-control-label" for="manage_parcel">{{__('allow')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'all_parcel'}}" name="permissions[]" value="all_parcel">
                                                            <label class="custom-control-label" for="all_parcel">{{__('allow_all')}}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text-capitalize">{{__('payment')}}</span></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'manage_payment'}}" name="permissions[]" value="manage_payment">
                                                            <label class="custom-control-label" for="manage_payment">{{__('allow')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'all_parcel_payment'}}" name="permissions[]" value="all_parcel_payment">
                                                            <label class="custom-control-label" for="all_parcel_payment">{{__('allow_all')}}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text-capitalize">{{__('logs')}}</span></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'read_logs'}}" name="permissions[]" value="read_logs">
                                                            <label class="custom-control-label" for="read_logs">{{ __('read_logs') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'all_parcel_logs'}}" name="permissions[]" value="all_parcel_logs">
                                                            <label class="custom-control-label" for="all_parcel_logs">{{ __('all_parcel_logs') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read common-key" id="{{'all_payment_logs'}}" name="permissions[]" value="all_payment_logs">
                                                            <label class="custom-control-label" for="all_payment_logs">{{ __('all_payment_logs') }}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text-capitalize">{{__('others_access')}}</span></td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read" id="{{'manage_company_information'}}" name="permissions[]" value="manage_company_information">
                                                            <label class="custom-control-label" for="manage_company_information">{{ __('manage_company_information') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read" id="{{'manage_payment_accounts'}}" name="permissions[]" value="manage_payment_accounts">
                                                            <label class="custom-control-label" for="manage_payment_accounts">{{ __('manage_payment_accounts') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read" id="{{'manage_shops'}}" name="permissions[]" value="manage_shops">
                                                            <label class="custom-control-label" for="manage_shops">{{ __('manage_shops') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read" id="{{'delivery_charge'}}" name="permissions[]" value="delivery_charge">
                                                            <label class="custom-control-label" for="delivery_charge">{{ __('delivery_charge') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input read" id="{{'cash_on_delivery_charge'}}" name="permissions[]" value="cash_on_delivery_charge">
                                                            <label class="custom-control-label" for="cash_on_delivery_charge">{{ __('cash_on_delivery_charge') }}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text-capitalize">{{__('shops_access')}}</span></td>
                                                    <td>
                                                        @foreach(Sentinel::getUser()->merchant->shops as $shop)
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input read" id="shop-{{ $shop->id }}" name="shops[]" required value="{{ $shop->id }}">
                                                                <label class="custom-control-label" for="shop-{{ $shop->id }}">{{ $shop->shop_name.' ('.$shop->address.')' }}</label>
                                                            </div>
                                                        @endforeach
                                                        @if($errors->has('shops'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('shops') }}</p>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-12 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.roles.script')
