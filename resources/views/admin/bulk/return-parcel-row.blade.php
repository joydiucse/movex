<div class="nk-tb-item" id="row_{{$parcel->parcel_no}}">
    <input type="text" name="parcel_list[]" hidden value="{{$parcel->id}}">
    <div class="nk-tb-col">
        <span>{{ $val }}</span>
    </div>
    <div class="nk-tb-col tb-col-lg">
        {{$parcel->parcel_no}}
    </div>
    <div class="nk-tb-col tb-col-lg">
        {{$parcel->customer_invoice_no}}
    </div>
    <div class="nk-tb-col">
        <p> {{__('name')}} : {{  $parcel->merchant->company  }}</p>
        <p>{{__('address') }}: {{ $parcel->merchant->address  }}</p>
    </div>
    <div class="nk-tb-col">
        <p> {{__('name')}} : {{ $parcel->customer_name  }}</p>
        <p>{{__('phone') }}: {{ $parcel->customer_phone_number  }}</p>
    </div>
    <div class="nk-tb-col">
        {{ $parcel->status  }}
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">
            <li><a href="javascript:void(0)"  data-row="row_{{$parcel->parcel_no}}" class="btn btn-sm btn-danger delete-btn-remove" id="delete-btn-remove"><em class="icon ni ni-trash"></em></a></li>
        </ul>
    </div>
</div><!-- .nk-tb-item -->
