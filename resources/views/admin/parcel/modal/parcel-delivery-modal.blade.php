@php $otp_permission = settingHelper('delivery_otp'); @endphp
{{-- message-area --}}
<form action="{{route('parcel-delivered')}}" method="POST" class="form-validate is-alter">
    @csrf
{{-- message-area --}}
    @if(isset($sms_info) && $sms_info !='')

        <div class=" alert-success alert-dismissible fade show text-dark ">
            <span class="send-response_{{$parcel->id}}">Delivery OTP  send to this number <strong class="font-weight-bold response_{{$parcel->id}}">{{$sms_info}}</strong> </span>
            <span><strong class="font-weight-bold otp-veryfi-response_{{$parcel->id}}"></strong></span>
        </div>
        <div class="row mt-2 mb-2">
            <div class="col-md-4">
                <button type="button" class="btn btn-sm btn-primary otp-button_{{$parcel->id}}" onclick="DeliveryOTPCode()">Re-Send OTP</button>
            </div>

            <div class="col-md-1">
                <span class="countdown_{{$parcel->id}}">120</span>
            </div>
        </div>



        <script>
            $(document).ready(function(){
                timecoundown(120);
            })
        </script>
          <input type="text" required class="form-control" name="otp_code" id="opt-code" onblur="parcelOTPCheck()" placeholder="OTP Code">
    @elseif(!empty($parcel->otp) && ($otp_permission->value == 'all' || $otp_permission->value == 'conditional'))
        <input type="text" required class="form-control" name="otp_code" id="opt-code" onblur="parcelOTPCheck()" placeholder="OTP Code">

    @endif

{{-- message-area --}}
    <input type="hidden" name="id" value="{{$parcel->id}}" id="delivery-parcel-id">
    <div class="form-group">
        <label class="form-label" for="area">{{ __('note') }}</label>
        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
    </div>
    <div class="form-group text-right">
        <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
    </div>
</form>
