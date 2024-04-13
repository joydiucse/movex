@extends('master')

@section('title')
    {{__('edit').' '.__('delivery_man')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit')}} {{__('delivery_man')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @include('partials.message')
                    <form action="{{ route('delivery.man.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$delivery_man->id}}" name="id">
                        <input type="hidden" value="{{$delivery_man->user_id}}" name="user_id">
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('first_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" name="first_name" value="{{old('first_name') != ''? old('first_name'): $delivery_man->user->first_name}}" required>
                                                    </div>
                                                    @if($errors->has('first_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('first_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('last_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" name="last_name" value="{{old('last_name') != ''? old('last_name'): $delivery_man->user->last_name}}" required>
                                                    </div>
                                                    @if($errors->has('last_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('last_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-email">{{__('email')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="fv-email" name="email" value="{{old('email') != ''? old('email'): $delivery_man->user->email}}" required>
                                                    </div>
                                                    @if($errors->has('email'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('email') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-email">{{__('password')}}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="password" class="form-control" id="fv-email" name="password" value="{{old('password')}}">
                                                    </div>
                                                    @if($errors->has('password'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('password') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone_number">{{ __('phone_number') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{old('phone_number') != ''? old('phone_number'): $delivery_man->phone_number}}" required>
                                                    </div>
                                                </div>
                                                @if($errors->has('phone_number'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('phone_number') }}</p>
                                                        </div>
                                                    @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="zip">{{ __('zip') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="zip" value="{{old('zip') != ''? old('zip'): $delivery_man->zip}}" name="zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="address">{{ __('address') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="address" value="{{old('address') != ''? old('address'): $delivery_man->address}}" name="address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="city">{{ __('city') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="city" value="{{old('city') != ''? old('city'): $delivery_man->city}}" name="city">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="hub">{{__('hub')}} </label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" id="hub" name="hub">
                                                            <option value="">{{ __('select_hub') }}</option>
                                                            @foreach($hubs as $hub)
                                                                <option value="{{ $hub->id }}" {{ $hub->id == $delivery_man->user->hub_id ? 'selected' : '' }}>{{ __($hub->name).' ('.$hub->address.')' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($errors->has('hub'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('hub') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="pick_up_fee">{{ __('pick_up_fee') }} *</label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('update_delivery_man_commission'))
                                                             <input type="text" class="form-control" id="pick_up_fee" value="{{old('pick_up_fee') != ''? old('pick_up_fee'): $delivery_man->pick_up_fee}}" name="pick_up_fee" required>
                                                        @else
                                                             <input readonly type="text" class="form-control" id="pick_up_fee" value="{{old('pick_up_fee') != ''? old('pick_up_fee'): $delivery_man->pick_up_fee}}" name="pick_up_fee" required>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="delivery_fee">{{ __('delivery_fee') }} *</label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('update_delivery_man_commission'))
                                                        <input type="text" class="form-control" id="delivery_fee" value="{{old('delivery_fee') != ''? old('delivery_fee'): $delivery_man->delivery_fee}}" name="delivery_fee" required>
                                                        @else
                                                        <input readonly type="text" class="form-control" id="delivery_fee" value="{{old('delivery_fee') != ''? old('delivery_fee'): $delivery_man->delivery_fee}}" name="delivery_fee">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="return_fee">{{ __('return_fee') }} *</label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('update_delivery_man_commission'))
                                                            <input type="number" class="form-control" id="return_fee" value="{{old('return_fee') != ''? old('return_fee'): $delivery_man->return_fee}}" name="return_fee" required>
                                                        @else
                                                        <input readonly type="number" class="form-control" id="return_fee" value="{{old('return_fee') != ''? old('return_fee'): $delivery_man->return_fee}}" name="return_fee" required>
                                                        @endif
                                                    </div>
                                                    @if($errors->has('return_fee'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('return_fee') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="opening_balance">{{ __('opening_balance') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="opening_balance" value="{{old('opening_balance') != ''? old('opening_balance'): @$delivery_man->companyAccount->amount}}" name="opening_balance" required>
                                                    </div>
                                                    @if($errors->has('opening_balance'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('opening_balance') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="driving_license">{{__('driving_license')}}</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="driving_license" name="driving_license">
                                                            <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('driving_license'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('driving_license') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-4 text-center">
                                                    @if($delivery_man->user->image != null)
                                                        <img src="{{ asset($delivery_man->user->image->original_image)}}" id="img_profile" class="img-thumbnail user-profile" >
                                                    @else
                                                        <img src="{{asset('admin/images/default/user.jpg') }}" id="img_profile" class="img-thumbnail user-profile">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
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
                                            @if(hasPermission('sip_read'))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="dial_permission">{{__('dial_permission')}} </label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('sip_update'))
                                                        <select class="form-control" name="dial_permission"  id="dial_permission">
                                                            <option {{ ($delivery_man->dial_enable == 1) ? 'selected' : '' }} value="1">Yes</option>
                                                            <option {{ ($delivery_man->dial_enable == 0) ? 'selected' : '' }} value="0">No</option>
                                                        </select>
                                                        @else
                                                        <input type="text" readonly class="form-control"  value="{{ ($delivery_man->dial_enable == 1) ? 'Yes' : 'No' }}">
                                                        @endif
                                                    </div>
                                                    @if($errors->has('dial_permission'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('dial_permission') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @if(hasPermission('sip_read'))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-5">
                                                    <label class="form-label" for="sip_extension">{{__('sip_extension')}} </label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('sip_update'))
                                                        <input type="text" class="form-control" id="sip_extension" name="sip_extension"  value="{{$delivery_man->sip_extension}}">
                                                        @else
                                                        <input type="text" readonly class="form-control"  value="{{$delivery_man->sip_extension}}">
                                                        @endif
                                                    </div>
                                                    @if($errors->has('sip_extension'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('sip_extension') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mt-5">
                                                    <label class="form-label" for="sip_password">{{__('sip_password')}} </label>
                                                    <div class="form-control-wrap">
                                                        @if(hasPermission('sip_update'))
                                                        <input type="text" class="form-control" id="sip_password" name="sip_password" placeholder="SIP Password" value="{{$delivery_man->sip_password}}" >
                                                        @else
                                                        <input type="text" readonly class="form-control"   value="{{$delivery_man->sip_password}}" >
                                                        @endif
                                                    </div>
                                                    @if($errors->has('sip_password'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('sip_password') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12 text-right mt-5">
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
    @include('admin.roles.script')
@endsection

