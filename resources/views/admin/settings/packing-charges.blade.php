@extends('master')

@section('title')
    {{__('packaging_type_and_charges')}}
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
                                                <h4 class="nk-block-title">{{__('packaging_type_and_charges')}}</h4>
                                            </div>
                                            <div class="d-flex">
                                                @if(hasPermission('charge_setting_update'))
                                                    <button  class="btn btn-primary d-md-inline-flex" id="add-row" data-url="admin/add-charge-packaging-row/"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></button>
                                                @endif
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('charge_setting_update'))
                                        <form action="{{ route('packaging.and.charge.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @endif
                                            <div class="card shadow-none">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card-inner">

                                                            <div class="row g-gs">
                                                                <table class="table table-borderless">
                                                                    <thead class="mb-3">
                                                                    <tr>
                                                                        <th scope="col">{{__('type')}}</th>
                                                                        <th scope="col">{{__('charge')}}</th>
                                                                        @if(hasPermission('charge_setting_update'))
                                                                        <th scope="col">{{__('action')}}</th>
                                                                        @endif
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="package-charge">
                                                                        @foreach($packaging_and_charges as $packaging_and_charge)
                                                                            <tr id="row_{{$packaging_and_charge->id}}">
                                                                                <td>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="number" class="form-control" id="{{'ids_'.$packaging_and_charge->id}}" value="{{ $packaging_and_charge->id }}" name="ids[]" hidden>
                                                                                        <input type="text" class="form-control" id="{{'type_'.$packaging_and_charge->id}}" value="{{ $packaging_and_charge->package_type }}" name="packaging_types[]" required>
                                                                                        @if($errors->has('packaging_types'))
                                                                                            <div class="nk-block-des text-danger">
                                                                                                <p>{{ $errors->first('packaging_types') }}</p>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="number" class="form-control" id="{{'charge_'.$packaging_and_charge->id}}" value="{{ $packaging_and_charge->charge }}"  name="charges[]" min="0" required>
                                                                                        @if($errors->has('charges'))
                                                                                            <div class="nk-block-des text-danger">
                                                                                                <p>{{ $errors->first('charges') }}</p>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                                @if(hasPermission('charge_setting_update'))
                                                                                <td>
                                                                                    <div class="form-control-wrap">
                                                                                        <ul class="nk-tb-actions mt-1">
                                                                                            <li><a href="javascript:void(0)"  data-row="row_{{$packaging_and_charge->id}}" data-id="{{$packaging_and_charge->id}}" class="btn btn-sm btn-danger delete-btn-remove" id="delete-btn-remove"><em class="icon ni ni-trash"></em></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            @if(hasPermission('charge_setting_update'))
                                                                <div class="row">
                                                                    <div class="col-md-12 text-right mt-4">
                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @if(hasPermission('charge_setting_update'))
                                        </form>
                                    @endif
                                </div>

                                @include('admin.settings.sidebar')
                                @include('admin.settings.script')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
