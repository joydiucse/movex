@extends('master')

@section('title')
    {{__('edit').' '.__('account')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit')}} {{__('account')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.account.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$account->id}}">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="method">{{__('user')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="user" required>
                                                            <option value="">{{ __('select_user') }}</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}" {{$user->id == $account->user_id? 'selected':'' }}>{{ $user->first_name.' '.$user->last_name }}( {{$user->email}})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($errors->has('staff'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('staff') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="method">{{__('method')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg method" name="method" required>
                                                            <option value="">{{ __('select_type') }}</option>
                                                            @foreach(\Config::get('greenx.account_methods') as $account_method)

                                                                <option value="{{ $account_method }}" {{$account_method == $account->method? 'selected':''}}>{{ __($account_method) }}</option>

                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($errors->has('method'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('method') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs account-holder {{$account->method == 'cash'? 'd-none':''}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="account_holder_name">{{__('account_holder_name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="account_holder_name" value="{{ old('account_holder_name') != ""? old('account_holder_name'): $account->account_holder_name }}" name="account_holder_name" required="{{$account->method == 'cash'? 'false':'true'}}">
                                                    </div>
                                                    @if($errors->has('account_holder_name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account_holder_name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs method-bank {{$account->method == 'bank'? '':'d-none'}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="account_no">{{__('account_no')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="account_no" value="{{ old('account_no') != ""? old('account_holder_name'): $account->account_no  }}" name="account_no" required="{{$account->method == 'bank'? 'true':''}}">
                                                    </div>
                                                    @if($errors->has('account_no'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account_no') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs method-bank {{$account->method == 'bank'? '':'d-none'}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="bank">{{__('bank')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="bank" id="bank" required="{{$account->method == 'bank'? 'true':''}}">
                                                            <option value="">{{ __('select_type') }}</option>
                                                            @foreach(\Config::get('greenx.banks') as $bank)
                                                                <option value="{{ $bank }}" {{$bank == $account->bank_name? 'selected':''}}>{{ __($bank) }}</option>
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
                                        <div class="row g-gs method-bank {{$account->method == 'bank'? '':'d-none'}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="branch">{{__('branch')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="branch" value="{{ old('branch') != ""? old('branch'): $account->bank_branch }}" name="branch" required="{{$account->method == 'bank'? 'true':''}}">
                                                    </div>
                                                    @if($errors->has('branch'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('branch') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-gs method-mobile {{$account->method != 'bank' && $account->method != 'cash'? '':'d-none'}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="number">{{__('number')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="number" value="{{ old('number') != ""? old('number'): $account->number }}" name="number" required="{{$account->method != 'bank' && $account->method != 'cash'? 'true':''}}">
                                                    </div>
                                                    @if($errors->has('number'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('number') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs method-mobile {{$account->method != 'bank' && $account->method != 'cash'? '':'d-none'}}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="account_type">{{__('account_type')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" id="account_type" name="account_type" required="{{$account->method != 'bank' && $account->method != 'cash'? 'true':''}}">
                                                            <option value="">{{ __('select_account_type') }}</option>
                                                            <option value="merchant" {{$account->type == 'merchant'? 'selected':''}}>{{ __('merchant') }}</option>
                                                            <option value="personal" {{$account->type == 'personal'? 'selected':''}}>{{ __('personal') }}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('account_type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account_type') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('opening_balance')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" value="{{ old('opening_balance') != ""? old('opening_balance'): $account->balance }}" name="opening_balance" required>
                                                    </div>
                                                    @if($errors->has('opening_balance'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('opening_balance') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
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
@push('script')
   <script>
       $(document).ready(function(){
            $('.method').on('change', function(){
                if($(this).val() == 'bank'){
                    $('.method-mobile').addClass('d-none');
                    $('.method-bank').removeClass('d-none');
                    $('.account-holder').removeClass('d-none');

                    $("#account_no").attr("required", true);
                    $("#branch").attr("required", true);
                    $("#bank").attr("required", true);

                    $("#account_type").attr("required", false);
                    $("#number").attr("required", false);
                    $("#account_holder_name").attr("required", true);


                }else if($(this).val() == 'cash'){
                    $('.method-mobile').addClass('d-none');
                    $('.method-bank').addClass('d-none');
                    $('.account-holder').addClass('d-none');

                    $("#account_no").attr("required", false);
                    $("#branch").attr("required", false);
                    $("#bank").attr("required", false);

                    $("#account_type").attr("required", false);
                    $("#number").attr("required", false);
                    $("#account_holder_name").attr("required", false);

                }else{
                    $('.method-mobile').removeClass('d-none');
                    $('.method-bank').addClass('d-none');
                    $('.account-holder').removeClass('d-none');

                    $("#account_no").attr("required", false);
                    $("#branch").attr("required", false);
                    $("#bank").attr("required", false);

                    $("#account_type").attr("required", true);
                    $("#number").attr("required", true);
                    $("#account_holder_name").attr("required", true);
                }
            });
       });
   </script>
   <script>
    $(document).ready(function(){
        $('select').select2();
    });
</script>
@endpush

