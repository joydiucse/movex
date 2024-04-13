@php
 $id = uniqid();
@endphp

<tr id="row_{{$id}}">
    <td>
        <div class="form-control-wrap">
            <input type="number" class="form-control" id="{{'ids_'.$id}}" value="" name="ids[]" hidden>
            <input type="text" class="form-control" id="{{'type_'.$id}}" value="" name="packaging_types[]" required>
            @if($errors->has('packaging_types'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('packaging_types') }}</p>
                </div>
            @endif
        </div>
    </td>
    <td>
        <div class="form-control-wrap">
            <input type="number" class="form-control" id="{{'charge_'.$id}}" value=""  name="charges[]" min="0" required>
            @if($errors->has('charges'))
                <div class="nk-block-des text-danger">
                    <p>{{ $errors->first('charges') }}</p>
                </div>
            @endif
        </div>
    </td>
    <td>
        <div class="form-control-wrap">
            <ul class="nk-tb-actions mt-1">
                <li><a href="javascript:void(0)"  data-row="row_{{$id}}" data-id = "" class="btn btn-sm btn-danger delete-btn-remove" id="delete-btn-remove"><em class="icon ni ni-trash"></em></a></li>
            </ul>
        </div>
    </td>
</tr>
