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
                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.update.profile') : route('merchant.staff.update.profile') }}" class="form-validate is-alter" method="POST" enctype="multipart/form-data" id="update-profile-form">
                    @csrf
                        <div class="form-group">
                            <label class="form-label" for="first_name">{{ __('first_name')  }}</label>
                            <input type="text" hidden name="id" id="id" value="{{ \Sentinel::getUser()->id }}">
                            @if(Sentinel::getUser()->user_type == 'merchant_staff')
                                <input type="text" hidden name="merchant" id="merchant" value="{{ \Sentinel::getUser()->merchant_id }}">
                            @endif
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
                        @if(Sentinel::getUser()->user_type == 'merchant_staff')
                            <div class="form-group">
                                <label class="form-label" for="phone_number">{{__('phone_number')}} *</label>
                                <input type="text" name="phone_number" class="form-control form-control-lg" value="{{ \Sentinel::getUser()->phone_number }}" id="phone_number" placeholder="{{__('phone_number')}}" required>
                            </div>
                            @if($errors->has('phone_number'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('phone_number') }}</p>
                                </div>
                            @endif
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

{{-- Update Merchant Details modal --}}
<div class="modal fade" tabindex="-1" id="update-merchant">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('update_merchant_profile')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.update.merchant') : route('merchant.staff.update.merchant') }}" method="POST" enctype="multipart/form-data" id="update-merchant-form" class="form-validate is-alter">
                    @csrf
                    @php $merchant = Sentinel::getUser()->user_type == 'merchant' ? \Sentinel::getUser()->merchant : \Sentinel::getUser()->staffMerchant @endphp
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="company">{{__('company_name')}}</label>
                                <input type="text"  name="merchant" hidden id="merchant" value="{{ $merchant->id }}">
                                <input type="text" readonly name="company" class="form-control form-control-lg" value="{{ old('company') ?? $merchant->company }}" id="company" placeholder="{{__('company_name')}}" required>
                            </div>
                            @if($errors->has('company'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('company') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="phone_number">{{__('phone')}}</label>
                                <input type="text" name="phone_number" class="form-control form-control-lg" value="{{ old('phone_number') ?? $merchant->phone_number }}" id="phone_number" placeholder="{{__('phone')}}" required>
                            </div>
                            @if($errors->has('phone_number'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('phone_number') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="website">{{__('website')}}
                                    @if(!blank($merchant->website))
                                        <a href="{{ $merchant->website }}" target="_blank"> <em class="icon ni ni-link-alt"></em></a>
                                    @endif
                                </label>
                                <input type="text" name="website" class="form-control form-control-lg" value="{{ old('website') ?? $merchant->website }}" id="city" placeholder="{{__('website')}}">
                            </div>
                            @if($errors->has('website'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('website') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="city">{{__('city')}}</label>
                                <input type="text" name="city" class="form-control form-control-lg" value="{{ old('city') ?? $merchant->city }}" id="city" placeholder="{{__('city')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="address">{{__('address')}}</label>
                                <input type="text" name="address" class="form-control form-control-lg" value="{{ old('address') ?? $merchant->address }}" id="phone_number" placeholder="{{__('address')}}">
                            </div>
                            @if($errors->has('address'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('address') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="city">{{__('zip')}}</label>
                                <input type="text" name="zip" class="form-control form-control-lg" value="{{ old('zip') ?? $merchant->zip }}" id="zip" placeholder="{{__('zip')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="billing_street">{{__('billing').' '.('street')}}</label>
                                <input type="text" name="billing_street" class="form-control form-control-lg" value="{{ old('billing_street') ?? $merchant->billing_street }}" id="billing_street" placeholder="{{__('billing').' '.('street')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="billing_city">{{__('billing').' '.__('city')}}</label>
                                <input type="text" name="billing_city" class="form-control form-control-lg" value="{{ old('billing_city') ?? $merchant->billing_city }}" id="billing_city" placeholder="{{__('billing').' '.__('city')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="billing_zip">{{__('billing').' '.__('zip')}}</label>
                                <input type="text" name="billing_zip" class="form-control form-control-lg" value="{{ old('billing_zip') ?? $merchant->billing_zip }}" id="billing_zip" placeholder="{{__('billing').' '.__('zip')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="trade_license">{{__('trade_license')}}
                                    @if(!blank($merchant->trade_license) && file_exists($merchant->trade_license))
                                        <a href="{{ asset($merchant->trade_license) }}" target="_blank"> <em class="icon ni ni-link-alt"></em></a>
                                    @endif
                                </label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="trade_license" name="trade_license">
                                        <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                    </div>
                                </div>
                                @if($errors->has('trade_license'))
                                    <div class="nk-block-des text-danger">
                                        <p>{{ $errors->first('trade_license') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="default-06">{{__('nid')}}
                                    @if(!blank($merchant->nid) && file_exists($merchant->nid))
                                        <a href="{{ asset($merchant->nid) }}" target="_blank"> <em class="icon ni ni-link-alt"></em></a>
                                    @endif
                                </label>
                                <div class="form-control-wrap">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="nid" name="nid">
                                        <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                    </div>
                                </div>
                                @if($errors->has('nid'))
                                    <div class="nk-block-des text-danger">
                                        <p>{{ $errors->first('nid') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-lg btn-primary">{{__('update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- add shop modal --}}
<div class="modal fade" tabindex="-1" id="add-shop">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('add_shop')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.add.shop') : route('merchant.staff.add.shop') }}" method="POST" class="form-validate is-alter" id="add-shop-form">
                    @csrf
                    <input type="hidden" name="id" value="" id="delivery-parcel-id">
                    <div class="form-group">
                        <label class="form-label" for="shop_name">{{__('shop_name')}}</label>
                        <input type="text" name="merchant" hidden id="merchant" value="{{ $merchant->id }}">
                        <input type="text" name="shop_name" class="form-control form-control-lg" value="{{ old('shop_name') }}" id="shop_name" placeholder="{{__('shop_name')}}" required>
                    </div>
                    @if($errors->has('shop_name'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('shop_name') }}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="contact_number">{{__('contact_number')}}</label>
                        <input type="text" name="contact_number" class="form-control form-control-lg" value="{{ old('contact_number')}}" id="contact_number" placeholder="{{__('contact_number')}}" required>
                    </div>
                    @if($errors->has('contact_number'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('contact_number') }}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="shop_phone_number">{{__('pickup_number')}}</label>
                        <input type="text" name="shop_phone_number" class="form-control form-control-lg" value="{{ old('shop_phone_number')}}" id="shop_phone_number" placeholder="{{__('pickup_number')}}" required>
                    </div>
                    @if($errors->has('shop_phone_number'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('shop_phone_number') }}</p>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="address">{{__('pickup_address')}}</label>
                        <textarea name="address" class="form-control form-control-lg">{{ old('address') }}</textarea>
                    </div>
                    @if($errors->has('address'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('address') }}</p>
                        </div>
                    @endif
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--edit shop--}}
<div class="modal fade" tabindex="-1" id="edit-shop">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('edit_shop')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <div id="shop_update">

                </div>
            </div>
        </div>
    </div>
</div>

{{-- company name change request modal --}}
<div class="modal fade" tabindex="-1" id="name-change-request">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('update_merchant_name')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.name.request') : route('merchant.staff.name.request') }}" method="POST" enctype="multipart/form-data" id="update-merchant-form" class="form-validate is-alter">
                    @csrf
                    @php $merchant = Sentinel::getUser()->user_type == 'merchant' ? \Sentinel::getUser()->merchant : \Sentinel::getUser()->staffMerchant @endphp
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="company">{{__('company_name')}}</label>
                                <input type="text"  name="merchant" hidden id="merchant" value="{{ $merchant->id }}">
                                <input type="text" name="request_name" class="form-control form-control-lg" value="{{ old('company') ?? $merchant->company }}" id="company" placeholder="{{__('company_name')}}" required>
                            </div>
                            @if($errors->has('company'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('company') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-lg btn-primary">{{__('Request')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- company name change history modal --}}
<div class="modal fade" tabindex="-1" id="name-change-history">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('name_change_request')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                @php $merchant = Sentinel::getUser()->user_type == 'merchant' ? \Sentinel::getUser()->merchant : \Sentinel::getUser()->staffMerchant @endphp
                <div class="nk-tb-list nk-tb-ulist">
                    <div class="nk-tb-item nk-tb-head">
                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_for')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('previous_name')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_name')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_by')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('process_by')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_date')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('process_date')}}</strong></span></div>
                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>


                    </div><!-- .nk-tb-item -->
                    {{--$merchant->merchantnamechanges--}}
                    @foreach($merchant->merchantnamechanges as $key => $name)
                    <div class="nk-tb-item">
                        <div class="nk-tb-col">
                            <span>{{ ++ $key }}</span>
                        </div>
                        <div class="nk-tb-col">
                            <span>{{ $name->merchant->company }}</span>
                        </div>

                        <div class="nk-tb-col">
                            <span class="text-primary">{{__($name->type) }}</span>
                        </div>
                        <div class="nk-tb-col">
                            <span class="text-capitalize text-info"><strong>{{__($name->old_name) }}</strong></span>
                        </div>

                        <div class="nk-tb-col">
                            <span class="text-capitalize text-danger"><strong>{{__($name->request_name) }}</strong></span>
                        </div>

                        <div class="nk-tb-col">
                            <span>{{ ($name->request_id) ? $name->createuser->first_name." ".$name->createuser->last_name: '-' }}</span>
                        </div>

                        <div class="nk-tb-col">
                            <span>{{ ($name->process_id) ? $name->processuser->first_name." ".$name->processuser->last_name: '-' }}</span>
                        </div>

                        <div class="nk-tb-col">
                            <span>{{ ($name->created_at) ? $name->created_at->format('Y-m-d'): '-' }}</span>
                        </div>
                        <div class="nk-tb-col">
                            <span>{{ ($name->updated_at) ? $name->updated_at->format('Y-m-d') : '-' }}</span>
                        </div>
                        <div class="nk-tb-col">
                            @if($name->status =='accept')
                                <span class="text-info text-success"><strong>{{ __($name->status) }}</strong></span>
                            @elseif($name->status =='pending')
                                <span class="text-info text-warning"><strong>{{ __($name->status) }}</strong></span>
                            @else
                                <span class="text-info text-danger"><strong>{{ __($name->status) }}</strong></span>
                            @endif
                        </div>
                    </div><!-- .nk-tb-item -->
                    @endforeach


                </div>
            </div>
        </div>
    </div>
</div>


{{-- shop name change request modal --}}
<div class="modal fade" tabindex="-1" id="shop-name-request">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('shop-name_edit_request')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <div id="shop_info">

                </div>
            </div>
        </div>
    </div>
</div>

{{-- shop name change history modal --}}
<div class="modal fade" tabindex="-1" id="shop-name-history">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('changes_history')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <div id="shop_history">

                </div>
            </div>
        </div>
    </div>
</div>

{{-- profile deletion confirmation modal --}}
<div class="modal fade" tabindex="-1" id="confirmDeleteModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Confirm Deletion')}}</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <div class="text-danger">
                <p>All data will be deleted.You will loss account access and data forverver!!</p>
                    {{ __('Are you sure you want to delete this user?') }}
                </div>
            </div>
            <div class="modal-footer">
                
                <a class="btn btn-danger" href="{{ route('merchant.profile.delete',Sentinel::getUser()->id) }}">{{ __('Delete') }}</a>
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
              </div>
        </div>
    </div>
</div>




@push('script')
    <script type="text/javascript">
        $('.shop-update').on('click', function (e) {
            e.preventDefault();
            var route = $(this).attr('data-url');
            var url = "{{url('')}}"+route;
            var shop_id = $(this).attr('data-id');

            var formData = {
                shop_id : shop_id
            }
            $.ajax({
                type: "GET",
                dataType: 'html',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function (data) {
                    $('#shop_update').html(data);
                },
                error: function (data) {
                }
            });
        });

        //shop name change request
        $('.shop-name-request').on('click', function (e) {
            e.preventDefault();
            var route = $(this).attr('data-url');
            var url = "{{url('')}}"+route;
            var shop_id = $(this).attr('data-id');

            var formData = {
                shop_id : shop_id
            }
            $.ajax({
                type: "GET",
                dataType: 'html',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function (data) {
                    $('#shop_info').html(data);
                    //console.log(data)
                },
                error: function (data) {
                }
            });
        });

        //name change history
        //shop name change request
        $('.shop-name-histry').on('click', function (e) {
            e.preventDefault();
            var route = $(this).attr('data-url');
            var url = "{{url('')}}"+route;
            var shop_id = $(this).attr('data-id');

            var formData = {
                shop_id : shop_id
            }
            $.ajax({
                type: "GET",
                dataType: 'html',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function (data) {
                    $('#shop_history').html(data);
                    //console.log(data)
                },
                error: function (data) {
                }
            });
        });



    </script>
@endpush

