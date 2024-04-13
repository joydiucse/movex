<script>



    //sticker print
    function submitbulkPrint(action_form)
    {
        if(action_form =='sticker'){
            document.getElementById("parcel-form").action = "{{ route('admin.bulk.sticker-print') }}";
            $('#parcel-form').submit();
        }else if(action_form == 'details_print'){
            document.getElementById("parcel-form").action = "{{ route('admin.bulk.parcel-print') }}";
            $('#parcel-form').submit();
        }else if(action_form == 'return_sticker'){
            document.getElementById("parcel-form").action = "{{ route('admin.bulk.return-sticker-print') }}";
            $('#parcel-form').submit();
        }else if(action_form == 'return_sheet_print'){
            document.getElementById("parcel-form").action = "{{ route('admin.bulk.parcel-return-print') }}";
            $('#parcel-form').submit();
        }else if(action_form == 'export_parcel'){
            document.getElementById("parcel-form").action = "{{ route('admin.parcel-export') }}";
            $('#parcel-form').submit();
        }

    }


    $("#all").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });

</script>
