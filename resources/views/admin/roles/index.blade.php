@extends('master')

@section('title')
{{__('role')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ $roles->total() }} {{__('roles')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('role_create'))
                                <a href="{{route('roles.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                            @endif
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('role')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('permissions')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    @foreach($roles as $key => $role)
                                    <div class="nk-tb-item" id="row_{{$role->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{!! $role->name !!}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            @if(!empty($role->permissions))
                                                <label class="label label-default"><span>{{count($role->permissions)}}</span></label>
                                            @else
                                            <label class="label label-default"><span>{{__('no_permission')}}</span></label>
                                            @endif
                                        </div>

                                        <div class="nk-tb-col nk-tb-col-tools">
                                            @if($role->id != 1)
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                @if(hasPermission('role_update'))
                                                                <li><a href="{{route('roles.edit', $role->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                @endif
                                                                @if(hasPermission('role_delete'))
                                                                <li><a href="javascript:void(0);" onclick="delete_row('roles/', {{$role->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                             @endif
                                        </div>


                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! $roles->links() !!}
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
