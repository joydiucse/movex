@extends('master')

@section('title')
    {{__('assign_delivery')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('assign_delivery')}} {{__('list')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($assign_parcel) }} {{__('assign_delivery')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{-- route('bulk.return.parcel.update') --}}/" class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="alert   fade show text-dark message-box" style="display: none">
                                                <strong class="response"></strong>
                                           </div>
                                            {{-- merchant --}}
                                            <div class="nk-tb-list nk-tb-ulist">
                                                <div class="nk-tb-item nk-tb-head ">
                                                    <div class="nk-tb-col tb-col-lg">
                                                        <span class="sub-text"><strong class="text-dark">Delivery Man : {{ $delivery_man->user->first_name }} {{ $delivery_man->user->last_name }} </strong></span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-dark">Delivery Man Phone : {{ $delivery_man->phone_number }}</strong></span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-dark">Delivery Man Address :  {{ $delivery_man->address }}</strong></span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-dark">{{__('create_by')  }} : {{ $bulk_delivery->returnAssignMan->first_name }} {{ $bulk_delivery->returnAssignMan->last_name }}</strong></span>
                                                        <span class="sub-text"><strong class="text-dark">{{__('process_by')}} : {{ ($bulk_delivery->processed_by) ? $bulk_delivery->precessMan->first_name." ".$bulk_delivery->precessMan->last_name : '' }}</strong></span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-dark">{{__('create_date')}} : {{ $bulk_delivery->created_at->format('Y-m-d') }}</strong></span>
                                                        <span class="sub-text"><strong class="text-dark">{{__('process_date')}} : {{ ($bulk_delivery->updated_at && $bulk_delivery->status == "processed") ? $bulk_delivery->updated_at->format('Y-m-d') : '' }}</strong></span>
                                                    </div>

                                                </div><!-- .nk-tb-item -->

                                            </div>

                                            @if($bulk_delivery->status =='pending')
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="barcode">{{__('scan_barcode')}} </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control barcode" id="barcode" data-url="admin/add-parcel-row/" value="{{ old('barcode') }}" name="barcode">
                                                    </div>
                                                    @if($errors->has('barcode'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('barcode') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="nk-tb-list nk-tb-ulist mt-5" id="parcels" data-val="{{ count($assign_parcel) }}">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('customer_name')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('customer_address') }}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('action')}}</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                            <input type="hidden" value="{{ $delivery_man->id }}" name="delivery_man">
                                            <input type="hidden" value="{{ $bulk_delivery->batch_no }}" name="batch_no">
                                            @foreach($assign_parcel as $parcel)
                                            <div class="nk-tb-item" id="row_{{ $parcel->parcel->id }}">
                                                <input type="hidden" value="{{ $parcel->parcel->id }}" name="parcel_list[]">
                                                <div class="nk-tb-col">
                                                    <span>{{ $val++ }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{$parcel->parcel->parcel_no}}
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{$parcel->parcel->customer_name}}
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{ $parcel->parcel->customer_address  }}
                                                </div>

                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        @if($bulk_delivery->status =='pending')
                                                        <li><button type="button" class="btn btn-sm btn-danger" onclick="ParcelReverse('{{ $parcel->parcel->id }}')"><em class="icon ni ni-trash"></em></button></li>
                                                        @else
                                                        <li><a href="{{ route('admin.parcel.detail', $parcel->parcel->parcel_no) }}"   class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div><!-- .nk-tb-item -->
                                            @endforeach
                                        </div><!-- .nk-tb-list -->

                                        <div class="row">
                                            <div class="col-md-12 text-right mt-4">
                                                @if($bulk_delivery->status =="pending")
                                                <div class="form-group ">
                                                    <button type="submit" name="hold" class="btn btn-success d-md-inline-flex resubmit mr-3" onclick="formActionChange('hold')">{{__('save_to_draft')}}</button>
                                                    <button type="submit" name="submit" class="btn btn-primary d-md-inline-flex resubmit" onclick="formActionChange('submit')">{{__('confirm')}} & {{__('print')}}</button>
                                                </div>
                                                @else
                                                <div class="form-group ">
                                                    <button type="submit" name="submit" class="btn btn-primary d-md-inline-flex resubmit" onclick="formActionChange('print')">{{__('print')}}</button>
                                                </div>
                                                @endif

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function ParcelReverse(id)
        {
            if(id !=''){
                $.ajax({
                    type: "post",
                    data: {'id': id, 'status': "received"},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    //url: "{{ route('merchant.return.reverse') }}",
                    url: "{{ route('parcel.delivery-assign.reverse') }}",
                    success: function (data) {
                        // Swal.fire('Oops...', data, 'error');
                            $('#row_'+id).remove();
                        console.log(data);
                    },
                    error: function (data) {
                    }
                });
            }
        }
    </script>
@endpush
@push('script')
<!-- Otp code script -->
<script>
        //from acction page change

        function formActionChange(action_name)
        {
           if(action_name =='hold'){
                //$("#parcel-form").action("{{ route('bulk.return.parcel.update') }}");
                document.getElementById("parcel-form").action = "{{ route('bulk.delivery-assign.add') }}";
                $('#parcel-form').submit();
           }else if(action_name == 'submit'){
                document.getElementById("parcel-form").action = "{{ route('bulk.delivery-assign.add') }}";
                $('#parcel-form').submit();
           }else if(action_name == 'print'){
                document.getElementById("parcel-form").action = "{{ route('bulk.delivery-assign.print') }}";
                $('#parcel-form').submit();
           }
        }

 </script>
@endpush
@include('admin.bulk.bulk-script')
