@extends('admin.auth.master')
@section('title')
    {{ __('confirm').' '.__('otp') }}
@endsection

@section('mainContent')

<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">{{ __('confirm').' '.__('otp') }}</h4>
        <div class="nk-block-des">
            <p>{{ __('opt_confirm_message_text') }}</p>
        </div>
        @include('partials.message')
        @if(@$success)
            @push('script')
            <script type="text/javascript">
                $(document).ready(function() {
                    toastr.clear();
                    NioApp.Toast('{{ $success }}', 'success', {
                        position: 'top-right'
                    });
                });
            </script>
            @endpush
        @endif
        @if(@$danger)
            @push('script')
            <script type="text/javascript">
                $(document).ready(function() {
                    toastr.clear();
                    NioApp.Toast('{{ $danger }}', 'error', {
                        position: 'top-right'
                    });
                });
            </script>
            @endpush
        @endif
    </div>
</div>
<form action="{{ route('confirm-otp') }}" class="form-validate" method="post">
    @csrf
    <input type="text" name="id" hidden class="form-control form-control-lg" value="{{ $id }}" id="id">
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="otp">{{ __('otp') }}</label>
        </div>
        <input type="text" name="otp" class="form-control form-control-lg" id="otp" placeholder="{{__('*****')}}" required>
        @if($errors->has('otp'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('otp') }}</p>
            </div>
        @endif
    </div>
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label text-right" for="password"></label>
            <p class="link btn link-primary link-sm request-otp" id="request" data-request-id="{{ $id }}" data-request="1">{{__('did_not_get').' '.__('request_another') }}</p>
            <div class="link btn link-primary link-sm time d-none">
                <em class="icon ni ni-clock"></em><span id="time" class="pl-0"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-primary btn-block submit-btn">{{ __('submit') }}</button>
    </div>
</form>

@endsection
@push('script')
    <script src="{{ asset('admin/')}}/js/scripts.js?ver=2.3.0"></script>
    @include('merchant.auth.request-otp-ajax')
    <script type="text/javascript">
        $(document).ready(function () {

            $('.submit-btn').on('click', function (e) {
                var loading = 2;

                $('.submit-btn').css("pointer-events", "none");
                $('.submit-btn').addClass('disabled');
                startTimer(loading);
            });
        });

        function startTimer(duration) {
            var timer = duration, minutes, seconds;
            var trigger =  setInterval(function () {
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                if (timer >= 0) {
                    --timer;
                }
                else if(timer < 0){
                    $('.submit-btn').css("pointer-events", "auto");
                    $('.submit-btn').removeClass('disabled');
                }
            }, 1000);
        }
    </script>
@endpush
