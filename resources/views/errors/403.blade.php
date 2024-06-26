@extends('errors.master')

@section('title')
{{__('error')}} {{__('403')}}
@endsection
@section('mainContent')
<div class="nk-content ">
    <div class="nk-block nk-block-middle wide-xs mx-auto">
        <div class="nk-block-content nk-error-ld text-center">
            <h1 class="nk-error-head">{{__('403')}}</h1>
            <h3 class="nk-error-title">{{__('opps_why_are_you_here')}}</h3>
            <p class="nk-error-text">{{__('403_message')}}</p>
            <a href="{{route('dashboard')}}" class="btn btn-lg btn-primary mt-2">{{__('back_to_home')}}</a>
        </div>
    </div><!-- .nk-block -->
</div>
@endsection