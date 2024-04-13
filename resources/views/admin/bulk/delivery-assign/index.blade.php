@extends('master')

@section('title')
    {{__('assign_delivery') }}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('assign_delivery')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($assign_list) }} {{__('assign_delivery')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner p-0" id="page-data">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('delivery_man')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total_percel')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Status')}}/{{__('Date')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Person')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>

                                        </div><!-- .nk-tb-item -->
                                        @foreach($assign_list as $assign)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{ $val++ }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                    <span class="tb-lead text-info">{{ $assign->deliveryMan->user->first_name  }}</span>

                                                <table>
                                                    <tr> <td class="tb-lead">Phone Number : {{ $assign->deliveryMan->phone_number}}</td></tr>
                                                    <tr> <td class="tb-lead">Address : {{ $assign->deliveryMan->address }}</td></tr>

                                                </table>
                                            </div>

                                            <div class="nk-tb-col">
                                                @php $total_parcel= DB::table('parcel_delivers')->where('batch_no', $assign->batch_no)->where('status', "!=", "reversed")->groupBy('batch_no')->count(); @endphp
                                                <span class="text-info text-center"> {{  $total_parcel }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                @if($assign->status == "pending")
                                                <span class="text-warning text-uppercase">  {{ $assign->status  }} </span>
                                                @else
                                                <span class="text-success text-uppercase">  {{ $assign->status  }} </span>
                                                @endif
                                                <table>
                                                    <tr>
                                                        <td class="tb-lead">Created Date: {{ $assign->created_at->format('Y-m-d') }} </td>
                                                        <td class="tb-lead">Processed Date: {{ ($assign->updated_at && $assign->status == "processed") ? $assign->updated_at->format('Y-m-d'): '' }} </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="nk-tb-col">
                                                <table>
                                                    <tr> <td class="tb-lead">Created by : {{ $assign->returnAssignMan->first_name }} {{ $assign->returnAssignMan->last_name }}</td></tr>
                                                    <tr><td class="tb-lead">Process by : {{ ($assign->processed_by) ? $assign->precessMan->first_name." ".$assign->precessMan->last_name : '' }} </td></tr>
                                                </table>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    @if($assign->status == "pending")
                                                    <li><a href="{{ route('parcel.delivery-assign.eidt', $assign->batch_no ) }}"   class="btn btn-sm btn-danger"><em class="icon ni ni-edit"></em></a></li>
                                                    @else
                                                    <li><a href="{{ route('parcel.delivery-assign.eidt', $assign->batch_no ) }}"   class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
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
                                            {!! $assign_list->appends(Request::except('page'))->links() !!}
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
