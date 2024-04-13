@extends('master')

@section('title')
    {{__('import')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('import')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ (Sentinel::getUser()->user_type =='merchant') ? route('merchant.import') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.import') : route('import'))}}" class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="barcode">{{__('choose_file')}} </label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input image_pick" data-image-for="profile" id="customFile" name="file">
                                                            <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                                        </div>
                                                    </div>
                                                    @if($errors && $errors->any())
                                                        @foreach($errors->all() as $error)
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $error }}</p>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="col-md-12 text-right mt-4">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>{{ __('n_b') }}</h5>
                                                <p>{{ __('please_check_this_before_importing_your_file') }}</p>
                                                <ul class="list list-sm list-success">
                                                    <li>{{ __('uploaded_file_must_be_xlsx_or_csv') }}</li>
                                                    <li>{{ __('the_file_must_contain_price_selling_price_customer_name_customer_invoice_no_customer_phone_number_customer_address') }}</li>
                                                    <li>{{ __('price_and_selling_price_must_be_numeric_example') }}</li>
                                                    <li>{{ __('fragile_parcel_type_note_weight_pickup_shop_phone_number_pickup_address_pickup_hub') }}</li>
                                                    <li>{{ __('if_parcel_type_not_provided_by_default_it_will_be_set_for_next_day') }}</li>
                                                    <li>{{ __('if_weight_not_provided_by_default_weight_will_be_1') }}</li>
                                                    <li>{{ __('if_parcel_types_provided_and_not_available_this_row_will_be_auto_ignored') }}</li>
                                                    @if(hasPermission('parcel_create') || hasPermission('manage_parcel') || Sentinel::getUser()->user_type == 'merchant')
                                                        <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.export') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.export') : route('export')) }}">
                                                            <span class="nk-menu-icon"><em class="icon ni ni-download"></em></span>
                                                            <span class="nk-menu-text">{{__('parcel_import_sample').' '.__('download')}}</span>
                                                        </a>
                                                    @endif
                                                </ul>
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
