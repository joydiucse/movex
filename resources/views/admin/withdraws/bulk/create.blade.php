@extends('master')

@section('title')
    {{__('add_new_batch')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('add_new_batch')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <div class="drodown">
                                                    <a href="{{url()->previous()}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.withdraws.bulk.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">{{__('Title') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" placeholder="{{ __('title') }}" required>
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
                                                    <label class="form-label">{{ __('batch_type') }}</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg withdraw_batches" name="batch_type" required>
                                                            <option value="bank" {{ old('batch_type') ? (old('batch_type') == 'bank' ? 'selected' : '') : '' }}>{{ __('bank') }}</option>
                                                            <option value="bkash" {{ old('batch_type') ? (old('batch_type') == 'bank' ? 'selected' : '') : '' }}>{{ __('bkash') }}</option>
                                                            <option value="nogod" {{ old('batch_type') ? (old('batch_type') == 'bank' ? 'selected' : '') : '' }}>{{ __('nogod') }}</option>
                                                            <option value="rocket" {{ old('batch_type') ? (old('batch_type') == 'bank' ? 'selected' : '') : '' }}>{{ __('rocket') }}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('batch_type'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('batch_type') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">{{__('Note')}} </label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="note" placeholder="{{__('note').' ('.__('optional').')'}}" name="note">{{ old('note') }}</textarea>
                                                    </div>
                                                    @if($errors->has('note'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('note') }}</p>
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
