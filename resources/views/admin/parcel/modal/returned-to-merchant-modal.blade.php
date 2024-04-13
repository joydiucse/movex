{{-- message-area --}}
<div class="alert alert-success alert-dismissible fade show text-dark message-box_{{$parcel->id}}" style="display: none">
    <strong class="response_{{$parcel->id}}"></strong>
</div>

{{-- message-area --}}
<form action="{{route('parcel-returned-to-merchant')}}" method="POST" class="form-validate is-alter">
    @csrf

    @if($return_sms->sms_to_merchant == 1)
    <input type="hidden" name="id" value="{{$parcel->id}}" id="returned-to-merchant-id">
    <input type="text" class="form-control" name="opt_code" id="opt-code" placeholder="OTP Code" onblur="merchantOTPCheck()">
    @if(hasPermission('parcel_return_assigned_to_merchant'))
        <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-primary  mt-1 otp-button_{{$parcel->id}}" onclick="sendOTPCode()">Send OTP</button>
                </div>

                <div class="col-md-1 mt-1">
                    <span class="countdown_{{$parcel->id}}" style="visibility: hidden;">120</span>
                </div>
        </div>

    @endif
    <div class="form-group">
        <label class="form-label" for="area">{{ __('note') }}</label>
        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
    </div>
    @if(hasPermission('parcel_return_assigned_to_merchant'))
        <div class="form-group text-right">
            <button type="submit" disabled class="btn btn-lg btn-primary resubmit merchant_return_btn_{{$parcel->id}}">{{__('submit')}}</button>
        </div>
    @endif

    @else
    <div class="form-group">
        <label class="form-label" for="area">{{ __('note') }}</label>
        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
        <input type="hidden" name="id" value="{{$parcel->id}}" id="returned-to-merchant-id">
    </div>
    <div class="form-group text-right">
        <button type="submit"  class="btn btn-lg btn-primary resubmit merchant_return_btn_{{$parcel->id}}">{{__('submit')}}</button>
    </div>
    @endif


</form>
