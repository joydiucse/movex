@extends('master')

@section('title')
    {{__('personal_information')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">

                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('payment_logs')}}</h4>
                                                <div class="nk-block-des">
                                                    <p>{{__('cash_received_by_this_user')}}</p>
                                                </div>
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
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
                                                                    <span>{{ number_format($statement->amount,2) }}</span>
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

                                @include('common.profile.staff.profile-sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
