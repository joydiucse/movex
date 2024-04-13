@extends('master')

@section('title')
{{__('add')}} {{__('user')}}
@endsection

@section('mainContent')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
            	<div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('add')}} {{__('user')}}</h3>

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
            	<form action="{{ route('user.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
            		@csrf
            		<div class="card">

				<div class="row">
						<div class="col-md-4">
			                <div class="card-inner">
		                        <div class="row g-gs">
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-full-name">{{__('first_name')}} *</label>
		                                    <div class="form-control-wrap">
		                                        <input type="text" class="form-control" id="fv-full-name" name="first_name" required value="{{old('first_name')}}">
		                                    </div>
		                                    @if($errors->has('first_name'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('first_name') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
									<div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-full-name">{{__('last_name')}} *</label>
		                                    <div class="form-control-wrap">
		                                        <input type="text" class="form-control" id="fv-full-name" name="last_name" value="{{old('last_name')}}">
		                                    </div>
		                                    @if($errors->has('last_name'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('last_name') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-email">{{__('email')}} *</label>
		                                    <div class="form-control-wrap">
		                                        <input type="email" class="form-control" id="fv-email" name="email" required value="{{old('email')}}">
		                                    </div>
		                                    @if($errors->has('email'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('email') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-email">{{__('password')}} *</label>
		                                    <div class="form-control-wrap">
		                                        <input type="password" class="form-control" id="fv-email" name="password" required value="{{old('password')}}">
		                                    </div>
		                                    @if($errors->has('password'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('password') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <img src="{{asset('admin/images/default/user.jpg') }}"   id="img_profile" class="img-thumbnail user-profile ">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">{{__('profile_image')}}</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input image_pick" data-image-for="profile" id="customFile" name="image">
                                                    <label class="custom-file-label" for="customFile">{{__('choose_file')}}</label>
                                                </div>
                                            </div>
                                            @if($errors->has('image'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('image') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
		                        </div>
			                </div>
			            </div>
			            <div class="col-md-8">
			            	<div class="card-inner">
								<div class="row g-gs">
		                            <div class="col-md-12">
		                                <div class="form-group pb-3">
											<label class="form-label" for="default-06">{{__('role')}}</label>
											<div class="form-control-wrap ">
												<div class="form-control-select">
													<select class="form-control change-role" id="default-06" name="role">
														<option value="">{{__('select')}} {{__('role')}}</option>
														@foreach($roles as $role)

														<option value="{{$role->id}}">{{$role->name}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
		                            </div>
								</div>
			            		<table class="table table-striped role-create-table" id="permissions-table">
									<thead>
										<tr>
										  <th scope="col">{{__('module')}}/{{__('sub-module')}}</th>
										  <th scope="col">{{__('permissions')}}</th>
										</tr>
									  </thead>
									  <tbody>
										  @foreach($permissions as $permission)
										<tr>
                                            <td><span class="text-capitalize">{{__($permission->attribute}}</span></td>
										  <td>
											@foreach($permission->keywords as $key => $permission)
											  <div class="custom-control custom-checkbox">
												@if($permission != "")
												<input type="checkbox" class="custom-control-input read" id="{{$permission}}" name="permissions[]" value="{{$permission}}">
												<label class="custom-control-label" for="{{$permission}}">{{$key}}</label>
												@endif
											</div>
											@endforeach
										  </td>


										</tr>
										@endforeach

									  </tbody>
								</table>
								<div class="row">
									<div class="col-md-12 text-right mt-4">
			                                <div class="form-group">
			                                    <button type="submit" class="btn btn-lg btn-primary">{{{__('submit')}}}</button>
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
@include('admin.roles.script')
@endsection

