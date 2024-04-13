@extends('master')

@section('title')
    {{__('add').' '.__('notice')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('add')}} {{__('notice')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('notice.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">{{__('title')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="title" value="{{ old('title') }}" name="title" required>
                                                    </div>
                                                    @if($errors->has('title'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('title') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="alert_class">{{__('alert_class')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="alert_class">
                                                            <option>{{ __('select_class') }}</option>
                                                            <option value="alert-danger">{{ __('alert-danger') }}</option>
                                                            <option value="alert-info">{{ __('alert-info') }}</option>
                                                            <option value="alert-primary">{{ __('alert-primary') }}</option>
                                                            <option value="alert-secondary">{{ __('alert-secondary') }}</option>
                                                            <option value="alert-success">{{ __('alert-success') }}</option>
                                                            <option value="alert-warning">{{ __('alert-warning') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('start_date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="start_date" required autocomplete="off" value="{{date('m/d/Y')}}">
                                                    </div>
                                                    @if($errors->has('start_date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('start_date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('start_time')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-clock"></em>
                                                        </div>
                                                        <input type="text" class="form-control time-picker" id="outlined-time-picker" name="start_time">
                                                    </div>
                                                    @if($errors->has('start_time'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('start_time') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('end_date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="end_date" required autocomplete="off" value="{{date('m/d/Y', strtotime('+1 day'))}}">
                                                    </div>
                                                    @if($errors->has('end_date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('end_date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('end_time')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-clock"></em>
                                                        </div>
                                                        <input type="text" class="form-control time-picker" id="outlined-time-picker" name="end_time">
                                                    </div>
                                                    @if($errors->has('end_time'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('end_time') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">{{__('details')}} </label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="details" placeholder="{{__('details').' ('.__('optional').')'}}" name="details" required>{{ old('details')}}</textarea>
                                                    </div>
                                                    @if($errors->has('details'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('details') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
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
