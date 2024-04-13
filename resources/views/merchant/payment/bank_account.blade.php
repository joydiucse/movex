@extends('master')

@section('title')
    {{__('bank_account')}}
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
                                                <h4 class="nk-block-title">{{__('payment_account_information')}}</h4>
                                                <div class="nk-block-des">
                                                    <p>{{__('payment_account_info')}}</p>
                                                </div>
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->

                                    <div class="nk-block nk-block-lg">
                                        <div class="row g-gs">
                                          <div class="col-lg-6">
                                            <a href="#" class="card card-bordered text-soft"  data-toggle="modal"  data-target="#bankaccountmodal">
                                              <div class="card-inner">
                                                <div class="align-center justify-between">
                                                  <div class="g">
                                                    <h6 class="title">Bank</h6>
                                                    @if($payment_account->bank_ac_number !="" && $payment_account->bank_ac_number !=NULL)
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <th>Bank</th>
                                                                    <td>:</td>
                                                                    <td>{{__($payment_account->selected_bank)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>A/c Name</th>
                                                                    <td>:</td>
                                                                    <td>{{__($payment_account->bank_ac_name)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>A/c No</th>
                                                                    <td>:</td>
                                                                    <td>{{$payment_account->bank_ac_number}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Branch</th>
                                                                    <td>:</td>
                                                                    <td>{{$payment_account->bank_branch}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Routing No</th>
                                                                    <td>:</td>
                                                                    <td>{{$payment_account->routing_no}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p>No bank account added yet.</p>
                                                    @endif
                                                  </div>
                                                  <div class="g">
                                                    {{-- <a href="#"></a> --}}
                                                    <span class="btn btn-icon btn-trigger me-n1">
                                                        @if($payment_account->bank_ac_number !="" && $payment_account->bank_ac_number !=NULL)
                                                            <em class="icon ni ni-edit"></em>
                                                        @else
                                                            <em class="icon ni ni-plus"></em>
                                                        @endif
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div> <!-- Bank account -->
                                          
                                          <div class="col-lg-6">
                                            <a href="#" class="card card-bordered text-soft"  data-toggle="modal"  data-target="#bkashaccountmodal">
                                              <div class="card-inner">
                                                <div class="align-center">
                                                    <div class="chat-media"><img src="{{asset('admin/images/payment/bkash.png')}}" alt=""></div>
                                                  <div class="g ml-3">
                                                    <h6 class="title">bKash</h6>
                                                    @if($payment_account->bkash_number !="" && $payment_account->bkash_number !=NULL)
                                                        <p><strong>{{$payment_account->bkash_number}}</strong>({{$payment_account->bkash_ac_type}})</p>
                                                    @else
                                                        <p>No bKash account added yet.</p>
                                                    @endif
                                                  </div>
                                                  <div class="g ml-auto">
                                                    <span class="btn btn-icon btn-trigger me-n1">
                                                        @if($payment_account->bkash_number !="" && $payment_account->bkash_number !=NULL)
                                                            <em class="icon ni ni-edit"></em>
                                                        @else
                                                            <em class="icon ni ni-plus"></em>
                                                        @endif
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div> <!-- Bkash account -->

                                          <div class="col-lg-6">
                                            <a href="#" class="card card-bordered text-soft"  data-toggle="modal"  data-target="#rocketaccountmodal">
                                              <div class="card-inner">
                                                <div class="align-center">
                                                    <div class="chat-media"><img src="{{asset('admin/images/payment/rocket.jpg')}}" alt=""></div>
                                                  <div class="g ml-3">
                                                    <h6 class="title">Rocket</h6>
                                                    @if($payment_account->rocket_number !="" && $payment_account->rocket_number !=NULL)
                                                        <p><strong>{{$payment_account->rocket_number}}</strong>({{$payment_account->rocket_ac_type}})</p>
                                                    @else
                                                        <p>No Rocket account added yet.</p>
                                                    @endif
                                                  </div>
                                                  <div class="g ml-auto">
                                                    <span class="btn btn-icon btn-trigger me-n1">
                                                        @if($payment_account->rocket_number !="" && $payment_account->rocket_number !=NULL)
                                                            <em class="icon ni ni-edit"></em>
                                                        @else
                                                            <em class="icon ni ni-plus"></em>
                                                        @endif
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div> <!-- Rocket account -->

                                          <div class="col-lg-6">
                                            <a href="#" class="card card-bordered text-soft"  data-toggle="modal"  data-target="#nagadaccountmodal">
                                              <div class="card-inner">
                                                <div class="align-center">
                                                    <div class="chat-media"><img src="{{asset('admin/images/payment/nagad.png')}}" alt=""></div>
                                                  <div class="g ml-3">
                                                    <h6 class="title">Nagad</h6>
                                                    @if($payment_account->nogod_number !="" && $payment_account->nogod_number !=NULL)
                                                        <p><strong>{{$payment_account->nogod_number}}</strong>({{$payment_account->nogod_ac_type}})</p>
                                                    @else
                                                        <p>No Nagad account added yet.</p>
                                                    @endif
                                                  </div>
                                                  <div class="g ml-auto">
                                                    <span class="btn btn-icon btn-trigger me-n1">
                                                        @if($payment_account->nogod_number !="" && $payment_account->nogod_number !=NULL)
                                                            <em class="icon ni ni-edit"></em>
                                                        @else
                                                            <em class="icon ni ni-plus"></em>
                                                        @endif
                                                    </span>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div> <!-- nagad account -->

                                        </div>
                                      </div>
                                </div>
                                
                                 <!-- bank account modal -->
                                 <div class="modal fade" id="bankaccountmodal" aria-labelledby="bankaccountmodal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bank Account</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.bank.account.update') :  route('merchant.staff.bank.account.update')}}" class="form-validate" method="POST">
                                                @csrf
                                                <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="selected_bank">{{__('selected_bank')}} *</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg" name="selected_bank">
                                                                    <option value="">{{ __('select_type') }}</option>
                                                                    @foreach(\Config::get('greenx.banks') as $bank)
                                                                        <option value="{{ $bank }}" {{$bank == $payment_account->selected_bank ? 'selected':''}}>{{ __($bank) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @if($errors->has('selected_bank'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('selected_bank') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="bank_branch">{{__('bank_branch')}} </label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="bank_branch" value="{{ old('bank_branch') != ""? old('bank_branch'):$payment_account->bank_branch }}" name="bank_branch" placeholder="{{__('enter_bank_branch')}}" required>
                                                            </div>
                                                            @if($errors->has('bank_branch'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('bank_branch') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="bank_ac_name">{{__('bank_ac_name')}} </label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="bank_ac_name" value="{{ old('bank_ac_name') != ""? old('bank_ac_name'):$payment_account->bank_ac_name }}" name="bank_ac_name" placeholder="{{__('enter_bank_ac_name')}}" required>
                                                            </div>
                                                            @if($errors->has('bank_ac_name'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('bank_ac_name') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="bank_ac_number">{{__('bank_ac_number')}} </label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="bank_ac_number" value="{{ old('bank_ac_number') != ""? old('bank_ac_number'):$payment_account->bank_ac_number }}" name="bank_ac_number" placeholder="{{__('enter_bank_ac_number')}}" required>
                                                            </div>
                                                            @if($errors->has('bank_ac_number'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('bank_ac_number') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="routing_no">{{__('routing_no')}} </label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="routing_no" value="{{ old('routing_no') != ""? old('routing_no'):$payment_account->routing_no }}" name="routing_no" placeholder="{{__('enter_routing_no')}}" required>
                                                            </div>
                                                            @if($errors->has('routing_no'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('routing_no') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right mt-4">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary">{{{__('save')}}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                 <!-- bank account modal -->

                                 <!-- bkash account modal -->
                                 <div class="modal fade" id="bkashaccountmodal" aria-labelledby="bkashaccountmodal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Bkash Account</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.others.account.update') : route('merchant.staff.others.account.update')}}" class="form-validate" method="POST">
                                                    @csrf
                                                    <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="card-inner">
                                                            <div class="row g-gs">

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="bkash_number">{{__('bkash').' '.__('number')}} </label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="bkash_number" value="{{ old('bkash_number') != ""? old('bkash_number'):$payment_account->bkash_number }}" name="bkash_number" placeholder="{{__('enter_bkash_number')}}">
                                                                           
                                                                            <input type="hidden" name="rocket_number" value="{{ ($payment_account->rocket_number) ? $payment_account->rocket_number: '' }}">
                                                                            <input type="hidden" name="rocket_ac_type" value="{{ ($payment_account->rocket_ac_type) ? $payment_account->rocket_ac_type: '' }}">
                                                                            
                                                                            <input type="hidden" name="nogod_number" value="{{ ($payment_account->nogod_number) ? $payment_account->nogod_number: '' }}">
                                                                            <input type="hidden" name="nogod_ac_type" value="{{ ($payment_account->nogod_ac_type) ? $payment_account->nogod_ac_type: '' }}">
                                                                        </div>
                                                                        @if($errors->has('bkash_number'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('bkash_number') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="bkash_ac_type">{{__('bkash').' '.__('account_type')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select form-control form-control-lg" name="bkash_ac_type">
                                                                                <option value="">{{ __('select_type') }}</option>
                                                                                @foreach(\Config::get('greenx.account_types') as $type)
                                                                                    <option value="{{ $type }}" {{(old('bkash_ac_type') != '' && $type == old('bkash_ac_type') ) ? 'selected' : ($type == $payment_account->bkash_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @if($errors->has('bkash_ac_type'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('bkash_ac_type') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 text-right mt-4">
                                                                    <div class="form-group">
                                                                        <button type="submit" class="btn btn-lg btn-primary">{{{__('update')}}}</button>
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
                                 <!-- bkash account modal -->


                                 <!-- Rocket account modal -->
                                 <div class="modal fade" id="rocketaccountmodal" aria-labelledby="rocketaccountmodal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rocket Account</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.others.account.update') : route('merchant.staff.others.account.update')}}" class="form-validate" method="POST">
                                                    @csrf
                                                    <div class="row g-gs">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="rocket_number">{{__('rocket').' '.__('number')}} </label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="rocket_number" value="{{ old('rocket_number') != ""? old('rocket_number'):$payment_account->rocket_number }}" name="rocket_number" placeholder="{{__('enter_rocket_number')}}">

                                                                    <input type="hidden" name="bkash_number" value="{{ ($payment_account->bkash_number) ? $payment_account->bkash_number: '' }}">
                                                                    <input type="hidden" name="bkash_ac_type" value="{{ ($payment_account->bkash_ac_type) ? $payment_account->bkash_ac_type: '' }}">
                                                                    
                                                                    <input type="hidden" name="nogod_number" value="{{ ($payment_account->nogod_number) ? $payment_account->nogod_number: '' }}">
                                                                    <input type="hidden" name="nogod_ac_type" value="{{ ($payment_account->nogod_ac_type) ? $payment_account->nogod_ac_type: '' }}">
                                                                </div>
                                                                @if($errors->has('rocket_number'))
                                                                    <div class="nk-block-des text-danger">
                                                                        <p>{{ $errors->first('rocket_number') }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="rocket_ac_type">{{__('rocket').' '.__('account_type')}} *</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select form-control form-control-lg" name="rocket_ac_type">
                                                                        <option value="">{{ __('select_type') }}</option>
                                                                        @foreach(\Config::get('greenx.account_types') as $type)
                                                                            <option value="{{ $type }}" {{(old('rocket_ac_type') != '' && $type == old('rocket_ac_type') ) ? 'selected' : ($type == $payment_account->rocket_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @if($errors->has('rocket_ac_type'))
                                                                    <div class="nk-block-des text-danger">
                                                                        <p>{{ $errors->first('rocket_ac_type') }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 text-right mt-4">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-lg btn-primary">{{{__('update')}}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                 <!-- Rocket account modal -->

                                 <!-- Nagad account modal -->
                                 <div class="modal fade" id="nagadaccountmodal" aria-labelledby="nagadaccountmodal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nagad Account</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.others.account.update') : route('merchant.staff.others.account.update')}}" class="form-validate" method="POST">
                                                    @csrf
                                                    <div class="row g-gs">
                                                    <div class="col-md-12">
                                                        <div class="card-inner">
                                                            <div class="row g-gs">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nogod_number">{{__('nogod').' '.__('number')}} </label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="nogod_number" value="{{ old('nogod_number') != ""? old('nogod_number'):$payment_account->nogod_number }}" name="nogod_number" placeholder="{{__('enter_nogod_number')}}">

                                                                            <input type="hidden" name="bkash_number" value="{{ ($payment_account->bkash_number) ? $payment_account->bkash_number: '' }}">
                                                                            <input type="hidden" name="bkash_ac_type" value="{{ ($payment_account->bkash_ac_type) ? $payment_account->bkash_ac_type: '' }}">
                                                                            
                                                                            <input type="hidden" name="rocket_number" value="{{ ($payment_account->rocket_number) ? $payment_account->rocket_number: '' }}">
                                                                            <input type="hidden" name="rocket_ac_type" value="{{ ($payment_account->rocket_ac_type) ? $payment_account->rocket_ac_type: '' }}">
                                                                        </div>
                                                                        @if($errors->has('nogod_number'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('nogod_number') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nogod_ac_type">{{__('nogod').' '.__('account_type')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select form-control form-control-lg" name="nogod_ac_type">
                                                                                <option value="">{{ __('select_type') }}</option>
                                                                                @foreach(\Config::get('greenx.account_types') as $type)
                                                                                    <option value="{{ $type }}" {{(old('nogod_ac_type') != '' && $type == old('nogod_ac_type') ) ? 'selected' : ($type == $payment_account->nogod_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @if($errors->has('nogod_ac_type'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('nogod_ac_type') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 text-right mt-4">
                                                                    <div class="form-group">
                                                                        <button type="submit" class="btn btn-lg btn-primary">{{{__('update')}}}</button>
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
                                 <!-- Nagad account modal -->

                                 

                               
                                @include('merchant.profile.profile-sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
