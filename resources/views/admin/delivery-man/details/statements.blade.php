@extends('master')

@section('title')
    {{__('payment_logs')}}
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
                                                </div>
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block card">
                                        <table class="table table-ulogs">
                                            <thead class="thead-light">
                                            <tr class="statement">
                                                <th class="tb-col-os"><span class="overline-title">{{__('details')}}</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('source')}}</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('completed_at')}}</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('amount')}} ({{ __('tk') }})</span></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($statements as $statement)
                                                <tr class="statement">
                                                    <td class="tb-col-os">
                                                        <span>{{ __($statement->details) }}</span><br>
                                                        @if($statement->parcel != "")
                                                            {{__('id')}}:<span>#{{ __(@$statement->parcel->parcel_no) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="tb-col-os"><span>{{ __($statement->source) }} </span></td>
                                                    <td class="tb-col-os"><span>{{$statement->created_at != ""? date('M d, Y h:i a', strtotime($statement->created_at)):''}} </span></td>
                                                    @if($statement->type == 'income')
                                                    <td class="tb-col-os"><span>{{ number_format($statement->amount,2) }} </span></td>
                                                    @else
                                                    <td class="tb-col-os text-danger"><span>{{ number_format($statement->amount,2) }} </span></td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-inner p-2">
                                        <div class="nk-block-between-md g-3">
                                            <div class="g">
                                                {!! $statements->appends(Request::except('page'))->links() !!}
                                            </div>
                                        </div><!-- .nk-block-between -->
                                    </div>
                                </div>

                                @include('admin.delivery-man.details.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
