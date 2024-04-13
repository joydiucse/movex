<div class="card-inner p-0">
    <div class="nk-tb-list nk-tb-ulist">
        <div class="nk-tb-item nk-tb-head">
            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('merchant') }}</strong></span></div>
            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('total_percel') }}</strong></span></div>
            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('Status') }}</strong></span></div>
            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('Person') }}</strong></span></div>
            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('options') }}</strong></span></div>

        </div><!-- .nk-tb-item -->
        @foreach ($parcels as $parcel)
            <div class="nk-tb-item">
                <div class="nk-tb-col">
                    <span>{{ $val++ }}</span>
                </div>
                <div class="nk-tb-col">
                    <span class="tb-lead text-info">{{ $parcel->merchant->company }}</span>

                    <table>
                        <tr>
                            <td class="tb-lead">Phone Number : {{ $parcel->merchant->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="tb-lead">Address : {{ $parcel->merchant->address }}</td>
                        </tr>

                    </table>
                </div>

                <div class="nk-tb-col">
                    @php
                        $total_parcel = DB::table('parcel_returns')
                            ->where('batch_no', $parcel->batch_no)
                            ->where('status', '!=', 'reversed')
                            ->groupBy('batch_no')
                            ->count();
                    @endphp
                    <span class="text-info text-center"> {{ $total_parcel }}</span>
                </div>

                <div class="nk-tb-col">
                    @if ($parcel->status == 'pending')
                        <span class="text-warning text-uppercase"> {{ $parcel->bulk_status }} </span>
                    @else
                        <span class="text-success text-uppercase"> {{ $parcel->bulk_status }} </span>
                    @endif
                    <table>
                        <tr>
                            <td class="tb-lead">Created Date: {{  date('Y-m-d', strtotime($parcel->bulk_creatd_at)) }}</td>
                            <td class="tb-lead">Processed Date: {{ ($parcel->bulk_updated_at) ? date('Y-m-d', strtotime($parcel->bulk_updated_at)): '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="nk-tb-col">

                    <table>
                        <tr>
                            <td><span class="tb-lead text-info"> Delivery Man:
                                    {{ $parcel->deliveryMan->user->first_name }}
                                    {{ $parcel->deliveryMan->user->last_name }}
                                    ({{ $parcel->deliveryMan->phone_number }})</span> </td>
                        </tr>
                        <tr>
                            <td class="tb-lead">Created by : {{ $parcel->returnAssignMan->first_name }}
                                {{ $parcel->returnAssignMan->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="tb-lead">Process by :
                                {{ $parcel->processed_by ? $parcel->precessMan->first_name . ' ' . $parcel->precessMan->last_name : '' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="nk-tb-col nk-tb-col-tools">
                    <ul class="nk-tb-actions gx-1">
                        @if ($parcel->status == 'pending')
                            <li><a href="{{ route('merchant.return.eidt', $parcel->batch_no) }}"
                                    class="btn btn-sm btn-danger"><em class="icon ni ni-edit"></em></a></li>
                        @else
                            <li><a href="{{ route('merchant.return.eidt', $parcel->batch_no) }}"
                                    class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
                        @endif
                    </ul>
                </div>
            </div><!-- .nk-tb-item -->
        @endforeach

    </div>
    <!-- .nk-tb-list -->
</div><!-- .card-inner -->
