@push('script')
    <script type="text/javascript">
        $('.shuttle-man-live-search').select2({
            placeholder: "{{ __('select_shuttle_man') }}",
            minimumInputLength: 2,
            ajax: {
                type: "GET",
                dataType: 'json',
                url: '{{ route('get-shuttle-man-live') }}',
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                delay: 250,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    </script>
@endpush
