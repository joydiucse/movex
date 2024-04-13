@extends('master')

@section('title')
    {{__('edit_history') }}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('merchant') }} {{__('edit_history')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($merchant_logs) }} {{__('edit_history')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    {{-- @if(isset(request()->status) && request()->status !='')
                                    <a href="{{ route('name-change-requests') }}" class="btn btn-primary  d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                    @endif --}}
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner" id="page-data">

                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg">
                                                <span class="sub-text"> <strong>{{__('merchant')}} :</strong><strong class="text-info ml-2">{{ $merchant->company }} </strong></span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="sub-text"><strong>{{__('phone_number')}}</strong> <strong class="text-info ml-2">: {{ (hasPermission('merchant_read_phone')) ? $merchant->phone_number: Str::mask($merchant->phone_number, "*", 3).Str::substr($merchant->phone_number, -2) }}</strong></span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="sub-text"> <strong> {{__('address')}} </strong> <strong class="text-info ml-2"> :  {{ (hasPermission('merchant_read_phone')) ?  $merchant->city." ". $merchant->address:  Str::mask($merchant->address, "*", 3) }} </strong></span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span class="sub-text"> <strong>{{__('website')  }}</strong> <strong class="text-info ml-2"> : {{ $merchant->website }} </strong></span>

                                            </div>



                                        </div><!-- .nk-tb-item -->

                                    </div>



                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#No</strong></span></div>
                                            <div class="nk-tb-col text-center"><span class="sub-text"><strong>{{__('Changes')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Changed')}} {{__('by')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('Changed')}} {{__('date') }}</strong></span></div>

                                        </div><!-- .nk-tb-item -->
                                        @foreach($merchant_logs as $log)
                                            @php
                                                $previous_data = (array) json_decode($log->previous_data);
                                                $edited_data = (array) json_decode($log->edited_data);
                                                $i=0;
                                                $j;
                                            @endphp
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{ $val++ }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <table class="table table-borderless custom-table-td">
                                                    <tr>
                                                        <th>{{__('title')}}</th>
                                                        <th>{{__('privious_data') }}</th>
                                                        <th>{{__('new_data')}}</th>
                                                    </tr>
                                                    @while ($i < 5)
                                                       @php
                                                        if($i == 0)
                                                            $j="user";
                                                        elseif($i == 1)
                                                            $j="merchant";
                                                        elseif($i == 2)
                                                            $j="cod_charges";
                                                        elseif($i == 3)
                                                            $j="charges";
                                                        else
                                                            $j="account";
                                                        @endphp
                                                        @foreach($previous_data[$j] as $key => $data)

                                                           @if($key != 'permissions' && $key !='charges' && $key != 'cod_charges' && $key !='hub_id' && $key !='updated_at' && $key !='created_at' )

                                                                @if($j =='charges')

                                                                    @php
                                                                        $old_charge  = json_decode(json_encode($data), true);
                                                                        $new_charge = json_decode(json_encode($edited_data[$j]->$key), true);
                                                                    @endphp
                                                                 @foreach($old_charge as $k=> $charge)
                                                                    @if($charge != $new_charge[$k])
                                                                            <tr>
                                                                                <td>{{$key}} (weight)</td>

                                                                            @break;
                                                                    @endif
                                                                 @endforeach


                                                                    <td>
                                                                        @foreach($old_charge as $k=> $charge)
                                                                            @if($charge != $new_charge[$k])
                                                                            <table>
                                                                                <tr>
                                                                                    <td  class="text-left" width="150px">{{__($k) }}</td>
                                                                                    <td>:</td>
                                                                                    <td>{{__($charge)}}</td>
                                                                                </tr>
                                                                            </table>
                                                                            @endif
                                                                        @endforeach
                                                                   </td>

                                                                   <td>
                                                                    @foreach($old_charge as $k=> $charge)
                                                                        @if($charge != $new_charge[$k])
                                                                        <table>
                                                                            <tr>
                                                                                <td class="text-left" width="150px">{{__($k)}}</td>
                                                                                <td>:</td>
                                                                                <td>{{ $new_charge[$k]}}</td>
                                                                            </tr>
                                                                        </table>

                                                                        @endif
                                                                    @endforeach
                                                                   </td>

                                                                @foreach($old_charge as $k=> $charge)
                                                                   @if($charge != $new_charge[$k])
                                                                        </tr>
                                                                        @break;
                                                                   @endif
                                                                @endforeach

                                                                @elseif($data !== $edited_data[$j]->$key)
                                                                <tr>
                                                                        <td>{{__($key)}}</td>
                                                                        <td>{{ trim($data) }}</td>
                                                                        <td>{{ trim($edited_data[$j]->$key) }}</td>

                                                                </tr>
                                                                @endif
                                                            @endif

                                                        @endforeach
                                                        @php  $i++ @endphp
                                                    @endwhile

                                                </table>
                                            </div>


                                            <div class="nk-tb-col">
                                                {{ $log->user->first_name }}  {{ $log->user->last_name }}
                                            </div>
                                            <div class="mt-3">
                                                {{ $log->created_at->format('Y-m-d') }}
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
                                            {!! $merchant_logs->appends(Request::except('page'))->links() !!}
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
    //name change process
    function nameChangeProcess(id)
    {
         var url =  $('#url_'+id).val();
         Swal.fire({
            title: 'Are you sure?',
            text: "You won't process this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then((result) => {
            if (result.isConfirmed) {
                    window.location.href = url;
                }else{
                    return false;
                }
            })

    }

    //delete reque4st
    function nameChangeDelete(id)
    {
        var url =  $('#delete_url_'+id).val();
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't delete this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
        }).then((result) => {
        if (result.isConfirmed) {
                window.location.href = url;
            }else{
                return false;
            }
        })
    }
 </script>
@endpush
@include('live_search.merchants')
@include('admin.withdraws.status-ajax')
