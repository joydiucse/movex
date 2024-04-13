@extends('master')

@section('title')
    {{__('return_list') }}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('return_list')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($parcels) }} {{__('return_merrchant')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <a href="{{ route('create-return-batch') }}" class="btn btn-primary  d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('create_batch')}}</span></a>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    <div class="card-title-group">
                                        <div class="card-tools">
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                    <form action="{{route('parcel.return.list.filter')}}" method="GET">
                                        <div class="row g-gs">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search"> </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select class="form-select form-select-sm" name="status">
                                                        <option value="all">{{__('All')}}</option>
                                                        <option value="pending">{{__('Pending')}}</option>
                                                        <option value="processed">{{__('Processed')}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <button type="submit" class="btn btn-primary">{{__('filter')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0" id="page-data">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total_percel')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Status')}}/{{__('Date')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Person')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>

                                        </div><!-- .nk-tb-item -->
                                        @foreach($parcels as $parcel)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{ $val++ }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                    <span class="tb-lead text-info">{{ $parcel->merchant->company  }}</span>

                                                <table>
                                                    <tr> <td class="tb-lead">Phone Number : {{ (hasPermission('merchant_read_phone')) ?  $parcel->merchant->phone_number : Str::mask($parcel->merchant->phone_number, "*", 3).Str::substr($parcel->merchant->phone_number, -2) }}</td></tr>
                                                    <tr> <td class="tb-lead">Address : {{ (hasPermission('merchant_read_address')) ?  $parcel->merchant->address : Str::mask($parcel->merchant->address, "*", 3) }}</td></tr>

                                                </table>
                                            </div>

                                            <div class="nk-tb-col">
                                                @php $total_parcel= DB::table('parcel_returns')->where('batch_no', $parcel->batch_no)->where('status', "!=", "reversed")->groupBy('batch_no')->count(); @endphp
                                                <span class="text-info text-center"> {{  $total_parcel }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                @if($parcel->status == "pending")
                                                <span class="text-warning text-uppercase">  {{ $parcel->status  }} </span>
                                                @else
                                                <span class="text-success text-uppercase">  {{ $parcel->status  }} </span>
                                                @endif
                                                <table>
                                                    <tr>
                                                        <td class="tb-lead">Created Date: {{ $parcel->created_at->format('Y-m-d') }}</td>
                                                        <td class="tb-lead">Processed Date: {{ $parcel->updated_at->format('Y-m-d') }}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="nk-tb-col">

                                                <table>
                                                    <tr> <td><span class="tb-lead text-info"> Delivery Man: {{ $parcel->deliveryMan->user->first_name  }} {{ $parcel->deliveryMan->user->last_name }} ({{ $parcel->deliveryMan->phone_number }})</span> </td></tr>
                                                    <tr> <td class="tb-lead">Created by : {{ $parcel->returnAssignMan->first_name }} {{ $parcel->returnAssignMan->last_name }}</td></tr>
                                                    <tr><td class="tb-lead">Process by : {{ ($parcel->processed_by) ? $parcel->precessMan->first_name." ".$parcel->precessMan->last_name : '' }} </td></tr>
                                                </table>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    @if($parcel->status == "pending")
                                                    <li><a href="{{ route('merchant.return.eidt', $parcel->batch_no ) }}"   class="btn btn-sm btn-danger"><em class="icon ni ni-edit"></em></a></li>
                                                    @else
                                                    <li><a href="{{ route('merchant.return.eidt', $parcel->batch_no ) }}"   class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div><!-- .nk-tb-item -->
                                        @endforeach

                                    </div>
                                    <!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div  id="search-data"> </div> <!--  search-data-show -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $parcels->appends(Request::except('page'))->links() !!}
                                        </div>
                                    </div><!-- .nk-block-between -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
 <script>
    function searchMerchantReturn()
    {
       var merchant_name =  $('#merchant').val();
       $.ajax({
            type: "post",
            url : "{{ route('search.merchant.return') }}",
            data:{
                'merchant_name':  merchant_name,
                "_token": "{{ @csrf_token() }}"
            },
            success:function(data){
                $('#page-data').hide();
                $('#search-data').show();
                $('#search-data').html(data);
            }

       });
    }

    $(document).ready(function(){
        $('#merchant').keyup(function(){
            var merchant_name =  $('#merchant').val();
            if(merchant_name == ''){
                $('#page-data').show();
                $('#search-data').hide();
            }
       });
     });

     $('#merchant').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            searchMerchantReturn()
            return false;
        }
        });
 </script>
@endpush
@include('live_search.merchants')
@include('admin.withdraws.status-ajax')
