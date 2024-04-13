@extends('admin.auth.master')
@section('title')
{{__('reset_password')}}
@endsection


@section('mainContent')
<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">{{ __('reset_password') }}</h4>
        @include('partials.message')
    </div>
</div>
<form action="{{ route('reset-password', [$email, $resetCode]) }}" class="form-validate" method="post">
    @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="default-01">{{ __('password') }}</label>
        </div>
        <input type="password" name="password" class="form-control form-control-lg" id="default-01" placeholder="{{ __('password') }}" required>
        @if($errors->has('password'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('password') }}</p>
            </div>
        @endif
    
    </div>
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="default-01">{{ __('confirm_password') }}</label>
        </div>
        <input type="password" name="password_confirmation" class="form-control form-control-lg" id="default-01" placeholder="{{ __('confirm_password') }}" required>
        @if($errors->has('password_confirmation'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('password_confirmation') }}</p>
            </div>
        @endif
    
    </div>
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="password"></label>
            <a class="link link-primary link-sm" href="{{route('login')}}">{{ __('login') }}?</a>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('submit') }}</button>
    </div>
</form>
</div>
@endsection
                    