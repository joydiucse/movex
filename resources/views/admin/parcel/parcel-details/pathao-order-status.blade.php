<div class="position-relative">
    <div class="w-100 h-100 position-absolute absolute-top-left"  style="background-color: {{$status->color}}; opacity: 0.05"></div>
    <div class="text-white text-center p-1 text-18px" style="background: {{$status->color}}">
        Status: <span class="fw-bold">{{$status->order_status}}</span>
    </div>
    <div class="p-2">
        <p><span class="fw-medium">Order Id:</span> {{$status->merchant_order_id}}</p>
        <p>Recipient Address: {{$status->recipient_address}}</p>
        <p class="fw-medium" style="color: {{$status->color}}">Delivery Fee: {{$status->delivery_fee}}</p>
    </div>
</div>
