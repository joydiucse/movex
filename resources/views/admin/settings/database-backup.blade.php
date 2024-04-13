@extends('master')

@section('title')
    {{__('database_backup_storage')}}
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
                                                <h4 class="nk-block-title">{{__('database_backup_storage')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('database_backup_storage_setting_update'))
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
                                                                        <label class="form-label">{{ __('select').' '.__('database_backup_storage') }} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select form-control form-control-lg database-storage" name="database_backup_storage" required>
                                                                                <option value="">{{ __('select_provider') }}</option>
                                                                                <option value="both" {{ settingHelper('database_backup_storage') == 'both' ? 'selected' : ''}}>{{ __('both') }}</option>
                                                                                <option value="local" {{ settingHelper('database_backup_storage') == 'local' ? 'selected' : ''}}>{{ __('local') }}</option>
                                                                                <option value="google-drive" {{ settingHelper('database_backup_storage') == 'google-drive' ? 'selected' : ''}}>{{ __('google-drive') }}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="google-drive {{ settingHelper('database_backup_storage') == 'both' ||  settingHelper('database_backup_storage') == 'google-drive'? '' : 'd-none'}}">
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="google_drive_client_id">{{__('google_drive_client_id')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="google_drive_client_id" value="{{ settingHelper('google_drive_client_id') }}" name="google_drive_client_id">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="google_drive_client_secret">{{__('google_drive_client_secret')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="password" class="form-control" id="google_drive_client_secret" value="{{ settingHelper('google_drive_client_secret') }}" name="google_drive_client_secret">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="google_drive_refresh_token">{{__('google_drive_refresh_token')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="google_drive_refresh_token" value="{{ settingHelper('google_drive_refresh_token') }}" name="google_drive_refresh_token">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="google_drive_folder_id">{{__('google_drive_folder_id')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="google_drive_folder_id" value="{{ settingHelper('google_drive_folder_id') }}" name="google_drive_folder_id">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(hasPermission('database_backup_storage_setting_update'))
                                                                <div class="row">
                                                                    <div class="col-md-6 text-right mt-4">
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
                                            @if(hasPermission('database_backup_storage_setting_update'))
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
