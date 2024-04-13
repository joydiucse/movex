@extends('master')

@section('title')
    {{__('third_parties')}} {{__('lists')}}
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
                                    <p>{{__('you_have_total')}} {{ !blank($third_parties)? $third_parties->total():'0' }} {{__('third_parties')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                @if(hasPermission('third_party_create'))
                                    <a href="{{route('admin.third-party.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('name')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('phone_number')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('address')}}</strong></span></div>
                                            @if(hasPermission('third_party_update'))
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            @endif
                                            @if(hasPermission('third_party_update') || hasPermission('third_party_delete'))
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                        @foreach($third_parties as $key => $third_party)
                                            <div class="nk-tb-item" id="row_{{$third_party->id}}">
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>{{$key + 1}}</span>
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{ $third_party->name }}
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{ $third_party->phone_number }}
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{ $third_party->address }}
                                                </div>
                                                @if(hasPermission('third_party_update'))
                                                    <div class="nk-tb-col tb-col-lg">
                                                        <div class="preview-block">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input {{ hasPermission('third_party_update') ? 'status-change' : ''}}" {{ $third_party->status ? 'checked' :'' }} value="third-party-status/{{$third_party->id}}" id="customSwitch2-{{$third_party->id}}">
                                                                <label class="custom-control-label" for="customSwitch2-{{$third_party->id}}"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(hasPermission('third_party_update') || hasPermission('third_party_delete'))
                                                <div class="nk-tb-col nk-tb-col-tools">

                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        @if(hasPermission('third_party_update'))
                                                                            <li><a href="{{route('admin.third-party.edit', $third_party->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('third_party_delete'))
                                                                            <li><a href="javascript:void(0);" onclick="delete_row('third-party-delete/', {{$third_party->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
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
                                            {!! !blank($third_parties)? $third_parties->links():'' !!}
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
@include('common.change-status-ajax')
