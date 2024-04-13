@extends('master')

@section('title')
    {{__('notice')}} {{__('lists')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ !blank($notices)? $notices->total():'0' }} {{__('notice')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                @if(hasPermission('notice_create'))
                                    <a href="{{route('notice.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                                @endif
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
                                            <div class="nk-tb-col tb-col-lg column-max-width"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                            <div class="nk-tb-col  tb-col-lg column-max-width"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                            @if(hasPermission('user_update'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            @endif
                                            @if(hasPermission('user_update'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('staff')}}</strong></span></div>
                                            @endif
                                            @if(hasPermission('user_update'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            @endif
                                            @if(hasPermission('notice_update') || hasPermission('notice_delete'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                        @foreach($notices as $key => $notice)
                                            <div class="nk-tb-item" id="row_{{$notice->id}}">
                                                <div class="nk-tb-col">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    {{$notice->title}}
                                                </div>
                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    {!! strip_tags(\Illuminate\Support\Str::limit($notice->details, 140)) !!}
                                                </div>
                                                <div class="nk-tb-col">
                                                    <div class="preview-block">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('notice_update') ? 'status-change' : '' }}" {{ $notice->status ? 'checked' :'' }} data-change-for="status" value="notice-status/{{$notice->id}}" id="customSwitch-{{$notice->id}}">
                                                            <label class="custom-control-label" for="customSwitch-{{$notice->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <div class="preview-block">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('notice_update') ? 'status-change' : '' }}" {{ $notice->staff ? 'checked' :'' }}  data-change-for="staff" value="notice-status/{{$notice->id}}" id="customSwitch-staff-{{$notice->id}}">
                                                            <label class="custom-control-label" for="customSwitch-staff-{{$notice->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <div class="preview-block">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('notice_update') ? 'status-change' : '' }}" {{ $notice->merchant ? 'checked' :'' }} data-change-for="merchant" value="notice-status/{{$notice->id}}" id="customSwitch-merchant-{{$notice->id}}">
                                                            <label class="custom-control-label" for="customSwitch-merchant-{{$notice->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(hasPermission('notice_update') || hasPermission('notice_delete'))
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        @if(hasPermission('notice_update'))
                                                                            <li><a href="{{route('notice.edit', $notice->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('notice_delete'))
                                                                            <li><a href="javascript:void(0);" onclick="delete_row('notice/delete/', {{$notice->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! !blank($notices)? $notices->links():'' !!}
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
@include('common.delete-ajax')
@include('admin.preference.change-status-ajax')
