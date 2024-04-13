<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V10\MerchantAuthController;
use App\Http\Controllers\Api\V10\ParcelController as V10ApiParcelController;
use App\Http\Controllers\Api\V10\PaymentController as V10ApiPaymentController;
use App\Http\Controllers\Api\DeliveryMan\Beta\AuthController;
use App\Http\Controllers\Api\DeliveryMan\V13\ConfigController;
use App\Http\Controllers\Api\DeliveryMan\Beta\ParcelController;
use App\Http\Controllers\Api\DeliveryMan\V10\AuthController as V10AuthController;
use App\Http\Controllers\Api\DeliveryMan\V11\AuthController as V11AuthController;
use App\Http\Controllers\Api\DeliveryMan\V12\AuthController as V12AuthController;
use App\Http\Controllers\Api\DeliveryMan\V13\AuthController as V13AuthController;
use App\Http\Controllers\Api\DeliveryMan\V10\ParcelController as V10ParcelController;
use App\Http\Controllers\Api\DeliveryMan\V11\ParcelController as V11ParcelController;
use App\Http\Controllers\Api\DeliveryMan\V12\ParcelController as V12ParcelController;
use App\Http\Controllers\Api\DeliveryMan\V13\ParcelController as V13ParcelController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});




Route::prefix('beta')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [AuthController::class, 'loginOtp']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password-otp', [AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [AuthController::class, 'forgotPasswordPost']);
    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[AuthController::class,'profile']);
        Route::get('logout',[AuthController::class, 'logout']);
        Route::post('update-profile',[AuthController::class, 'updateProfile']);
        Route::get('payment-logs',[AuthController::class, 'paymentLogs']);
        Route::get('my-pickup',[ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[ParcelController::class, 'pickupCompleted']);
        Route::get('my-delivery',[ParcelController::class, 'myDelivery']);
        Route::get('pending-delivery',[ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[ParcelController::class, 'deliveryCompleted']);
        Route::post('otp-verify',[ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[ParcelController::class, 'cancel']);
        Route::post('delivered',[ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[ParcelController::class, 'parcelDetails']);
    });
});

Route::prefix('v10')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [V10AuthController::class, 'loginOtp']);
        Route::post('login', [V10AuthController::class, 'login']);
        Route::post('forgot-password-otp', [V10AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [V10AuthController::class, 'forgotPasswordPost']);

    });

    Route::middleware(['merchantApiAuth'])->group(function () {
        //Merchant
        Route::get('merchant/get-profile',[MerchantAuthController::class,'profile']);
        Route::get('merchant/get-balance',[MerchantAuthController::class,'balance']);
        Route::get('merchant/parcel-status-track/{parcel_no}',[V10ApiParcelController::class,'getTrack']);
        Route::post('merchant/create-parcel',[V10ApiParcelController::class,'create']);
        Route::get('merchant/parcel-list',[V10ApiParcelController::class,'parcelList']);
        Route::post('merchant/create-payment',[V10ApiPaymentController::class,'create']);
        Route::get('merchant/payment-logs',[V10ApiPaymentController::class,'paymentLogs']);
        Route::get('merchant/payment-lists',[V10ApiPaymentController::class,'paymentList']);
    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[V10AuthController::class,'profile']);
        Route::get('logout',[V10AuthController::class, 'logout']);
        Route::post('update-profile',[V10AuthController::class, 'updateProfile']);
        Route::get('payment-logs',[V10AuthController::class, 'paymentLogs']);
        Route::get('my-pickup',[V10ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[V10ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[V10ParcelController::class, 'pickupCompleted']);
        Route::get('my-delivery',[V10ParcelController::class, 'myDelivery']);
        Route::get('pending-delivery',[V10ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[V10ParcelController::class, 'deliveryCompleted']);
        Route::post('otp-verify',[V10ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[V10ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[V10ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[V10ParcelController::class, 'cancel']);
        Route::post('delivered',[V10ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[V10ParcelController::class, 'parcelDetails']);
    });
});

Route::prefix('v11')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [V11AuthController::class, 'loginOtp']);
        Route::post('login', [V11AuthController::class, 'login']);
        Route::post('forgot-password-otp', [V11AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [V11AuthController::class, 'forgotPasswordPost']);
    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[V11AuthController::class,'profile']);
        Route::get('logout',[V11AuthController::class, 'logout']);
        Route::post('update-profile',[V11AuthController::class, 'updateProfile']);
        Route::get('payment-logs',[V11AuthController::class, 'paymentLogs']);
        Route::get('my-pickup',[V11ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[V11ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[V11ParcelController::class, 'pickupCompleted']);
        Route::get('my-delivery',[V11ParcelController::class, 'myDelivery']);
        Route::get('pending-delivery',[V11ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[V11ParcelController::class, 'deliveryCompleted']);
        Route::post('otp-verify',[V11ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[V11ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[V11ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[V11ParcelController::class, 'cancel']);
        Route::post('delivered',[V11ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[V11ParcelController::class, 'parcelDetails']);
        Route::get('my-pickup-merchants',[V11ParcelController::class, 'myPickupMerchants']);
        Route::post('pickup-received',[V11ParcelController::class, 'pickupReceived']);
    });
});

Route::prefix('v12')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [V12AuthController::class, 'loginOtp']);
        Route::post('login', [V12AuthController::class, 'login']);
        Route::post('forgot-password-otp', [V12AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [V12AuthController::class, 'forgotPasswordPost']);
    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[V12AuthController::class,'profile']);
        Route::get('logout',[V12AuthController::class, 'logout']);
        Route::post('update-profile',[V12AuthController::class, 'updateProfile']);
        Route::post('update-profile-image',[V12AuthController::class, 'updateProfileImage']);
        Route::get('payment-logs',[V12AuthController::class, 'paymentLogs']);
        Route::get('cash-deposits',[V12AuthController::class, 'cashDeposits']);
        Route::get('my-pickup',[V12ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[V12ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[V12ParcelController::class, 'pickupCompleted']);
        Route::get('my-delivery',[V12ParcelController::class, 'myDelivery']);
        Route::get('my-re-scheduled-delivery',[V12ParcelController::class, 'myReScheduledDelivery']);
        Route::get('pending-delivery',[V12ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[V12ParcelController::class, 'deliveryCompleted']);
        Route::post('otp-verify',[V12ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[V12ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[V12ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[V12ParcelController::class, 'cancel']);
        Route::post('delivered',[V12ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[V12ParcelController::class, 'parcelDetails']);
        Route::get('my-pickup-merchants',[V12ParcelController::class, 'myPickupMerchants']);
        Route::post('pickup-received',[V12ParcelController::class, 'pickupReceived']);
    });
});


Route::prefix('v13')->group(function() {
    Route::get('configaration', [ConfigController::class, 'configaration']); // without token
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [V13AuthController::class, 'loginOtp']);
        Route::post('login', [V13AuthController::class, 'login']);
        Route::post('forgot-password-otp', [V13AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [V13AuthController::class, 'forgotPasswordPost']);

    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[V13AuthController::class,'profile']);
        Route::get('logout',[V13AuthController::class, 'logout']);
        Route::post('update-profile',[V13AuthController::class, 'updateProfile']);
        Route::post('update-profile-image',[V13AuthController::class, 'updateProfileImage']);
        Route::get('payment-logs',[V13AuthController::class, 'paymentLogs']);
        Route::get('cash-deposits',[V13AuthController::class, 'cashDeposits']);
        Route::get('my-pickup',[V13ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[V13ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[V13ParcelController::class, 'pickupCompleted']);
        Route::get('my-delivery',[V13ParcelController::class, 'myDelivery']);
        Route::get('my-re-scheduled-delivery',[V13ParcelController::class, 'myReScheduledDelivery']);
        Route::get('pending-delivery',[V13ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[V13ParcelController::class, 'deliveryCompleted']);
        Route::post('otp-verify',[V13ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[V13ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[V13ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[V13ParcelController::class, 'cancel']);
        Route::post('delivered',[V13ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[V13ParcelController::class, 'parcelDetails']);
        Route::get('my-pickup-merchants',[V13ParcelController::class, 'myPickupMerchants']);
        Route::post('pickup-received',[V13ParcelController::class, 'pickupReceived']);
        Route::post('parcel-delivery',[V13ParcelController::class, 'parceldelivery']);
        Route::get('my-return',[V13ParcelController::class, 'myReturn']);
        Route::get('get-sip-configuration', [ConfigController::class, 'getConfiguration']); // with token
        //return API
        Route::Post('parcel-return', [V13ParcelController::class, "ParcelReturn"]);
        Route::Post('confirm-returned', [V13ParcelController::class, "confirmReturned"]);
        Route::get('bulk-return-list', [V13ParcelController::class, "bulkReturnList"]);
        Route::post('bulk-return-details', [V13ParcelController::class, "bulkReturnDetails"]);
        Route::Post('bulk-confirm-returned', [V13ParcelController::class, "bulkconfirmReturned"]);
        Route::Post('bulk-otp-returned', [V13ParcelController::class, "bulkOtpReturned"]);
    });
});
