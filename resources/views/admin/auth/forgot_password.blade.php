@extends('admin.auth.master')
@section('title')
{{__('forgot_password')}}
@endsection

@section('mainContent')

<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">{{ __('forgot_password') }}</h4>
        @include('partials.message')
    </div>
</div>
<form action="{{ route('forgot-password') }}" class="form-validate" method="post">
    @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="default-01">{{ __('email') }}</label>
        </div>
        <input type="email" name="email" class="form-control form-control-lg" id="default-01" placeholder="{{__('enter_your_email')}}" required>
        @if($errors->has('email'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('email') }}</p>
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
       
@endsection
                    