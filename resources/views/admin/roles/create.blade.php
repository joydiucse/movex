@extends('master')

@section('title')
{{__('add')}} {{__('role')}}
@endsection

@section('mainContent')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
            	<div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('add')}} {{__('role')}}</h3>

                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
            	<form action="{{ route('roles.store')}}" class="form-validate" method="POST">
            		@csrf
            		<div class="card">

				<div class="row">
						<div class="col-md-4">
			                <div class="card-inner">
		                        <div class="row g-gs">
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-full-name">{{__('name')}} *</label>
		                                    <div class="form-control-wrap">
		                                        <input type="text" class="form-control" id="fv-full-name" name="name" required>
		                                    </div>
		                                    @if($errors->has('name'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('name') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <label class="form-label" for="fv-email">{{__('slug')}}</label>
		                                    <div class="form-control-wrap">
		                                        <input type="text" class="form-control" id="fv-email" name="slug">
		                                    </div>
		                                    @if($errors->has('slug'))
	                                            <div class="nk-block-des text-danger">
	                                                <p>{{ $errors->first('slug') }}</p>
	                                            </div>
	                                        @endif
		                                </div>
		                            </div>
		                        </div>
			                </div>
			            </div>
			            <div class="col-md-8">
			            	<div class="card-inner">
			            		<table class="table table-striped role-create-table role-permission">
								  <thead>
								    <tr>
								      <th scope="col">{{__('module')}}/{{__('sub-module')}}</th>
								      <th scope="col">{{__('permissions')}}</th>
								    </tr>
								  </thead>
								  <tbody>
								  	@foreach($permissions as $permission)
								    <tr>
                                        <td><span class="text-capitalize">{{__($permission->attribute)}}</span></td>

								      <td>
										@foreach($permission->keywords as $key=>$keyword)
								      	<div class="custom-control custom-checkbox">
								      		@if($keyword != "")
	                                        <input type="checkbox" class="custom-control-input read common-key" name="permissions[]" value="{{$keyword}}" id="{{$keyword}}">
	                                        <label class="custom-control-label" for="{{$keyword}}">{{__($key)}}</label>
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
@include('admin.roles.script')
@endsection

