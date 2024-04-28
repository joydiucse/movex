<div class="" id="parcelDetails">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class="text-center py-3  px-1">S/L</th>
            <th class="text-center py-3 ">Parcel Details</th>
        </tr>
        </thead>
        <tbody>
        @php
            $itemCount=0;
        @endphp
        @foreach ($percel as $key=>$item)
            @php
             if($item->pathao_consignment_id) continue;
             $itemCount++
            @endphp
            <tr class="position-relative">
                <td class="text-center pl-2 pr-2 font-weight-bold align-middle">
                    {{ $key+1 }}
                    <input type="hidden" name="selected_parcel_id[]" value="{{ $item->id }}">
                    <div class="position-absolute absolute-top-left w-100 h-100 sendingPathaoApiRowoverlay d-none" id="sendingPathaoApiRowoverlay_{{ $item->id }}"></div>
                </td>
                <td class="position-relative">
                    ID: <span class="text-primary">#{{ $item->parcel_no ?? '' }}</span>
                    <div class="">
                        <small>
                            <p class="text-muted mb-0"><small><span class="font-weight-bold">Marchant:</span> {{ $item->merchant->company ?? '' }}</p>
                            <p class="text-muted mb-0"><span class="font-weight-bold">Customer:</span> {{ $item->customer_name ?? '' }} {{ $item->customer_phone_number ? "({$item->customer_phone_number})" : '' }}</p>
                            <p class="text-muted mb-0"><span class="font-weight-bold">Customer Address:</span> {{ $item->customer_address ?? '' }}</p>

                        </small>
                        <div id="deliveryAreaWrap_{{$item->id}}">
                            @if($item->pathao_city && $item->pathao_zone )
                                @php
                                    $city=$item->pathaoCity->city_name ?? '';
                                    $zone=$item->pathaoZone->zone_name ?? '';
                                    $area=$item->pathaoArea->area_name ?? '';
                                @endphp

                                    @if($city && $zone )
                                        <p class="mb-0 text-primary fw-medium">
                                            <span class="">Pathao Delivery Area:</span>
                                            <span>{{$city}}</span>
                                            <i class="fa-solid fa-chevron-right "></i>
                                            <span>{{$zone}}</span>
                                            <i class="fa-solid fa-chevron-right "></i>
                                            <span>{{$area}}</span>
                                            <button class="btn  deliveryAreaEditBtn ms-2" onclick="editPathaoDeliveryAreaForm({{$item->id}}, 'edit')"><i class="fa-solid fa-pen-to-square"></i></button>
                                        </p>
                                    @else
                                        <input type="hidden" name="none-delivery-area-parcel[]" value="{{$item->id}}">
                                        <button class="btn  btn-primary p-0 px-1 my-1" onclick="editPathaoDeliveryAreaForm({{$item->id}})">Add Pathao Delivery Area</button>
                                    @endif

                            @else
                                <input type="hidden" name="none-delivery-area-parcel[]" value="{{$item->id}}">
                                <button class="btn  btn-primary p-0 px-1 my-1" onclick="editPathaoDeliveryAreaForm({{$item->id}})" id="editPathaoDeliveryAreaBtn_{{$item->id}}">Add Pathao Delivery Area</button>
                            @endif
                        </div>
                        <div id="pathaoDeliveryAreaForm_{{$item->id}}" class="d-none">
                            <div class="border p-3">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div class="flex-grow-1">
                                        <div class="row mr-2">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="">City <span class="text-primary">*</span></label>
                                                    <select id="city-live-search-{{$item->id}}" name="city" onchange="selectZone(true, {{$item->id}})" class="form-control form-control-md merchant-live-search" required> </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="">Zone <span class="text-primary">*</span></label>
                                                    <select id="zone-live-search-{{$item->id}}" name="zone" onchange="selectArea(true, {{$item->id}})" class="form-control form-control-md merchant-live-search" required> </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="">Area <span class="text-primary">*</span></label>
                                                    <select id="area-live-search-{{$item->id}}" name="area" class="form-control form-control-md merchant-live-search" > </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <button class="btn btn-primary text-19px px-2" id="savePathaoDeliveryArea_{{$item->id}}" onclick="savePathaoDeliveryArea({{$item->id}})"><i class="fa-solid fa-check"></i></button>
                                    </div>
                                </div>
                                <span class="text-danger" id="delivery_area_error_{{$item->id}}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute absolute-top-right h-100 d-flex align-items-center px-3" id="sendingStatus_{{$item->id}}"></div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($itemCount==0)
        <div class="text-center py-4 text-lg-center">
            No Parcels Found!
        </div>
    @endif
</div>

