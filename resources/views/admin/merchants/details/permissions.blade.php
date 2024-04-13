@extends('master')

@section('title')
    {{__('merchant').' '.__('permissions')}}
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
                                    <div class="nk-block-head nk-block-head-lg pb-2">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('merchant').' '.__('permissions')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <form id="permission-form" action="{{ route('detail.merchant.permission.update', $merchant->id)}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card shadow-none">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-inner">
                                                    <div class="card-title mt-3">
                                                        <h5>{{ __('api') }}</h5>
                                                    </div>
                                                    <div class="row g-gs">
                                                        <table class="table table-borderless m-2">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">{{ __('title') }}</th>
                                                                <th scope="col">{{ __('status') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <div class="g-item">
                                                                <tr>
                                                                    <td>
                                                                        {{ __('read_credentials') }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input common-key" id="{{'read_api_credentials'}}" name="permissions[]" value="read_api_credentials" {{in_array('read_api_credentials', $merchant->user->permissions)? 'checked':''}}>
                                                                            <label class="custom-control-label" for="read_api_credentials"></label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        {{ __('update_credentials') }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input common-key" id="{{'update_api_credentials'}}" name="permissions[]" value="update_api_credentials" {{in_array('update_api_credentials', $merchant->user->permissions)? 'checked':''}}>
                                                                            <label class="custom-control-label" for="update_api_credentials"></label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </div>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 text-right mt-4">
                                                            <div class="form-group">
                                                                <button type="button" onclick="formSubmit('api')" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    </form> <!--- Permission  -->

                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('api_credentials')}}</h4>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->

                                    @if(hasPermission('merchant_api_credentials_update') && @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff)
                                    <form id="credential-form" action="{{ route('detail.merchant.api.credentials.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @endif
                                        <div class="card shadow-none">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-inner">
                                                        <div class="row g-gs">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="api_key">{{ __('api_key') }} {{ @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff ? '*' : '' }}</label>
                                                                    <div class="form-control-wrap d-flex">
                                                                        <input type="text" class="form-control" hidden id="merchant" value="{{ $merchant->id }}" name="id">
                                                                        <input type="text" class="form-control api-key" id="api_key" data-text="{{__('copied')}}" value="{{ $merchant->api_key }}" name="api_key" required>
                                                                        @if(hasPermission('merchant_api_credentials_update') && @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff)
                                                                            <span type="button" class="btn btn-icon btn-trigger btn-tooltip {{ hasPermission('merchant_api_credentials_update') ? '' : 'd-none' }}" onclick="getKey(15, 'api_key')" data-original-title="{{__('change')}}"><em class="icon ni ni-repeat"></em></span>
                                                                        @endif
                                                                        <span type="button" class="btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('copy')}}" onclick="copyInput('api_key')"><em class="icon ni ni-copy"></em></span>
                                                                    </div>
                                                                    @if($errors->has('api_key'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('api_key') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="secret_key">{{ __('secret_key') }} {{ @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff ? '*' : ''}}</label>
                                                                    <div class="form-control-wrap d-flex">
                                                                        <input type="text" class="form-control secret-key" id="secret_key" data-text="{{__('copied')}}" value="{{ $merchant->secret_key }}" name="secret_key" required>
                                                                        @if(hasPermission('merchant_api_credentials_update') && @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff)
                                                                            <span type="button" class="btn btn-icon btn-trigger btn-tooltip {{ hasPermission('merchant_api_credentials_update') ? '' : 'd-none' }}" onclick="getKey(30, 'secret_key')" data-original-title="{{__('change')}}"><em class="icon ni ni-repeat"></em></span>
                                                                        @endif
                                                                        <span type="button" class="btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('copy')}}" onclick="copyInput('secret_key')"><em class="icon ni ni-copy"></em></span>
                                                                    </div>
                                                                    @if($errors->has('secret_key'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('secret_key') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(hasPermission('merchant_api_credentials_update') && @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff)
                                                            <div class="row">
                                                                <div class="col-md-12 text-right mt-4">
                                                                    <div class="form-group">
                                                                        <button type="button" onclick="formSubmit('credential')" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @if(hasPermission('merchant_api_credentials_update') && @settingHelper('preferences')->where('title','merchant_api_update')->first()->staff)
                                    </form>
                                @endif
                                </div>

                                @include('admin.merchants.details.sidebar')
                                @include('admin.merchants.details.script')
                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function formSubmit(val)
    {
        if(val == 'credential'){
            $('#credential-form').submit();
        }

        if(val == 'api'){
            $('#permission-form').submit();
        }
    }
</script>
@endpush
