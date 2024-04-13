@extends('master')

@section('title')
    {{__('edit').' '.__('merchant')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit')}} {{__('merchant')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('merchant.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="{{$user->id}}" name="id">
                        <input type="hidden" value="{{$merchant->id}}" name="merchant_id">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('first_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" name="first_name" value="{{ old('first_name') !='' ? old('first_name') : $user->first_name }}" required>
                                                    </div>
                                                    @if($errors->has('first_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('first_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('last_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" value="{{ old('last_name') !='' ? old('last_name') : $user->last_name }}" name="last_name">
                                                    </div>
                                                    @if($errors->has('last_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('last_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-email">{{__('email')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" class="form-control" id="fv-email" name="email" value="{{ old('email') !='' ? old('email') : $user->email }}" required>
                                                    </div>
                                                    @if($errors->has('email'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('email') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="company">{{ __('company_name') }} *</label>
                                                    <div class="form-control-wrap">
                                                        @if(haspermission('merchant_company_name_update'))
                                                        <input type="text" class="form-control" id="company" value="{{ old('company') !='' ? old('company') : $merchant->company }}" name="company" required>
                                                        @else
                                                        <input readonly type="text" class="form-control" id="company" value="{{ old('company') !='' ? old('company') : $merchant->company }}" name="company" required>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($errors->has('company'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('company') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone_number">{{ __('phone_number') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone_number" value="{{ old('phone_number') !='' ? old('phone_number') : $merchant->phone_number }}" name="phone_number" required>
                                                    </div>
                                                </div>
                                                @if($errors->has('phone_number'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('phone_number') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="website">{{ __('website') }}
                                                        @if(!blank($merchant->website))
                                                            <a href="{{ $merchant->website }}" target="_blank"> <em class="icon ni ni-link-alt"></em></a>
                                                        @endif
                                                    </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="website" value="{{ old('website') !='' ? old('website') : $merchant->website }}" name="website">
                                                    </div>

                                                    @if($errors->has('website'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('website') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="city">{{ __('city') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="city" value="{{ old('city') !='' ? old('city') : $merchant->city }}" name="city">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="zip">{{ __('zip') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="zip" value="{{ old('zip') !='' ? old('zip') : $merchant->zip }}" name="zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="form-label" for="address">{{ __('address') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="address" value="{{ old('address') !='' ? old('address') : $merchant->address }}" name="address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="billing_street">{{ __('billing').' '.__('street') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="billing_street" value="{{ old('billing_street') !='' ? old('billing_street') : $merchant->billing_street }}" name="billing_street">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="billing_city">{{ __('billing').' '.__('city') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="billing_city" value="{{ old('billing_city') !='' ? old('billing_city') : $merchant->billing_city }}" name="billing_city">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="billing_zip">{{ __('billing').' '.__('zip') }}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="billing_zip" value="{{ old('billing_zip') !='' ? old('billing_zip') : $merchant->billing_zip }}" name="billing_zip">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="pickup_hub">{{__('parcel').' '.__('pickup_hub')}} </label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" id="pickup_hub" name="pickup_hub">
                                                            <option value="">{{ __('select_hub') }}</option>
                                                            @foreach($hubs as $hub)
                                                                <option value="{{ $hub->id }}" {{ $merchant->user->hub_id  == $hub->id ? 'selected' : ''}}>{{ __($hub->name).' ('.$hub->address.')' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($errors->has('pickup_hub'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('pickup_hub') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="vat">{{ __('vat') }} (%)</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="vat" value="{{ old('vat') !='' ? old('vat') : $merchant->vat }}" name="vat">
                                                    </div>
                                                </div>
                                                @if($errors->has('vat'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('vat') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="opening_balance">{{ __('opening_balance') }} * <span class="text-warning">{{ $merchant->merchantAccount->payment_withdraw_id != null ||  $merchant->merchantAccount->is_paid == true ? __('adjusted_in_payment') : '' }}</span></label>
                                                    <div class="form-control-wrap">
                                                        <input type="number" class="form-control" id="opening_balance" value="{{old('opening_balance') != ''? old('opening_balance'): @$merchant->merchantAccount->amount}}" name="opening_balance" required {{ $merchant->merchantAccount->payment_withdraw_id != null ||  $merchant->merchantAccount->is_paid == true ? 'readonly' : '' }}>
                                                    </div>
                                                    @if($errors->has('opening_balance'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('opening_balance') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
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
                                            <div class="col-md-4">
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

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="dstaff-live-search">{{__('key_account')}}</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <select id="staff_id" name="staff_id" class="form-control form-control-lg staff-live-search-live-search">
                                                                @if(!empty($merchant->key_account_id))
                                                                <option value="{{ $merchant->key_account_id }}">{{ $merchant->keyAccountUser->first_name }} {{ $merchant->keyAccountUser->last_name }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('staff_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('staff_id') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="dstaff-live-search">{{__('sales_agent')}}</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <select id="sales_agent" name="sales_agent" class="form-control form-control-lg staff-live-search-live-search">
                                                                @if(!empty($merchant->sales_agent_id))
                                                                <option value="{{ $merchant->sales_agent_id }}">{{ $merchant->salesAgent->first_name }} {{ $merchant->salesAgent->last_name }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('staff_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('staff_id') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>



                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <div class="form-group text-center">
                                                    @if($user->image != null)
                                                        <img src="{{ asset($user->image->original_image)}}" id="img_profile" class="img-thumbnail user-profile" >
                                                    @else
                                                        <img src="{{asset('admin/images/default/user.jpg') }}" id="img_profile" class="img-thumbnail user-profile">
                                                    @endif
                                                </div>
                                                <div class="form-group">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
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

                                        {{-- start charge area --}}
                                        <div class="row mt-5">

                                            <div class="col-md-12 mb-3">
                                                <h5>{{__('cash_on_delivery_charge')}}</h5>
                                            </div>
                                            @foreach($merchant->cod_charges as $key=>$cod_charge)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="charge">{{ __($key) }}</label>
                                                    <input type="hidden" name="locations[]" value="{{$key}}">
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" value="{{ $cod_charge }}" id="charge" name="charge[]" required>
                                                    </div>
                                                </div>
                                                @if($errors->has('charge'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('charge') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 mb-3">
                                                <h5>{{__('charge')}}</h5>
                                            </div>
                                            <table class="table table-striped role-create-table" id="permissions-table">
                                                <thead>
                                                    <tr>
                                                      <th scope="col">{{__('weight')}}</th>
                                                      <th scope="col">{{__('same_day')}}</th>
                                                      <th scope="col">{{__('next_day')}}</th>
                                                      {{-- <th scope="col">{{__('frozen')}}</th> --}}
                                                      <th scope="col">{{__('sub_city')}}</th>
                                                      <th scope="col">{{__('outside_dhaka')}}</th>
                                                      {{-- <th scope="col">{{__('third_party_booking')}}</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      @foreach($merchant->charges as $weight => $charge)
                                                        <tr>
                                                          <td>
                                                              <span class="text-capitalize">{{$weight}}</span>
                                                              <input type="hidden" value="{{$weight}}" name="weights[]">
                                                          </td>
                                                          <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'same_day', 0.00) }}" id="charge" name="same_day[]" required>
                                                            </div>
                                                          </td>
                                                          <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'next_day', 0.00) }}" id="charge" name="next_day[]" required>
                                                            </div>
                                                          </td>
                                                          {{-- <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'frozen', 0.00) }}" id="charge" name="frozen[]" required>
                                                            </div>
                                                          </td> --}}
                                                          <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'sub_city', 0.00) }}" id="charge" name="sub_city[]" required>
                                                            </div>
                                                          </td>
                                                          <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'outside_dhaka', 0.00) }}" id="charge" name="outside_dhaka[]" required>
                                                            </div>
                                                          </td>
                                                          {{-- <td>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" value="{{ data_get($charge, 'third_party_booking', 0.00) }}" id="charge" name="third_party_booking[]" required>
                                                            </div>
                                                          </td> --}}
                                                        </tr>
                                                    @endforeach

                                                  </tbody>
                                            </table>
                                        </div>
                                        {{-- end charge area --}}

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
    @include('live_search.staff')
@endsection

