<option value="">{{ __('select_hub') }}</option>
@foreach($hubs as $hub)
    <option value="{{ $hub->id }}" {{ $hub->id == $pickup_hub ? 'selected':'' }}>{{ __($hub->name).' ('.$hub->address.')' }}</option>
@endforeach
