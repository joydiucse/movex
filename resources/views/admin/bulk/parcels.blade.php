@foreach($parcels as $key => $parcel)
    <div class="nk-tb-item" id="row_{{$parcel->parcel_no}}">
        <input type="text" name="parcels[]" hidden value="{{$parcel->id}}">
        <div class="nk-tb-col">
            <span>{{ $key + 1 }}</span>
        </div>
        <div class="nk-tb-col tb-col-lg">
            {{$parcel->parcel_no}}
        </div>
        <div class="nk-tb-col">
            {{ $parcel->customer_name  }}
        </div>
        <div class="nk-tb-col">
            {{ $parcel->customer_address  }}
        </div>

        <div class="nk-tb-col nk-tb-col-tools">
            <ul class="nk-tb-actions gx-1">
                <li><a href="javascript:void(0)"  data-row="row_{{$parcel->parcel_no}}" class="btn btn-sm btn-danger delete-btn-remove" id="delete-btn-remove"><em class="icon ni ni-trash"></em></a></li>
            </ul>
        </div>
    </div><!-- .nk-tb-item -->
@endforeach
