@extends('master')

@section('title')
{{__('user')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ !blank($users)? $users->total():'0' }} {{__('users')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('user_create'))
                                <a href="{{route('user.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('user')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('hub')}}</strong></span></div>
                                        <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('last_login')}}</strong></span></div>
                                        @if(hasPermission('user_update'))
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                        @endif
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                    </div><!-- .nk-tb-item -->
                                    @foreach($users as $key => $user)
                                    <div class="nk-tb-item" id="row_{{$user->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col column-max-width">
                                            <a href="{{route('detail.staff.personal.info', $user->id)}}">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-primary">
                                                        @if(!blank($user->image) && file_exists($user->image->image_small_two))
                                                            <img src="{{asset($user->image->image_small_two)}}" alt="{{$user->first_name}}">
                                                        @else
                                                        <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$user->first_name}}">
                                                        @endif
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="tb-lead">{{$user->first_name.' '.$user->last_name}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                        <span>{{$user->email}}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="nk-tb-col tb-col-lg">
                                            {{@$user->hub ? $user->hub->name.' ('.$user->hub->address.')':''}}
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            {{$user->last_login != ""? date('M d, Y h:i a', strtotime($user->last_login)):''}}
                                        </div>
                                        @if(hasPermission('user_update'))
                                        <div class="nk-tb-col">
                                            <div class="preview-block">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input status-change" {{ $user->status ? 'checked' :'' }} value="user-status/{{$user->id}}" id="customSwitch2-{{$user->id}}">
                                                    <label class="custom-control-label" for="customSwitch2-{{$user->id}}"></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            @if($user->id != 1)
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                @if(hasPermission('user_update'))
                                                                <li><a href="{{route('user.edit', $user->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                @endif
                                                                @if(hasPermission('user_delete'))
                                                                <li><a href="javascript:void(0);" onclick="delete_row('user/delete/', {{$user->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
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
                                        {!! !blank($users)? $users->links():'' !!}
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
