<div class="" id="parcelDetails">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class="text-center py-3  px-1">S/L</th>
            <th class="text-center py-3 ">Parcel Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($percel as $key=>$item)
            <tr>
                <td class="text-center pl-2 pr-2 font-weight-bold align-middle">{{ $key+1 }}</td>
                <td>
                    ID: <span class="text-primary">#{{ $item->parcel_no ?? '' }}</span>
                    <div class="">
                        <small>
                            <p class="text-muted mb-0"><small><span class="font-weight-bold">Marchant:</span> {{ $item->merchant->company ?? '' }}</p>
                            <p class="text-muted mb-0"><span class="font-weight-bold">Customer:</span> {{ $item->customer_name ?? '' }} {{ $item->customer_phone_number ? "({$item->customer_phone_number})" : '' }}</p>
                        </small>
                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

