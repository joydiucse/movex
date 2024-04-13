@extends('master')

@section('title')
    {{__('hub')}} {{__('lists')}}
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
                                    <p>{{__('you_have_total')}} {{ !blank($hubs)? $hubs->total():'0' }} {{__('hubs')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                @if(hasPermission('hub_create'))
                                    <a href="{{route('admin.hub.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('incharge')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('name')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('phone_number')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('address')}}</strong></span></div>
                                            @if(hasPermission('hub_update') || hasPermission('hub_delete'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                        @foreach($hubs as $key => $hub)
                                            <div class="nk-tb-item" id="row_{{$hub->id}}">
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <span>{{__('name')}}: {{$hub->user->first_name.' '.$hub->user->last_name}}</span><br>
                                                    <span>{{__('email')}}: {{$hub->user->email}}</span><br>
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{ $hub->name }}
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{ $hub->phone_number }}
                                                </div>

                                                <div class="nk-tb-col tb-col-lg">
                                                    {{ $hub->address }}
                                                </div>

                                                <div class="nk-tb-col nk-tb-col-tools">

                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        @if(hasPermission('hub_update'))
                                                                            <li><a href="{{route('admin.hub.edit', $hub->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('hub_delete'))
                                                                            <li><a href="javascript:void(0);" onclick="delete_row('hub-delete/', {{$hub->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>

                                                </div>

                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! !blank($hubs)? $hubs->links():'' !!}
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

@push('script')
    @include('common.delete-ajax')
@endpush
