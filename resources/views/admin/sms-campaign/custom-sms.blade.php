@extends('master')

@section('title')
    {{__('send_sms')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('send_sms')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('sms.custom.post') }}" class="form-validate" method="POST">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div id="custom-section">
                                            <div class="row g-gs">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('phone_numbers') }} *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" name="phone_numbers" class="form-control" placeholder="{{ __('write_and_hit_enter') }}" value="" data-role="tagsinput" required>
                                                            <input type="text" hidden name="sms_to" class="form-control" value="custom" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-gs">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('sms_body') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" name="sms_body" rows="7"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-6">
                                            <div class="col-sm-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn d-md-inline-flex btn-primary resubmit"><em class="icon ni ni-send"></em></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="{{ asset('admin/')}}/js/tagsinput.js"></script>
@endpush
