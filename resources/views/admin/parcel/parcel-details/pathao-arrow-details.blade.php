@php
    $city=$parcel->pathaoCity->city_name ?? '';
    $zone=$parcel->pathaoZone->zone_name ?? '';
    $area=$parcel->pathaoArea->area_name ?? '';
@endphp

@if($city && $zone && $area )
    <p class="mb-0 text-primary " id="deliveryAreaWrap_{{$parcel->id}}">
        <span class="font-weight-bold">Pathao Delivery Area:</span>
        <span>{{$city}}</span>
        <i class="fa-solid fa-chevron-right "></i>
        <span>{{$zone}}</span>
        <i class="fa-solid fa-chevron-right "></i>
        <span>{{$area}}</span>
        <button class="btn  deliveryAreaEditBtn ms-2" onclick="editPathaoDeliveryAreaForm({{$parcel->id}}, 'edit')" id="editPathaoDeliveryAreaBtn_{{$parcel->id}}"><i class="fa-solid fa-pen-to-square"></i></button>
    </p>
@endif
