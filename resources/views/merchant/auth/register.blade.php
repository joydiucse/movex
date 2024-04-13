@extends('admin.auth.master')

@section('title')
    {{__('merchant').' '.__('registration')}}
@endsection

@section('mainContent')

    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h4 class="nk-block-title">{{ __('register') }}</h4>
            @include('partials.message')
        </div>
    </div>
    <form action="{{ route('merchant-register') }}" class="form-validate" method="post">
        @csrf
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="company">{{ __('company_name') }} *</label>
            </div>
            <input type="text" name="company" value="{{ old('company') }}" class="form-control form-control-lg" id="company" placeholder="{{__('enter_your_company_name')}}" required>
            @if($errors->has('company'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('company') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="first_name">{{ __('first_name') }} *</label>
            </div>
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control form-control-lg" id="first_name" placeholder="{{__('enter_your_first_name')}}" required>
            @if($errors->has('first_name'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('first_name') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="last_name">{{ __('last_name') }}</label>
            </div>
            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control form-control-lg" id="last_name" placeholder="{{__('enter_your_last_name')}}">
            @if($errors->has('last_name'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('last_name') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="address">{{ __('address') }} *</label>
            </div>
            <textarea class="form-control form-control-lg" id="address" name="address" placeholder="{{__('enter_your_address')}}" required>{{ old('address') }}</textarea>
            @if($errors->has('address'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('address') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="phone_number">{{ __('phone') }} *</label>
            </div>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="form-control form-control-lg" id="phone_number" placeholder="{{__('enter_your_phone')}}" required>
            @if($errors->has('phone_number'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('phone_number') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="email">{{ __('email') }} *</label>
            </div>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" id="email" placeholder="{{__('enter_your_email')}}" required>
            @if($errors->has('email'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('email') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="form-label-group">
                <label class="form-label" for="password">{{ __('password') }} *</label>
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
                        <input type="checkbox" class="custom-control-input" id="checkbox_agree" name="agree_with" required>
                        <label class="custom-control-label" for="checkbox_agree" >{{__('i_agree_to_greenx')}} <a tabindex="-1" target="_blank" href="https://greenx.com.bd/privacy-policy">Privacy Policy</a> &amp; <a tabindex="-1" target="_blank" href="https://greenx.com.bd/terms-condition"> Terms.</a></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-control-wrap">
                    <div class="custom-control custom-control-xs custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="newslatter" name="newslatter" required>
                        <label class="custom-control-label" for="newslatter" >I would like to get newslatter from MoveX Courier.</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-primary btn-block submit-btn">{{ __('register') }}</button>
        </div>
    </form>
    <div class="form-note-s2 pt-4"> {{__('already_have_an_account')}} <a href="{{ route('merchant.login') }}"><strong>{{ __('sign_in_instead') }}</strong></a>
    </div>

@endsection

@push('script')
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
            }, 2000);
        }
    </script>
@endpush
