@extends('master')

@section('title')
    {{__('pickup_time_delivery_days')}}
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
                                    <div class="nk-block-head nk-block-head-lg pb-2">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('delivery_man_sip_domain')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('pickup_and_delivery_time_setting_update'))
                                    <form action="{{ route('setting.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                        @csrf
                                    @endif
                                        <div class="card shadow-none">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-inner">

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="sip_domain">{{ __('sip_domain') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="sip_domain" value="{{ settingHelper('sip_domain') }}" name="sip_domain"  required>
                                                                    </div>
                                                                    @if($errors->has('sip_domain'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('sip_domain') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 text-right mt-4">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @if(hasPermission('pickup_and_delivery_time_setting_update'))
                                    </form>
                                    @endif
                                </div>

                                @include('admin.settings.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script>
    $('select').select2();
</script>
@endpush
