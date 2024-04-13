@extends('master')

@section('title')
    {{__('name_change_request') }}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('name_change_request')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($changes) }} {{__('name_change_request')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    @if(isset(request()->status) && request()->status !='')
                                    <a href="{{ route('name-change-requests') }}" class="btn btn-primary  d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                    @endif
                                </div><!-- .toggle-wrap -->
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
                                        <div class="card-tools mr-n1">
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <div class="toggle-wrap">
                                                        <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-menu-right"></em></a>
                                                        <div class="toggle-content" data-content="cardTools">
                                                            <form action="{{route('name.change.filter')}}" method="GET">
                                                            {{--                                                                @csrf--}}
                                                            <ul class="btn-toolbar gx-1">
                                                                <li>
                                                                    <div class="dropdown">
                                                                        <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                            <div class="dot dot-primary"></div>
                                                                            <em class="icon ni ni-filter-alt"></em>
                                                                        </a>
                                                                        <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                            <div class="dropdown-head">
                                                                                <span class="sub-title dropdown-title">{{__('filter')}}</span>

                                                                            </div>
                                                                            <div class="dropdown-body dropdown-body-rg">
                                                                                <div class="row gx-6 gy-3">
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label class="form-label">{{ __('merchant') }} *</label>
                                                                                                <div class="form-control-wrap">
                                                                                                    <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search" required> </select>
                                                                                                </div>
                                                                                               
                                                                                                @if($errors->has('merchant'))
                                                                                                    <div class="nk-block-des text-danger">
                                                                                                        <p>{{ $errors->first('merchant') }}</p>
                                                                                                    </div>
                                                                                                @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="type">
                                                                                                <option value="all">{{__('request_for')}}</option>
                                                                                                <option value="company">{{__('company')}}</option>
                                                                                                <option value="shop">{{__('shop')}}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="status">
                                                                                                <option value="all">{{__('status')}}</option>
                                                                                                <option value="pending">{{__('Pending')}}</option>
                                                                                                <option value="accept">{{__('accept')}}</option>
                                                                                                <option value="decline">{{__('decline')}}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 text-right">
                                                                                        <div class="form-group">
                                                                                            <button type="submit" class="btn btn-primary">{{__('filter')}}</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div><!-- .filter-wg -->
                                                                    </div><!-- .dropdown -->
                                                                </li><!-- li -->
                                                            </ul><!-- .btn-toolbar -->
                                                            </form>
                                                        </div><!-- .toggle-content -->
                                                    </div><!-- .toggle-wrap -->
                                                </li><!-- li -->
                                            </ul><!-- .btn-toolbar -->
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->

                                <div class="card-inner" id="page-data">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_for')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('previous_name')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_name')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_by')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('process_by')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('request_date')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('process_date')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        </div><!-- .nk-tb-item -->
                                        @foreach($changes as $name)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{ $val++ }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span>{{ $name->merchant->company }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span class="text-primary">{{__($name->type) }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="text-capitalize text-info"><strong>{{__($name->old_name) }}</strong></span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span class="text-capitalize text-danger"><strong>{{__($name->request_name) }}</strong></span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span>{{ ($name->request_id) ? $name->createuser->first_name." ".$name->createuser->last_name: '-' }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span>{{ ($name->process_id) ? $name->processuser->first_name." ".$name->processuser->last_name: '-' }}</span>
                                            </div>

                                            <div class="nk-tb-col">
                                                <span>{{ ($name->created_at) ? $name->created_at->format('Y-m-d'): '-' }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span>{{ ($name->updated_at) ? $name->updated_at->format('Y-m-d') : '-' }}</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                @if($name->status =='accept')
                                                <span class="text-info text-success"><strong>{{ __($name->status) }}</strong></span>
                                                @elseif($name->status =='pending')
                                                <span class="text-info text-warning"><strong>{{ __($name->status) }}</strong></span>
                                                @else
                                                <span class="text-info text-danger"><strong>{{ __($name->status) }}</strong></span>
                                                @endif
                                            </div>

                                            <div class="nk-tb-col nk-tb-col-tools" style="width: 15%">
                                                @if(hasPermission('name_change_accept'))
                                                <ul class="nk-tb-actions gx-1">
                                                    @if($name->status == "pending")
                                                        <input type="hidden" value="{{ route('name.change.authorize', $name->id) }}" id="url_{{ $name->id }}">
                                                        <input type="hidden" value="{{ route('name.change.delete', $name->id) }}" id="delete_url_{{ $name->id }}">
                                                        <li><a href="javascript:void" onclick="nameChangeProcess('{{ $name->id }}')"   class="btn btn-sm btn-primary"><em class="icon ni ni-wallet-out"></em><span class="p-1"> Accept </span></a></li>
                                                        <li><a href="javascript:void" onclick="nameChangeDelete('{{ $name->id }}')"   class="btn btn-sm btn-danger"><em class="icon ni ni-wallet-out"></em><span class="p-1"> Decline </span></a></li>
                                                    @else
                                                        {{-- <li><a href="javascript:void"><span class="p-1"> Complete </span></a></li> --}}
                                                    @endif
                                                </ul>
                                                @else
                                                <ul>
                                                    <li><span class="text-info">No permission</span></li>
                                                </ul>
                                                @endif
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
                                            {!! $changes->appends(Request::except('page'))->links() !!}
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
