@extends('master')

@section('title')
    {{__('payment_logs')}}
@endsection

@section('mainContent')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('payment_logs')}}</h3>

                        </div><!-- .nk-block-head-content -->

                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <form action="{{ route('admin.search.statements')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-inner">
                                    <div class="row g-gs">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">{{__('start_date')}} *</label>
                                                <div class="form-control-wrap focused">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar-alt"></em>
                                                    </div>
                                                    <input type="text" class="form-control date-picker" required name="start_date" autocomplete="off" placeholder="{{__('start_date')}}">
                                                </div>
                                                @if($errors->has('start_date'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('start_date') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">{{__('end_date')}} *</label>
                                                <div class="form-control-wrap focused">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar-alt"></em>
                                                    </div>
                                                    <input type="text" class="form-control date-picker" required name="end_date" autocomplete="off" placeholder="{{__('end_date')}}">
                                                </div>
                                                @if($errors->has('end_date'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('end_date') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">{{__('select_type')}} *</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select form-control form-control-lg search-type" required name="type">
                                                        <option value="">{{ __('select_type') }}</option>
                                                        <option value="company">{{__('company')}}</option>
                                                        <option value="merchant">{{__('merchant')}}</option>
                                                        <option value="delivery-man">{{__('delivery_man')}}</option>
                                                    </select>
                                                </div>
                                                @if($errors->has('type'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('type') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 d-none" id="merchant-deliveryman-area">
                                        <div class="col-sm-4 d-none" id="merchant-area">
                                            <div class="form-group">
                                                <label class="form-label">{{__('merchant')}}</label>
                                                <div class="form-control-wrap">
                                                    <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search">
{{--                                                    <select class="form-select form-control form-control-lg" name="merchant">--}}
                                                        <option value="">{{ __('select_merchant') }}</option>
                                                        @foreach($merchants as $merchant)
                                                                <option value="{{ $merchant->id }}">{{ $merchant->user->first_name.' '.$merchant->user->last_name }} ({{$merchant->company}})</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-none" id="delivery-area">
                                            <div class="form-group">
                                                <label class="form-label">{{__('delivery_man')}}</label>
                                                <div class="form-control-wrap">
                                                    <select id="delivery-man-live-search" name="delivery_man" class="form-control form-control-lg delivery-man-live-search">
{{--                                                    <select class="form-select form-control form-control-lg" name="delivery_man">--}}
                                                        <option value="">{{ __('select_delivery_man') }}</option>
                                                        @foreach($delivery_men as $delivery_man)
                                                                <option value="{{ $delivery_man->id }}">{{ $delivery_man->user->first_name.' '.$delivery_man->user->last_name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-sm-12 text-right">
                                            <div class="form-group">
                                                <button type="submit" class="btn d-md-inline-flex btn-primary">{{{__('search')}}}</button>
                                            </div>
                                        </div>
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

@if(isset($statements))
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{__('you_have_total')}} {{ $statements->total() }} {{__('payment_logs')}}.</p>
                            </div>
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
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        @if($type == 'merchant')
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                        @elseif($type == 'delivery-man')
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('delivery_man')}}</strong></span></div>
                                        @endif
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('source')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('completed_at')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{ __('tk') }})</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    @foreach($statements as $key => $statement)
                                        <div class="nk-tb-item" id="row_{{$statement->id}}">
                                            <div class="nk-tb-col">
                                                <span>{{$key + 1}}</span>
                                            </div>
                                            @if($type == 'merchant')
                                            <div class="nk-tb-col column-max-width">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-primary">
                                                        @if(!blank($statement->merchant->user->image) && file_exists($statement->merchant->user->image->image_small_two))
                                                            <img src="{{asset($statement->merchant->user->image->image_small_two)}}" alt="{{$statement->merchant->user->first_name}}">
                                                        @else
                                                            <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$statement->merchant->user->first_name}}">
                                                        @endif
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="tb-lead">{{$statement->merchant->user->first_name.' '.$statement->merchant->user->last_name .'('.$statement->merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                        <span>{{$statement->merchant->user->email}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($type == 'delivery-man')
                                            <div class="nk-tb-col column-max-width">
                                            <div class="user-card">
                                                <div class="user-avatar bg-primary">
                                                    @if(!blank($statement->delivery_man->user->image) && file_exists($statement->delivery_man->user->image->image_small_two))
                                                        <img src="{{asset($statement->delivery_man->user->image->image_small_two)}}" alt="{{$statement->delivery_man->user->first_name}}">
                                                    @else
                                                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$statement->delivery_man->user->first_name}}">
                                                    @endif
                                                </div>
                                                <div class="user-info">
                                                    <span class="tb-lead">{{$statement->delivery_man->user->first_name.' '.$statement->delivery_man->user->last_name}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                    <span>{{$statement->delivery_man->user->email}}</span>
                                                </div>
                                            </div>
                                            </div>
                                            @endif
                                            <div class="nk-tb-col column-max-width">

                                                <span>{{ __($statement->details) }}</span><br>
                                                @if($statement->parcel != "")
                                                {{__('id')}}:<span>#{{ __(@$statement->parcel->parcel_no) }}</span>
                                                @endif
                                            </div>
                                            <div class="nk-tb-col column-max-width">
                                                <span>{{ __($statement->source) }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                {{$statement->created_at != ""? date('M d, Y h:i a', strtotime($statement->created_at)):''}}
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="{{$statement->amount < 0? 'text-danger':''}}">{{ number_format($statement->amount,2) }}</span>
                                            </div>
                                        </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div>
                                <!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! $statements->appends(Request::except('page'))->links() !!}
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
@endif

@endsection
@push('script')

<script>
    $(document).ready(function(){
        $('.search-type').on('change', function(){
            if($(this).val() == 'merchant'){
                $('#merchant-deliveryman-area').removeClass('d-none');
                $('#merchant-area').removeClass('d-none');
                $('#delivery-area').addClass('d-none');
            }else if($(this).val() == 'delivery-man'){
                $('#merchant-deliveryman-area').removeClass('d-none');
                $('#delivery-area').removeClass('d-none');
                $('#merchant-area').addClass('d-none');
            }else{
                $('#merchant-deliveryman-area').addClass('d-none');
            }
        })
    })
</script>

@endpush
@include('live_search.merchants')
@include('live_search.delivery-man')
