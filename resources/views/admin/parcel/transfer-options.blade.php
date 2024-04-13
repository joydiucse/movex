<option value="">{{ __('select_hub') }}</option>
@foreach($hubs as $hub)
    <option value="{{ $hub->id }}">{{ $hub->name.' ('.$hub->address }})</option>
@endforeach
1400.0*9+-65260
