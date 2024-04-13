@extends('master')

@section('title')
    {{__('shops').' '.__('list')}}
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
                                                <h4 class="nk-block-title">{{__('shops')}}</h4>
                                                <div class="nk-block-des">
                                                </div>
                                            </div>
                                            @if(hasPermission('merchant_shop_create'))
                                            <div class="d-flex">
                                                <a href="" data-toggle="modal" data-target="#add-shop" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block card">

                                        <div class="row g-gs">
                                            @foreach($shops as $key => $shop)
                                            <div class="col-lg-6">

                                                <a href="javascript:void"  class="card card-bordered text-soft shop-update">
                                                    <div class="card-inner">
                                                      <div class="align-center justify-between">
                                                        <div class="g">
                                                              <table>
                                                                  <tbody>
                                                                      <tr id="row_{{$shop->id}}" class="shops-profile">
                                                                          <th>{{__('shop_name') }}</th>
                                                                          <td>:</td>
                                                                          <td>
                                                                            <span class="ml-2">{{ $shop->shop_name }} </span>
                                                                            {{isset($shop->nameChange->status)? $shop->nameChange->status: '' }}
                                                                            @if(hasPermission('manage_shops'))
                                                                                @if(isset($shop->nameChange->status) && $shop->nameChange->status == "accept")
                                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#shop-name-request"  class="btn btn-sm btn-primary ml-2 shop-name-request" data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-name-request' : '/staff/shop-name-request' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('change')}}</span></a>
                                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#shop-name-history"    class="btn btn-sm btn-info ml-2 shop-name-histry" data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-change-history' : '/staff/shop-change-history' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('history')}}</span></a>
                                                                                
                                                                                @elseif(isset($shop->nameChange->status) && $shop->nameChange->status == "pending")
                                                                                <a href="javascript:void(0)"  class="btn btn-sm btn-info ml-2"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('request_pending')}}</span></a>
                                                                                
                                                                                @elseif(isset($shop->nameChange->status) && $shop->nameChange->status == "decline")
                                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#shop-name-request"  class="btn btn-sm btn-primary ml-2 shop-name-request" data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-name-request' : '/staff/shop-name-request' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('change')}}</span></a>
                                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#shop-name-history"    class="btn btn-sm btn-info ml-2 shop-name-histry" data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-change-history' : '/staff/shop-change-history' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('history')}}</span></a>
                                                                                
                                                                                @else
                                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#shop-name-request"  class="btn btn-sm btn-primary ml-2 shop-name-request" data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-name-request' : '/staff/shop-name-request' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-wallet-out text-white"></em><span>{{__('change')}}</span></a>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                      </tr>
                                                                      <tr>
                                                                          <th>{{__('contact_number')}}</th>
                                                                          <td>:</td>
                                                                          <td><span class="ml-2">{{ $shop->contact_number }}</span></td>
                                                                      </tr>
                                                                      <tr>
                                                                          <th>{{__('pickup_number')}}</th>
                                                                          <td>:</td>
                                                                          <td><span class="ml-2">{{ $shop->shop_phone_number }}</span> </td>
                                                                      </tr>
                                                                      <tr>
                                                                          <th>{{__('pickup_address') }}</th>
                                                                          <td>:</td>
                                                                          <td><span class="ml-2">{{ $shop->address }}</span></td>
                                                                      </tr>
                                                                      <tr>
                                                                          <th>{{__('default')}}</th>
                                                                          <td>:</td>
                                                                          <td>
                                                                            <span class="ml-2">
                                                                                <div class="custom-control custom-switch">
                                                                                    <input type="checkbox" class="custom-control-input default-change" {{ $shop->default ? 'checked disabled' :'' }} value="{{$shop->id}}"
                                                                                           data-merchant="{{ Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->id : Sentinel::getUser()->merchant_id }}"
                                                                                           data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.default.shop') : route('merchant.staff.default.shop') }}" id="customSwitch2-{{$shop->id}}">
                                                                                    <label class="custom-control-label" for="customSwitch2-{{$shop->id}}"></label>
                                                                                </div>
                                                                            </span>
                                                                          </td>
                                                                      </tr>
                                                                      <tr>
                                                                        <th>{{__('options')}}</th>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <div class="tb-odr-btns d-md-inline ml-2">
                                                                                <a href="" data-toggle="modal" data-target="#edit-shop" class="btn btn-sm btn-info shop-update"
                                                                                   data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop-edit' : '/staff/shop-edit' }}" data-id="{{ $shop->id }}"><em class="icon ni ni-edit"></em></a>
                                                                            </div>
                                                                            @if(!$shop->default)
                                                                                <div class="tb-odr-btns d-md-inline ml-2">
                                                                                    <a href="javascript:void(0);" onclick="delete_row('{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/shop/delete/' : '/staff/shop/delete/' }}', {{$shop->id}})" id="delete-btn" class="btn btn-sm btn-danger"><em class="icon ni ni-trash"></em></a>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                  </tbody>
                                                              </table>

                                                        </div>
                                                        <div class="g">
                                                          {{-- <span class="btn btn-icon btn-trigger me-n1">
                                                                  <em class="icon ni ni-edit"></em>
                                                          </span> --}}
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </a>
                                            </div>

                                            @endforeach



                                        </div>
                                    </div>
                                </div>

                                @include('admin.merchants.details.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
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
                    <form action="{{route('admin.merchant.add.shop')}}" method="POST" class="form-validate is-alter" id="add-shop-form">
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
                            <button type="submit" class="btn btn-lg btn-primary">{{__('submit')}}</button>
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
@endsection
@push('script')
    @include('merchant.delete-ajax')
    @include('merchant.profile.default-shop-ajax')
    <script type="text/javascript">
        $('.shop-update').on('click', function (e) {
            e.preventDefault();
            var url = "{{url('')}}"+'/admin/shop-edit';
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
    </script>
@endpush
