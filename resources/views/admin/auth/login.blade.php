@extends('admin.auth.master')

@section('title')
{{__('login')}}
@endsection

@section('mainContent')

<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">{{ __('login') }}</h4>
        @include('partials.message')
    </div>
</div>
<form action="{{ route('login') }}" class="form-validate" method="post">
    @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="default-01">{{ __('email') }}</label>
        </div>
        <input type="email" name="email" class="form-control form-control-lg" id="default-01" value="{{ old('email') }}" placeholder="{{__('enter_your_email')}}" required>
        @if($errors->has('email'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('email') }}</p>
            </div>
        @endif


    </div>
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="password">{{ __('password') }}</label>
            <a class="link link-primary link-sm" href="{{route('forgot-password')}}">{{ __('forgot_password') }}?</a>
        </div>
        <div class="form-group">
            <div class="form-control-wrap">
                <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                </a>
                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="{{__('enter_your_password')}}" required>

                @if($errors->has('password'))
                    <div class="nk-block-des text-danger">
                        <p>{{ $errors->first('password') }}</p>
                    </div>
                @endif

            </div>
        </div>
        <div class="row ml-1 mb-1">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
                @if($errors->has('g-recaptcha-response'))
                    <div class="nk-block-des text-danger">
                        <p>{{ $errors->first('g-recaptcha-response') }}</p>
                    </div>
                @endif
            </div>
        <div class="form-group">
            <div class="form-control-wrap">
                <div class="custom-control custom-control-xs custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox" name="remember_me">
                    <label class="custom-control-label" for="checkbox">{{__('remember_me')}} </label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('login') }}</button>
    </div>
</form>
{{-- <div class="form-note-s2 pt-4"> {{ __('new_on_our_platform') }} <a href="{{ route('merchant-register') }}">{{ __('sign_up_here') }}</a>
</div> --}}


@endsection
