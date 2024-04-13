<div class="nk-tb-list nk-tb-ulist">
    <div class="nk-tb-item nk-tb-head">
        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('merchant') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('request_for') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('previous_name') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('request_name') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('request_by') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('process_by') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('request_date') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('process_date') }}</strong></span></div>
        <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('status') }}</strong></span></div>


    </div><!-- .nk-tb-item -->

    @foreach ($shop->shopnamechanges as $key => $name)
        <div class="nk-tb-item">
            <div class="nk-tb-col">
                <span>{{ ++$key }}</span>
            </div>
            <div class="nk-tb-col">
                <span>{{ $name->merchant->company }}</span>
            </div>

            <div class="nk-tb-col">
                <span class="text-primary">{{ __($name->type) }}</span>
            </div>
            <div class="nk-tb-col">
                <span class="text-capitalize text-info"><strong>{{ __($name->old_name) }}</strong></span>
            </div>

            <div class="nk-tb-col">
                <span class="text-capitalize text-danger"><strong>{{ __($name->request_name) }}</strong></span>
            </div>

            <div class="nk-tb-col">
                <span>{{ $name->request_id ? $name->createuser->first_name . ' ' . $name->createuser->last_name : '-' }}</span>
            </div>

            <div class="nk-tb-col">
                <span>{{ $name->process_id ? $name->processuser->first_name . ' ' . $name->processuser->last_name : '-' }}</span>
            </div>

            <div class="nk-tb-col">
                <span>{{ $name->created_at ? $name->created_at->format('Y-m-d') : '-' }}</span>
            </div>
            <div class="nk-tb-col">
                <span>{{ $name->updated_at ? $name->updated_at->format('Y-m-d') : '-' }}</span>
            </div>
            <div class="nk-tb-col">
                @if($name->status =='accept')
                <span class="text-info text-success"><strong>{{ __($name->status) }}</strong></span>
                @elseif($name->status =='pending')
                    <span class="text-info text-warning"><strong>{{ __($name->status) }}</strong></span>
                @else
                    <span class="text-info text-danger"><strong>{{ __($name->status) }}</strong></span>
                @endif
            </div>
        </div><!-- .nk-tb-item -->
    @endforeach
