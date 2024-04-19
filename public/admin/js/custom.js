var baseURL = $('meta[name="baseURL"]').attr('content');
$(document).ready(function () {

    $('#switch-mode').click(function (e) {
        e.preventDefault();
        var url = $('#url').val();
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: url + '/' + 'mode-change',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                location.reload();
            },
            error: function (data) {
               // console.log('Error:', data);
            }
        });
    });

});

$(document).ready(function () {

    $('.change-role').on('change', function (e) {
        e.preventDefault();
        var url = $('#url').val();
        var role_id = $(this).val();


        var formData = {
            role_id : role_id
        }
        $.ajax({
            type: "GET",
            dataType: 'html',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url + '/' + 'admin/change-role',
            success: function (data) {
                $('#permissions-table').html(data);
            },
            error: function (data) {
            }
        });
    });

});

function readURL(input, image_for) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_'+ image_for).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".image_pick").change(function () {
    var image_for = $(this).attr('data-image-for');
    readURL(this, image_for);
});



$(document).ready(function () {

    $('.cancel-parcel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#cancel-parcel-id').val(id);
    });

});
$(document).ready(function () {

    $('.delivery-parcel-partially').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#delivery-parcel-partially-id').val(id);

        var formData = {
            id : id
        }
        $.ajax({
            type: "GET",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/' + 'admin/get-current-cod',
            success: function (data) {
                console.log(data);
                $("form#partial-delivery-form .cod").parent('.form-control-wrap').addClass('focused');

                $("form#partial-delivery-form .cod").val(data[1]);
            },
            error: function (data) {
            }
        });
    });

});
$(document).ready(function () {

    $('#customer_phone_number').on('blur', function (e) {
        e.preventDefault();

        var phone_number = $(this).val();

        var formData = {
            phone_number : phone_number
        }
        $.ajax({
            type: "GET",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/' + 'get-customer-info',
            success: function (data) {
                console.log(data);
                if ($("#customer_name").val() == ""){
                    $("#customer_name").val(data['customer_name']);
                }
                if($("#customer_address").val() == ""){
                    $("#customer_address").val(data['customer_address']);
                }

            },
            error: function (data) {
            }
        });
    });

});

$(document).ready(function () {

    $('.parcel-re-request').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#re-request-parcel-id').val(id);
    });

});

$(document).ready(function () {

    $('.delete-parcel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#delete-parcel-id').val(id);
    });

});

$(document).ready(function () {

    $('.transfer-to-hub').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#transfer-to-hub-id').val(id);
    });

});

$(document).ready(function () {

    $('.receive-parcel-pickup').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#receive-parcel-pickup-id').val(id);
    });

});

$(document).ready(function () {

    $('.receive-parcel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#receive-parcel-id').val(id);
    });

});

$(document).ready(function () {

    $('.transfer-receive-to-hub').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#transfer-receive-to-hub-id').val(id);
    });

});

// $(document).ready(function () {

//     $('.delivery-reverse').on('click', function (e) {

//         e.preventDefault();
//         var id = $(this).closest("div.nk-tb-item").find("input").val();
//         $('#delivery-reverse-id').val(id);

//     });

// });

$(document).ready(function () {

    $('.assign-pickup-man').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#pickup-parcel-id').val(id);
    });

});
$(document).ready(function () {

    $('.assign-delivery-man').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#delivery-parcel-id').val(id);
    });

});
$(document).ready(function () {

    $('.return-assign-to-merchant').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#return-merchant-parcel-id').val(id);
    });

});

$(document).ready(function () {

    $('.delivery-return').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#delivery-return-id').val(id);
    });

});
$(document).ready(function () {

    $('.parcel-returned-to-merchant').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#returned-to-merchant-id').val(id);
        $('#returned-to-merchant').modal('show');
        parcelReturnToMerchant(id);
    });

});

$(document).ready(function () {

    $('.reverse-from-cancel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#reverse-from-cancel-id').val(id);
    });

});


$(document).ready(function () {

    $(".reschedule-pickup").on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#re-schedule-pickup-parcel-id').val(id);

        var formData = {
            id : id
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/' + 'admin/re-schedule-pickup',
            success: function (data) {
                console.log(data);
                $("#re-schedule-pickup-assign-man").append(data[1]);
                $("form#re-schedule-pickup-assign-form .date-picker").parent('.form-control-wrap').addClass('focused');

                $("form#re-schedule-pickup-assign-form .date-picker").val(data[2]);

                if(data[4] == 'frozen'){
                    $("form#re-schedule-pickup-assign-form .time").removeClass('d-none');
                    $("form#re-schedule-pickup-assign-form .time-picker").parent('.form-control-wrap').addClass('focused');
                    $("form#re-schedule-pickup-assign-form .time-picker").val(data[3]);
                }else{
                    $("form#re-schedule-pickup-assign-form .time").addClass('d-none');
                }

                $("#re-schedule-pickup-note").val(data[5]);


                console.log(data[4]);
            },
            error: function (data) {
            }
        });
    });

});

$(document).ready(function () {

    $(".reschedule-delivery").on('click', function (e) {
        e.preventDefault();

        var url = $('#url').val();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#re-schedule-delivery-parcel-id').val(id);


        var formData = {
            id : id
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url + '/' + 'admin/re-schedule-delivery',
            success: function (data) {
                $("#re-schedule-delivery-assign-man").append(data[1]);
                $("form#re-schedule-delivery-assign-form .date-picker").parent('.form-control-wrap').addClass('focused');

                $("form#re-schedule-delivery-assign-form .date-picker").val(data[2]);

                if(data[4] == 'frozen'){
                    $("form#re-schedule-delivery-assign-form .time").removeClass('d-none');
                    $("form#re-schedule-delivery-assign-form .time-picker").parent('.form-control-wrap').addClass('focused');
                    $("form#re-schedule-delivery-assign-form .time-picker").val(data[3]);
                }else{
                    $("form#re-schedule-delivery-assign-form .time").addClass('d-none');
                }

                $("#re-schedule-delivery-note").val(data[5]);

                if(data[6] == 'dhaka'){
                    $(".third-party").addClass('d-none');
                }else{
                    $(".third-party").removeClass('d-none');
                    $("#re-schedule-third-party").append(data[7]);
                }
                console.log(data[5]);
            },
            error: function (data) {
            }
        });
    });

});

$(document).ready(function () {

    $('.select-shop').on('change', function (e) {

        var shop_id = $(this).val();
        var url = $(this).attr('data-url');
        var formData = {
            shop_id : shop_id
        }
        $.ajax({
            type: "GET",
            dataType: 'json',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (data) {
                console.log(data);
                $('#shop_phone_number').val(data['shop_phone_number']);
                $('#shop_address').val(data['address']);
            },
            error: function (data) {
            }
        });
    });

});

$(document).ready(function () {

    $('.select-merchant').on('change', function (e) {

        var merchant_id = $(this).val();
        var url = $(this).attr('data-url');
        var formData = {
            merchant_id : merchant_id
        }
        $.ajax({
            type: "GET",
            dataType: 'html',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (data) {
                $('#merchant_select').html(data);
                getDefaultShop(merchant_id);
                getMerchantStaff(merchant_id);
            },
            error: function (data) {
            }
        });
    });

});

function getDefaultShop(merchant_id){
    var url = '/admin/shop/default';
    var formData = {
        merchant_id : merchant_id
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        success: function (data) {
            console.log(data);
            $('#shop_phone_number').val(data['shop_phone_number']);
            $('#shop_address').val(data['address']);
            $('#pickup_hub').empty();
            $('#pickup_hub').append(data['pickup_hub']);
        }
    });
}

function getMerchantStaff(merchant_id){
    var url = '/admin/merchant/staff';
    var formData = {
        merchant_id : merchant_id
    }
    $.ajax({
        type: "GET",
        dataType: 'html',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        success: function (data) {
            $('#created_by').html(data);
        },
        error: function (data) {
        }
    });
}

$(document).ready(function () {

    $('.process-withdraw').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#withdraw-process-id').val(id);
    });

});

$(document).ready(function () {

    $('.approve-withdraw').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#withdraw-approve-id').val(id);
        var url = $(this).attr('data-url');
        getBatches(id, url);
    });

});

function getBatches(id, url){
    var formData = {
        withdraw_id : id
    }

    $.ajax({
        type: "GET",
        dataType: 'html',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        success: function (data) {
            $('.withdraw_batches').html(data);
        },
        error: function (data) {
        }
    });
}

$(document).ready(function () {

    $('.reject-payment').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#reject-payment-id').val(id);
    });

});
$(document).ready(function () {

    $('.change-batch').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#change-batch-id').val(id);
        var url = $(this).attr('data-url');
        getBatches(id, url);
    });

});
$(document).ready(function () {

    $('.delivery-parcel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#delivery-parcel-id').val(id);
        parcelDelivery(id);
        $('#parcel-delivered').modal('show');
    });

});
$(document).ready(function () {

    $('.assign-delivery-man').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#assign-delivery-parcel-id').val(id);

        getLocation(id);
    });

});


$(document).ready(function () {

    $('.select-merchant-for-credit').on('change', function (e) {

        var merchant_id = $(this).val();
        var url = $(this).attr('data-url');
        var formData = {
            merchant_id : merchant_id
        }
        $.ajax({
            type: "GET",
            dataType: 'html',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (data) {
                $('#parcel_select').html(data);
            },
            error: function (data) {
            }
        });
    });

});

function getLocation(id){
    var url = $('#url').val();
    var formData = {
        id : id
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        data: formData,
        url: url + '/' + 'admin/get-parcel-location',
        success: function (data) {

            if(data.location == 'dhaka'){
                $(".third-party").addClass('d-none');
            }else{
                $(".third-party").removeClass('d-none');
            }
        },
        error: function (data) {
        }
    });
}

$(function() {
    $('.copy-to-clipboard input').click(function() {
        var text = $(this).attr('data-text');
        $(this).focus();
        $(this).select();
        document.execCommand('copy');

        toastr.clear();
        NioApp.Toast(text+': '+$(this).val(), 'success',{
            position: 'top-right'
        });
    });
});
function copyInput(element) {
    var text = $('#'+element).attr('data-text');
    $('#'+element).focus();
    $('#'+element).select();
    document.execCommand('copy');

    toastr.clear();
    NioApp.Toast(text+': '+$('#'+element).val(), 'success',{
        position: 'top-right'
    });
}

$('.sms-provider').on('change', function () {
    if ($(this).val() === "onnorokom") {
        $(".onnorokom").removeClass('d-none');
        $(".reve").addClass('d-none');

        $("#onnorokom_url").attr("required", true);
        $("#onnorokom_username").attr("required", true);
        $("#onnorokom_password").attr("required", true);

        $("#reve_url").attr("required", false);
        $("#reve_api_key").attr("required", false);
        $("#reve_secret").attr("required", false);
    }
    else if ($(this).val() === "reve") {
        $(".reve").removeClass('d-none');
        $(".onnorokom").addClass('d-none');

        $("#onnorokom_url").attr("required", false);
        $("#onnorokom_username").attr("required", false);
        $("#onnorokom_password").attr("required", false);

        $("#reve_url").attr("required", true);
        $("#reve_api_key").attr("required", true);
        $("#reve_secret").attr("required", true);

    } else {
        $(".onnorokom").addClass('d-none');
        $(".reve").addClass('d-none');

        $("#onnorokom_url").attr("required", false);
        $("#onnorokom_username").attr("required", false);
        $("#onnorokom_password").attr("required", false);

        $("#reve_url").attr("required", false);
        $("#reve_api_key").attr("required", false);
        $("#reve_secret").attr("required", false);
    }
});

$('.database-storage').on('change', function () {
    if ($(this).val() === "local") {
        $(".google-drive").addClass('d-none');

        $("#google_drive_client_id").attr("required", false);
        $("#google_drive_client_secret").attr("required", false);
        $("#google_drive_refresh_token").attr("required", false);
        $("#google_drive_folder_id").attr("required", false);
    }
    else if ($(this).val() === "both" ||  $(this).val() === "google-drive") {
        $(".google-drive").removeClass('d-none');

        $("#google_drive_client_id").attr("required", true);
        $("#google_drive_client_secret").attr("required", true);
        $("#google_drive_refresh_token").attr("required", true);
        $("#google_drive_folder_id").attr("required", true);

    } else {
        $(".google-drive").addClass('d-none');

        $("#google_drive_client_id").attr("required", false);
        $("#google_drive_client_secret").attr("required", false);
        $("#google_drive_refresh_token").attr("required", false);
        $("#google_drive_folder_id").attr("required", false);
    }
});

$('.get-delivery-man-balance').on('change', function () {
    var url = $('#url').val();
    var id  = $(this).val();
    var data_for = $(this).attr('data-for');
    var company_account_id = $(this).attr('data-id');

    var formData = {
        id: id,
        data_for: data_for,
        company_account_id: company_account_id
    }

    $.ajax({
        type: "GET",
        dataType: 'json',
        data: formData,
        url: url + '/' + 'admin/get-delivery-man-balance',
        success: function (data) {
            $('.current-balance').text(data.balance);
        },
        error: function (data) {
        }
    });
});

function getKey(length = 16, id) {
    var api_key = "";
    var string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@$%^&*()";

    for (var i = 0; i < length; i++)
        api_key += string.charAt(Math.floor(Math.random() * string.length));

    $("#"+id).val(api_key);
}

$(document).ready(function () {
    $('.resubmit').on('click', function (e) {
        var loading = 2;
        $('.resubmit').css("pointer-events", "none");
        $('.resubmit').addClass('disabled')
        startTimer(loading);
    });
});
function startTimer(duration) {
    var timer = duration, minutes, seconds;
    var trigger =  setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if (timer >= 0) {
            --timer;
        }
        else if(timer < 0){
            $('.resubmit').css("pointer-events", "auto");
            $('.resubmit').removeClass('disabled');
        }
    }, 2000);
}

$('.return-charge-type').on('change', function () {
    var value = $(this).val();

    if (value == 'on_demand'){
        $('.charges').removeClass('d-none');
    } else{
        $('.charges').addClass('d-none');
    }
});


$(document).ready(function () {

    $('.create-paperfly-parcel').on('click', function (e) {
        e.preventDefault();
        var id = $(this).closest("div.nk-tb-item").find("input").val();
        $('#create-paperfly-parcel-id').val(id);
        $('.thana-union').html('');
        $('.get-thana-union').html('');
        var url = $(this).attr('data-url');

        $.ajax({
            type: "GET",
            dataType: 'html',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (data) {
                $('.get-thana-union').html(data);
                $('.get-thana-union').select2();
                $('.thana-union').select2();
            },
            error: function (data) {
            }
        });

    });

});


$(document).ready(function () {

    $('.get-thana-union').on('change', function (e) {

        var district = $(this).val();
        $('.thana-union').html('');
        var url = $(this).attr('data-url');
        var formData = {
            district : district
        }
        $.ajax({
            type: "GET",
            dataType: 'html',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (data) {
                $('.thana-union').html(data);
            },
            error: function (data) {
            }
        });
    });

});

$(document).ready(function () {
    $('.modal#create-paperflyparcel').on('hidden.bs.modal', function () {
        $('.thana-union').select2("destroy")
        $('.get-thana-union').select2("destroy")
    })
});

function tripleBase64Encode(data) {
    return btoa(btoa(btoa(data)));
}

$(function (){
    var baseUrl=$('#url').val();
    $('input[name="parcel_id[]"], #all').change(function() {

        if ($('input[name="parcel_id[]"]:checked').length > 0) {
            $('#addToPathaoButton').prop('disabled', false);
        } else {
            $('#addToPathaoButton').prop('disabled', true);
        }
    });

    $('#addToPathaoButton').click(function (){
        $('#smallModalTitle').html('Add parcels to [Pathao]');
        let html=`
            <div class="d-flex align-items-center justify-content-center" role="status">
                <div class="spinner-border" role="status"></div>
            </div>
        `;
        $('#smallModalBody').html(html);
        $('#smallModalFooter').html('<button type="button" onclick="addToPathao()" id="addToPathaoSubmitButton" class="btn btn-primary" disabled>Add to Pathao</button>');
        $('#smallModal').modal('show');
        if ($('input[name="parcel_id[]"]:checked').length > 0) {
            var checkedValues = [];
            $('input[name="parcel_id[]"]:checked').each(function () {
                checkedValues.push($(this).val());
            });
            console.log(checkedValues);
            $.ajax(`${baseUrl}/admin/pathao/parcel-short-details?id=${checkedValues}`).then(function (res) {
                if(res.status===1){
                    $('#smallModalBody').html(res.html);
                    $('#addToPathaoSubmitButton').prop('disabled', false);
                }else{

                }
                console.log(res)
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", errorThrown);
            });
        }else{
            console.log('select ')
        }
    })



})

function addToPathao() {
    let baseUrl=$('#url').val();
    $('#parcelDetails').css('opacity', '0.2');
    //$('#addToPathaoSubmitButton').prop('disabled', true);
    let html=`
            <div class="w-100 h-100 position-absolute absolute-top-left d-flex align-items-center justify-content-center" role="status">
                <div class="spinner-border" role="status"></div>
            </div>
        `;
    $('#smallModalBody').append(html);
    let selectedValues = [];
    $('input[name="selected_parcel_id[]"]').each(function () {
        selectedValues.push($(this).val());
    });
    if(selectedValues.length>0){
        console.log('send')
        $.ajax(`${baseUrl}/admin/pathao/bulk-order?id=${selectedValues}`).then(function (res) {
            if(res.status===1){
                console.log()
            }else{

            }
            console.log(res)
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error:", errorThrown);
        });
    }

}


function validateGlobalSearchForm(form) {
    if (form.key.value === '') {
        // Key is empty, prevent form submission
        return false;
    }
    // Key has value, allow form submission
    return true;
}
