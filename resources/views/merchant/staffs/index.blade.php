@extends('master')

@section('title')
    {{__('staffs').' '.__('lists')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('staffs')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ $staffs->total() }} {{__('staffs')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{route('merchant.staff.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('user')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('current_balance')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('phone_number')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('last_login')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('actions')}}</strong></span></div>
                                        </div><!-- .nk-tb-item -->
                                        @foreach($staffs as $key => $staff)
                                            <div class="nk-tb-item" id="row_{{$staff->id}}">
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col column-max-width">
                                                    <a href="{{route('merchant.staff.personal.info', $staff->id)}}">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-primary">
                                                                @if(!blank($staff->image) && file_exists($staff->image->image_small_two))
                                                                    <img src="{{asset($staff->image->image_small_two)}}" alt="{{$staff->first_name}}">
                                                                @else
                                                                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$staff->first_name}}">
                                                                @endif
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{$staff->first_name.' '.$staff->last_name}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                                <span>{{$staff->email}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    <td class="tb-col-os"><span>{{ number_format($staff->balance($staff->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small> </span></td>
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{@$staff->phone_number}}
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    {{$staff->last_login != ""? date('M d, Y h:i a', strtotime($staff->last_login)):''}}
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <div class="preview-block">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input status-change" {{ $staff->status ? 'checked' :'' }} value="user-status/{{$staff->id}}" id="customSwitch2-{{$staff->id}}">
                                                                <label class="custom-control-label" for="customSwitch2-{{$staff->id}}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <div class="tb-odr-btns d-md-inline">
                                                        <a href="{{route('merchant.staff.edit', $staff->id)}}" class="btn btn-sm btn-info btn-tooltip" data-original-title="{{__('edit')}}"><em class="icon ni ni-edit"></em></a>
                                                    </div>
                                                </div>


                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $staffs->appends(Request::except('page'))->links() !!}
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
@endsection

@include('merchant.delete-ajax')
@include('merchant.staffs.change-status')
