<?php

use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\BulkWithdrawController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\HubController;
use App\Http\Controllers\Admin\LiveSearchController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\ParcelController;
use App\Http\Controllers\Admin\PreferenceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SmsCampaignController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ThirdPartyController;
use App\Http\Controllers\Merchant\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DeliveryManController;
use App\Http\Controllers\Merchant\AuthController as MerchantAuthController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\ParcelController as MerchantParcelController;
use App\Http\Controllers\Merchant\WithdrawController as MerchantWithdrawController;
use App\Http\Controllers\Admin\WithdrawController as AdminWithdrawController;
use App\Http\Controllers\Merchant\MerchantStaffController as MerchantStaffController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\FundTransferController;
use App\Http\Controllers\Admin\PathaoServiceController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', [TestController::class, 'index']);
Route::group(['middleware'=>'XSS'], function() {

Route::group(
	[
		'prefix' => LaravelLocalization::setLocale(),
		'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
	], function(){

	// before login
	Route::middleware(['LogoutCheck'])->group(function () {
		Route::get('/', [MerchantAuthController::class, 'loginForm'])->name('merchant.login');

		route::get('admin/login', [AuthController::class, 'loginForm'])->name('admin.login');
		route::post('login', [AuthController::class, 'login'])->name('login');
		route::get('logout', [AuthController::class, 'logout'])->name('logout');
		route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
		route::post('forgot-password', [AuthController::class, 'forgotPasswordPost'])->name('forgot-password');

		Route::get('reset/{email}/{activationCode}', [AuthController::class, 'resetPassword']);
		Route::post('reset/{email}/{activationCode}', [AuthController::class, 'PostResetPassword'])->name('reset-password');

        Route::get('activation/{email}/{activationCode}',[MerchantAuthController::class,'activation']);

		//merchant routes
        route::get('register', [MerchantAuthController::class, 'registerForm'])->name('merchant-register');
        route::post('register', [MerchantAuthController::class, 'register'])->name('merchant-register');
        route::get('confirm-otp', [MerchantAuthController::class, 'otpConfirm'])->name('confirm-otp');
        route::post('confirm-otp', [MerchantAuthController::class, 'otpConfirmPost'])->name('confirm-otp');
        route::get('request-otp/{id}', [MerchantAuthController::class, 'otpRequest'])->name('request-otp');
        route::get('login', [MerchantAuthController::class, 'loginForm'])->name('merchant.login');
        route::post('merchant-login', [MerchantAuthController::class, 'login'])->name('merchant-login');

	});

	// common route after login
	Route::middleware(['LoginCheckCommon'])->group(function () {
		Route::get('mode-change', [CommonController::class, 'modeChange']);
		route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('logout-other-devices', [CommonController::class, 'logoutOtherDevices'])->name('logout.other.devices');
	});

	// after login
	Route::middleware(['LoginCheck'])->group(function () {

		route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
		route::get('default', [DashboardController::class, 'report'])->name('admin.default.dashboard');
		route::post('custom-report', [DashboardController::class, 'customDateRange'])->name('admin.default.dashboard.custom');

		//parcel bulk import
        Route::get('download-sample', [ImportExportController::class, 'export'])->name('export');

        Route::prefix('admin')->group(function() {
			Route::resource('roles', RoleController::class);

			Route::get('users', [UserController::class, 'index'])->name('users')->middleware('PermissionCheck:user_read');
			Route::get('user-create', [UserController::class, 'create'])->name('user.create')->middleware('PermissionCheck:user_create');
			Route::post('user-store', [UserController::class, 'store'])->name('user.store')->middleware('PermissionCheck:user_create');
			Route::get('user-edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('PermissionCheck:user_update');
			Route::post('user-update', [UserController::class, 'update'])->name('user.update')->middleware('PermissionCheck:user_update');
			Route::delete('user/delete/{id}', [UserController::class, 'delete'])->middleware('PermissionCheck:user_delete');
			Route::get('change-role', [UserController::class, 'changeRole']);
			Route::POST('user-status', [UserController::class, 'statusChange']);
			// staff detail
			Route::get('staff-personal-info/{id}', [UserController::class, 'personalInfo'])->name('detail.staff.personal.info');
			Route::get('staff-account-activity/{id}', [UserController::class, 'accountActivity'])->name('detail.staff.account-activity');
			Route::get('staff-payment-logs/{id}', [UserController::class, 'paymentLogs'])->name('detail.staff.payment.logs');


			Route::get('profile', [CommonController::class, 'profile'])->name('staff.profile');
			Route::get('payment-logs', [CommonController::class, 'paymentLogs'])->name('staff.payment.logs');
			Route::get('notifications', [CommonController::class, 'notification'])->name('staff.notifications');
			Route::get('account-activity', [CommonController::class, 'accountActivity'])->name('staff.account-activity');
			Route::get('security-settings', [CommonController::class, 'securitySetting'])->name('staff.security-settings');
			Route::post('change-password', [CommonController::class, 'changePassword'])->name('staff.change-password');
            Route::post('profile-update', [CommonController::class, 'profileUpdate'])->name('staff.update.profile');

            //merchant
            Route::get('merchants', [MerchantController::class, 'index'])->name('merchant')->middleware('PermissionCheck:merchant_read');
            Route::get('merchant/create', [MerchantController::class, 'create'])->name('merchant.create')->middleware('PermissionCheck:merchant_create');
            Route::post('merchant-store', [MerchantController::class, 'store'])->name('merchant.store')->middleware('PermissionCheck:merchant_create');
            Route::get('merchant-edit/{id}', [MerchantController::class, 'edit'])->name('merchant.edit')->middleware('PermissionCheck:merchant_update');
            Route::post('merchant-update', [MerchantController::class, 'update'])->name('merchant.update')->middleware('PermissionCheck:merchant_update');
            Route::delete('merchant/delete/{id}', [MerchantController::class, 'delete'])->middleware('PermissionCheck:merchant_delete');
            Route::any('merchant-filter', [MerchantController::class, 'filter'])->name('merchant.filter')->middleware('PermissionCheck:merchant_read');
			Route::POST('merchant-status', [MerchantController::class, 'statusChange'])->middleware('PermissionCheck:merchant_update');

            Route::get('merchant-log/{id}', [MerchantController::class, 'merchantLog'])->name('admin.merchant.edit.log')->middleware('PermissionCheck:merchant_read');

			Route::get('merchant-personal-info/{id}', [MerchantController::class, 'personalInfo'])->name('detail.merchant.personal.info')->middleware('PermissionCheck:merchant_read');
			Route::get('merchant-account-activity/{id}', [MerchantController::class, 'accountActivity'])->name('detail.merchant.account-activity')->middleware('PermissionCheck:merchant_account_activity_read');

			Route::get('merchant-permissions/{id}', [MerchantController::class, 'permissions'])->name('detail.merchant.permissions')->middleware('PermissionCheck:merchant_read');
			Route::get('merchant-account-activity/{id}', [MerchantController::class, 'accountActivity'])->name('detail.merchant.account-activity')->middleware('PermissionCheck:merchant_account_activity_read');
            Route::post('merchant-permission-update/{id}', [MerchantController::class, 'permissionUpdate'])->name('detail.merchant.permission.update')->middleware('PermissionCheck:merchant_staff_update');

            Route::get('merchant-staffs/{id}', [StaffController::class, 'staffs'])->name('detail.merchant.staffs')->middleware('PermissionCheck:merchant_staff_read');
            Route::get('merchant-staff-create/{id}', [StaffController::class, 'staffCreate'])->name('detail.merchant.staff.create')->middleware('PermissionCheck:merchant_staff_create');
            Route::post('merchant-staff-store', [StaffController::class, 'staffStore'])->name('detail.merchant.staff.store')->middleware('PermissionCheck:merchant_staff_create');
            Route::get('merchant-staff-edit/{id}', [StaffController::class, 'staffEdit'])->name('detail.merchant.staff.edit')->middleware('PermissionCheck:merchant_staff_update');
            Route::post('merchant-staff-update', [StaffController::class, 'staffUpdate'])->name('detail.merchant.staff.update')->middleware('PermissionCheck:merchant_staff_update');

            Route::get('merchant-staff-personal-info/{id}', [StaffController::class, 'personalInfo'])->name('detail.merchant.staff.personal.info')->middleware('PermissionCheck:merchant_staff_read');
            Route::get('merchant-staff-account-activity/{id}', [StaffController::class, 'accountActivity'])->name('detail.merchant.staffs.account-activity')->middleware('PermissionCheck:merchant_staff_read');

			Route::get('merchant-charge/{id}', [MerchantController::class, 'charge'])->name('detail.merchant.charge')->middleware('PermissionCheck:merchant_charge_read');
			Route::get('merchant-cod-charge/{id}', [MerchantController::class, 'codCharge'])->name('detail.merchant.cod.charge')->middleware('PermissionCheck:merchant_cod_charge_read');

            Route::get('merchant-company/{id}', [MerchantController::class, 'company'])->name('detail.merchant.company')->middleware('PermissionCheck:merchant_read');
            Route::get('merchant-statements/{id}', [MerchantController::class, 'statements'])->name('detail.merchant.statements')->middleware('PermissionCheck:merchant_payment_logs_read');
            Route::get('merchant-shops/{id}', [MerchantController::class, 'shops'])->name('detail.merchant.shops')->middleware('PermissionCheck:merchant_shop_read');
            Route::post('merchant-shop-add', [MerchantController::class, 'shopStore'])->name('admin.merchant.add.shop')->middleware('PermissionCheck:merchant_shop_create');
            Route::delete('shop/delete/{id}', [MerchantController::class, 'shopDelete'])->middleware('PermissionCheck:merchant_shop_delete');
            Route::get('merchant-api-credentials/{id}', [MerchantController::class, 'apiCredentials'])->name('detail.merchant.api.credentials')->middleware('PermissionCheck:merchant_api_credentials_read');
            Route::post('merchant-api-credentials-update', [MerchantController::class, 'apiCredentialsUpdate'])->name('detail.merchant.api.credentials.update')->middleware('PermissionCheck:merchant_api_credentials_update');

            //name change request
            Route::get('name-change-requests', [MerchantController::class, 'chengeRequest'])->name('name-change-requests')->middleware('PermissionCheck:name_change_read');
            Route::get('name-change-requests/{id}', [MerchantController::class, 'chengeRequestAuthorize'])->name('name.change.authorize')->middleware('PermissionCheck:name_change_accept');
            Route::get('name-change-delete/{id}', [MerchantController::class, 'chengeRequestDelete'])->name('name.change.delete')->middleware('PermissionCheck:name_change_accept');
            Route::get('name-change-filter', [MerchantController::class, "nameChangeFilter"])->name('name.change.filter');
            //shop default status change
            Route::post('shop-default-update', [MerchantController::class, 'changeDefault'])->name('admin.merchant.default.shop')->middleware('PermissionCheck:merchant_shop_update');
            Route::get('shop-edit', [MerchantController::class, 'shopEdit'])->name('admin.merchant.edit.shop')->middleware('PermissionCheck:merchant_shop_update');
            Route::post('shop-update', [MerchantController::class, 'shopUpdate'])->name('admin.merchant.update.shop')->middleware('PermissionCheck:merchant_shop_update');

            Route::get('merchant-payment-accounts/{id}', [MerchantController::class, 'paymentAccounts'])->name('detail.merchant.payment.accounts')->middleware('PermissionCheck:merchant_payment_account_read');
            Route::get('merchant-payment-account-others/{id}', [MerchantController::class, 'paymentOthersAccount'])->name('detail.merchant.payment.accounts.others')->middleware('PermissionCheck:merchant_payment_account_read');

            Route::get('merchant-payment-account-edit/{id}', [MerchantController::class, 'paymentAccountEdit'])->name('detail.merchant.payment.bank.edit')->middleware('PermissionCheck:merchant_payment_account_update');
            Route::post('merchant-payment-account-update', [MerchantController::class, 'paymentAccountUpdate'])->name('detail.merchant.payment.bank.update')->middleware('PermissionCheck:merchant_payment_account_update');
            Route::get('merchant-payment-account-others-edit/{id}', [MerchantController::class, 'paymentAccountOthersEdit'])->name('detail.merchant.payment.others.edit')->middleware('PermissionCheck:merchant_payment_account_update');
            Route::post('merchant-payment-account-others-update', [MerchantController::class, 'paymentAccountOthersUpdate'])->name('detail.merchant.payment.others.update')->middleware('PermissionCheck:merchant_payment_account_update');

            //delivery man
            Route::get('delivery-man', [DeliveryManController::class, 'index'])->name('delivery.man')->middleware('PermissionCheck:deliveryman_read');
            Route::get('delivery-man-create', [DeliveryManController::class, 'create'])->name('delivery.man.create')->middleware('PermissionCheck:deliveryman_create');
            Route::post('delivery-man-store', [DeliveryManController::class, 'store'])->name('delivery.man.store')->middleware('PermissionCheck:deliveryman_create');
            Route::get('delivery-man-edit/{id}', [DeliveryManController::class, 'edit'])->name('delivery.man.edit')->middleware('PermissionCheck:deliveryman_update');
			Route::post('delivery-man-update', [DeliveryManController::class, 'update'])->name('delivery.man.update')->middleware('PermissionCheck:deliveryman_update');
			Route::delete('delivery-man-delete/{id}', [DeliveryManController::class, 'delete'])->middleware('PermissionCheck:deliveryman_delete');
			Route::POST('delivery-man-status', [DeliveryManController::class, 'statusChange'])->middleware('PermissionCheck:deliveryman_update');
			Route::any('delivery-man-filter', [DeliveryManController::class, 'filter'])->name('delivery.man.filter')->middleware('PermissionCheck:deliveryman_read');
            Route::get("delivery-edit-log/{id}", [DeliveryManController::class, 'editLog'])->name('admin.delivery.edit.log')->middleware('PermissionCheck:deliveryman_read');

            Route::POST('delivery-man-vertual', [DeliveryManController::class, 'vertualChange'])->middleware('PermissionCheck:deliveryman_update');
            Route::POST('delivery-man-shuttle', [DeliveryManController::class, 'shuttleChange'])->middleware('PermissionCheck:deliveryman_update');
            //details
			Route::get('delivery-man-personal-info/{id}', [DeliveryManController::class, 'personalInfo'])->name('detail.delivery.man.personal.info')->middleware('PermissionCheck:deliveryman_read');
			Route::get('delivery-man-account-activity/{id}', [DeliveryManController::class, 'accountActivity'])->name('detail.delivery.man.account-activity')->middleware('PermissionCheck:deliveryman_account_activity_read');
            Route::get('delivery-man-statements/{id}', [DeliveryManController::class, 'statements'])->name('detail.delivery.man.statements')->middleware('PermissionCheck:deliveryman_payment_logs_read');
            Route::get('get-delivery-man-balance/{id}', [DeliveryManController::class, 'balance'])->name('income.delivery.man.balance')->middleware('PermissionCheck:income_create');


			//merchant parcel request
            Route::get('parcel', [ParcelController::class, 'index'])->name('parcel')->middleware('PermissionCheck:parcel_read');
            Route::get('parcel-create', [ParcelController::class, 'create'])->name('parcel.create')->middleware('PermissionCheck:parcel_create');
            Route::post('parcel-store', [ParcelController::class, 'store'])->name('parcel.store')->middleware('PermissionCheck:parcel_create');
            Route::get('parcel-edit/{id}', [ParcelController::class, 'edit'])->name('parcel.edit')->middleware('PermissionCheck:parcel_update');
            Route::post('parcel-update', [ParcelController::class, 'update'])->name('parcel.update')->middleware('PermissionCheck:parcel_update');
            Route::post('parcel-delete', [ParcelController::class, 'parcelDelete'])->name('parcel-delete')->middleware('PermissionCheck:parcel_delete');
            Route::any('parcel-filter', [ParcelController::class, 'filter'])->name('admin.parcel.filter');
            Route::get('parcel-detail/{id}', [ParcelController::class, 'detail'])->name('admin.parcel.detail');
            Route::get('parcel-print/{id}', [ParcelController::class, 'print'])->name('admin.parcel.print');
            Route::get('parcel-duplicate/{id}', [ParcelController::class, 'duplicate'])->name('admin.parcel.duplicate');
            Route::get('sticker/{id}', [ParcelController::class, 'sticker'])->name('admin.parcel.sticker');
            Route::get('notify-pickup-man/{id}', [ParcelController::class, 'notifyPickupMan'])->name('admin.parcel.notify.pickupman');

            Route::get('parcel-filtering/{slug}', [ParcelController::class, 'parcelFiltering'])->name('admin.parcel.filtering');

            ############### Pathao
            Route::get('pathao/parcel-short-details', [PathaoServiceController::class, 'parcelDetails']);
            Route::get('pathao/bulk-order', [PathaoServiceController::class, 'pathaoBulkOrder']);




            //for getting shops of selected merchant on select shop dropdown
            Route::get('shops', [ParcelController::class, 'shops'])->name('merchant.change');
            //for getting shop phone number and address
            Route::get('shop', [ParcelController::class, 'shop'])->name('admin.merchant.shop');
            Route::get('shop/default', [ParcelController::class, 'default']);
            Route::get('merchant/staff', [ParcelController::class, 'merchantStaff']);

            Route::post('assign-pickup-man', [ParcelController::class, 'assignPickupMan'])->name('assign.pickup.man')->middleware('PermissionCheck:parcel_pickup_assigned');
            Route::post('assign-delivery-man', [ParcelController::class, 'assignDeliveryMan'])->name('assign.delivery.man')->middleware('PermissionCheck:parcel_delivery_assigned');
			//re schedule pickup
			Route::post('re-schedule-pickup', [ParcelController::class, 'reSchedulePickup'])->middleware('PermissionCheck:parcel_reschedule_pickup');
			Route::post('re-schedule-pickup-man', [ParcelController::class, 'reSchedulePickupMan'])->name('re-schedule.pickup')->middleware('PermissionCheck:parcel_reschedule_pickup');
			//re schedule delivery
			Route::post('re-schedule-delivery', [ParcelController::class, 'reScheduleDelivery'])->middleware('PermissionCheck:parcel_reschedule_delivery');
			Route::post('re-schedule-delivery-man', [ParcelController::class, 'reScheduleDeliveryMan'])->name('re-schedule.delivery')->middleware('PermissionCheck:parcel_reschedule_delivery');
			Route::post('return-assign-to-merchant', [ParcelController::class, 'returnAssignToMerchant'])->name('return.assign.to.merchant')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
			//cancel parcel with note
            Route::post('parcel-cancel', [ParcelController::class, 'parcelCancel'])->name('parcel-cancel')->middleware('PermissionCheck:parcel_cancel');
            //notes added
            Route::post('parcel-receive-by-pickupman', [ParcelController::class, 'parcelReceiveByPickupman'])->name('parcel-receive-by-pickupman')->middleware('PermissionCheck:parcel_received');

            Route::post('parcel-receive', [ParcelController::class, 'parcelReceive'])->name('parcel-receive')->middleware('PermissionCheck:parcel_received');
            Route::post('parcel-delivered', [ParcelController::class, 'parcelDelivery'])->name('parcel-delivered')->middleware('PermissionCheck:parcel_delivered');
            Route::post('partially-delivered', [ParcelController::class, 'partialDelivery'])->name('partially-delivered')->middleware('PermissionCheck:parcel_delivered');
            Route::post('parcel-returned-to-greenx', [ParcelController::class, 'parcelReturnToGreenx'])->name('parcel-returned-to-greenx')->middleware('PermissionCheck:parcel_returned_to_greenx');
            Route::post('parcel-returned-to-merchant', [ParcelController::class, 'returnToMerchant'])->name('parcel-returned-to-merchant')->middleware('PermissionCheck:parcel_returned_to_merchant');
            Route::post('reverse-from-cancel', [ParcelController::class, 'reverseFromCancel'])->name('reverse-from-cancel')->middleware('PermissionCheck:parcel_backward');
            Route::post('transfer-to-hub', [ParcelController::class, 'transferToHub'])->name('transfer-to-hub')->middleware('PermissionCheck:parcel_transfer_to_hub');
            Route::post('transfer-receive-to-hub', [ParcelController::class, 'transferReceiveToHub'])->name('transfer-receive-to-hub')->middleware('PermissionCheck:parcel_transfer_receive_to_hub');
            Route::post('parcel-otp-generate-check', [ParcelController::class, 'parcelGenerateOtpCheck'])->name('parcel-otp-generate-check')->middleware('PermissionCheck:parcel_returned_to_merchant');

            //merchant OTP code
            Route::post('parcel-otp-generate', [ParcelController::class, 'parcelGenerateOtp'])->name('parcel-otp-generate')->middleware('PermissionCheck:parcel_returned_to_merchant');
                    Route::post('delivery-otp-check', [ParcelController::class, 'deliveryOtpCheck'])->name('delivery-otp-check');

            //
            Route::post('delivery-reverse', [ParcelController::class, 'deliveryReverse'])->name('delivery-reverse')->middleware('PermissionCheck:parcel_backward');

			Route::get('incomes', [AccountController::class, 'index'])->name('incomes')->middleware('PermissionCheck:income_read');
			Route::get('income-create', [AccountController::class, 'create'])->name('incomes.create')->middleware('PermissionCheck:income_create');
			Route::post('income-store', [AccountController::class, 'store'])->name('incomes.store')->middleware('PermissionCheck:income_create');
			Route::get('income-edit/{id}', [AccountController::class, 'edit'])->name('incomes.edit')->middleware('PermissionCheck:income_update');
			Route::post('income-update', [AccountController::class, 'update'])->name('incomes.update')->middleware('PermissionCheck:income_update');
			Route::delete('income-delete/{id}', [AccountController::class, 'delete'])->middleware('PermissionCheck:income_delete');
            Route::get('get-delivery-man-balance', [AccountController::class, 'balance'])->name('income.delivery.man.balance')->middleware('PermissionCheck:income_create');

            Route::get('credit-from-merchant-create', [AccountController::class, 'creditCreate'])->name('incomes.receive.from.merchant')->middleware('PermissionCheck:income_create');
            Route::post('credit-from-merchant-store', [AccountController::class, 'creditStore'])->name('incomes.receive.from.merchant.store')->middleware('PermissionCheck:income_create');
            Route::get('credit-from-merchant-edit/{id}', [AccountController::class, 'creditEdit'])->name('incomes.receive.from.merchant.edit')->middleware('PermissionCheck:income_update');
            Route::post('credit-from-merchant-update', [AccountController::class, 'creditUpdate'])->name('incomes.receive.from.merchant.update')->middleware('PermissionCheck:income_update');
            Route::delete('credit-from-merchant-delete/{id}', [AccountController::class, 'creditDelete'])->middleware('PermissionCheck:income_delete');

            Route::get('merchant-parcel',[AccountController::class, 'merchantParcels'])->name('admin.merchant.parcel')->middleware('PermissionCheck:merchant_read');

			Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses')->middleware('PermissionCheck:expense_read');
			Route::get('expense-create', [ExpenseController::class, 'create'])->name('expenses.create')->middleware('PermissionCheck:expense_create');
			Route::post('expense-store', [ExpenseController::class, 'store'])->name('expenses.store')->middleware('PermissionCheck:expense_create');
			Route::get('expense-edit/{id}', [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('PermissionCheck:expense_update');
			Route::post('expense-update', [ExpenseController::class, 'update'])->name('expenses.update')->middleware('PermissionCheck:expense_update');
			Route::delete('expense-delete/{id}', [ExpenseController::class, 'delete'])->middleware('PermissionCheck:expense_delete');

			Route::get('get-balance-info', [CommonController::class, 'getBalanceInfo']);
			Route::get('get-accounts', [CommonController::class, 'getAccounts']);
			Route::get('user-accounts', [CommonController::class, 'userAccounts'])->name('user.accounts');
			Route::get('staff-accounts/{id}', [UserController::class, 'staffAccounts'])->name('staff.accounts');

            Route::get('accounts', [BankAccountController::class, 'index'])->name('admin.account')->middleware('PermissionCheck:account_read');
            Route::get('account-create', [BankAccountController::class, 'create'])->name('admin.account.create')->middleware('PermissionCheck:account_create');
            Route::post('account-store', [BankAccountController::class, 'store'])->name('admin.account.store')->middleware('PermissionCheck:account_create');
            Route::get('account-edit/{id}', [BankAccountController::class, 'edit'])->name('admin.account.edit')->middleware('PermissionCheck:account_update');
            Route::post('account-update', [BankAccountController::class, 'update'])->name('admin.account.update')->middleware('PermissionCheck:account_update');
            // Route::delete('account-delete/{id}', [BankAccountController::class, 'delete'])->middleware('PermissionCheck:account_delete');

            Route::get('account-view/{id}', [BankAccountController::class, 'view'])->name('admin.account.view')->middleware('PermissionCheck:account_view');
            Route::get('account-statement/{id}', [BankAccountController::class, 'statement'])->name('admin.account.statement')->middleware('PermissionCheck:account_statement');
            Route::get('staff-account-statement/{id}', [BankAccountController::class, 'staffStatement'])->name('staff.account.statement');

            Route::get('index', [FundTransferController::class, 'index'])->name('admin.fund-transfer')->middleware('PermissionCheck:fund_transfer_read');
            Route::get('create', [FundTransferController::class, 'create'])->name('admin.fund-transfer.create')->middleware('PermissionCheck:fund_transfer_create');
            Route::post('store', [FundTransferController::class, 'store'])->name('admin.fund-transfer.store')->middleware('PermissionCheck:fund_transfer_create');
            Route::get('edit/{id}', [FundTransferController::class, 'edit'])->name('admin.fund-transfer.edit')->middleware('PermissionCheck:fund_transfer_update');
            Route::post('update', [FundTransferController::class, 'update'])->name('admin.fund-transfer.update')->middleware('PermissionCheck:fund_transfer_update');
            Route::delete('fund-transfer-delete/{id}', [FundTransferController::class, 'delete'])->middleware('PermissionCheck:fund_transfer_delete');


			Route::get('withdraws', [AdminWithdrawController::class, 'index'])->name('admin.withdraws')->middleware('PermissionCheck:withdraw_read');
            Route::get('withdraw-create', [AdminWithdrawController::class, 'create'])->name('admin.withdraw.create')->middleware('PermissionCheck:withdraw_create');
            Route::post('withdraw-store', [AdminWithdrawController::class, 'store'])->name('admin.withdraw.store')->middleware('PermissionCheck:withdraw_create');
            Route::get('withdraw-edit/{id}', [AdminWithdrawController::class, 'edit'])->name('admin.withdraw.edit')->middleware('PermissionCheck:withdraw_update');
            Route::post('withdraw-update', [AdminWithdrawController::class, 'update'])->name('admin.withdraw.update')->middleware('PermissionCheck:withdraw_update');
            Route::get('withdraw-details/{id}', [AdminWithdrawController::class, 'details'])->name('admin.withdraw.details')->middleware('PermissionCheck:withdraw_read');
            Route::get('payment-invoice/{id}', [AdminWithdrawController::class, 'invoice'])->name('admin.withdraw.invoice')->middleware('PermissionCheck:withdraw_read');
            Route::get('payment-invoice-print/{id}', [AdminWithdrawController::class, 'print'])->name('admin.withdraw.invoice.print')->middleware('PermissionCheck:withdraw_read');

            //bulk payments
            Route::get('bulk-withdraws', [BulkWithdrawController::class, 'index'])->name('admin.withdraws.bulk')->middleware('PermissionCheck:bulk_withdraw_read');
            Route::get('bulk-withdraw-create', [BulkWithdrawController::class, 'create'])->name('admin.withdraws.bulk.create')->middleware('PermissionCheck:bulk_withdraw_create');
            Route::post('bulk-withdraw-store', [BulkWithdrawController::class, 'store'])->name('admin.withdraws.bulk.store')->middleware('PermissionCheck:bulk_withdraw_create');
            Route::get('bulk-withdraw-edit/{id}', [BulkWithdrawController::class, 'edit'])->name('admin.withdraws.bulk.edit')->middleware('PermissionCheck:bulk_withdraw_update');
            Route::get('get-batches', [BulkWithdrawController::class, 'batches'])->name('get-batches')->middleware('PermissionCheck:add_to_bulk_withdraw');
            Route::post('bulk-withdraw-update', [BulkWithdrawController::class, 'update'])->name('admin.withdraws.bulk.update')->middleware('PermissionCheck:bulk_withdraw_update');
            Route::post('process-bulk-payment', [BulkWithdrawController::class, 'processPayment'])->name('admin.bulk.process-payment')->middleware('PermissionCheck:bulk_withdraw_process');
            Route::get('bulk-payment-invoice/{id}', [BulkWithdrawController::class, 'invoice'])->name('admin.withdraw.invoice.bulk')->middleware('PermissionCheck:bulk_withdraw_read');
            Route::delete('bulk-withdraw-delete/{id}', [BulkWithdrawController::class, 'delete'])->middleware('PermissionCheck:bulk_withdraw_delete');


            Route::get('get-merchant-info', [AdminWithdrawController::class, 'getMerchantInfo'])->middleware('PermissionCheck:merchant_read');
            Route::post('withdraw-status/{id}/{status}', [AdminWithdrawController::class, 'chargeStatus'])->middleware('PermissionCheck:withdraw_process', 'PermissionCheck:withdraw_reject');

            Route::post('process-payment', [AdminWithdrawController::class, 'processPayment'])->name('process-payment')->middleware('PermissionCheck:withdraw_process');
            Route::post('approve-payment', [AdminWithdrawController::class, 'approvePayment'])->name('approve-payment')->middleware('PermissionCheck:withdraw_process');
            Route::post('reject-payment', [AdminWithdrawController::class, 'rejectPayment'])->name('reject-payment')->middleware('PermissionCheck:withdraw_reject');
            Route::post('update-payment-batch', [AdminWithdrawController::class, 'updateBatch'])->name('update-payment-batch')->middleware('PermissionCheck:add_to_bulk_withdraw');
            //reports

            Route::get('transaction-history', [ReportController::class, 'transactionHistory'])->name('admin.transaction_history')->middleware('PermissionCheck:transaction_history_read');
            Route::any('transactions', [ReportController::class, 'transactionSearch'])->name('admin.search.transaction')->middleware('PermissionCheck:transaction_history_read');

            //daily summery
            Route::get('account-summary', [ReportController::class, 'accountSummary'])->name('admin.account.summary')->middleware('PermissionCheck:transaction_history_read');
            Route::any('account-summary-info', [ReportController::class, 'SummaryInfo'])->name('admin.account.summary-info')->middleware('PermissionCheck:transaction_history_read');

            Route::get('parcels', [ReportController::class, 'parcels'])->name('admin.parcels')->middleware('PermissionCheck:parcels_summary_read');
            Route::any('search-parcels', [ReportController::class, 'parcelSearch'])->name('admin.search.parcels')->middleware('PermissionCheck:parcels_summary_read');

            Route::get('merchant-summary', [ReportController::class, 'merchantReport'])->name('admin.merchant.summary')->middleware('PermissionCheck:merchant_summary_report_read');
            Route::any('merchant-summary-report', [ReportController::class, 'merchantReportSearch'])->name('admin.search.merchant.summary')->middleware('PermissionCheck:merchant_summary_report_read');

            Route::get('income-expense', [ReportController::class, 'incomeExpense'])->name('admin.income.expense')->middleware('PermissionCheck:income_expense_report_read');
            Route::any('search-income-expense', [ReportController::class, 'incomeExpenseSearch'])->name('admin.search.income.expense')->middleware('PermissionCheck:income_expense_report_read');

            Route::get('total-summery', [ReportController::class, 'totalSummery'])->name('admin.total_summery')->middleware('PermissionCheck:total_summary_read');
            Route::any('total-summery-report', [ReportController::class, 'totalSummerySearch'])->name('admin.total_summery.report')->middleware('PermissionCheck:total_summary_read');

            Route::get('agent-sales-summery', [ReportController::class, 'agentSalesSummery'])->name('admin.agent.sales.summary')->middleware('PermissionCheck:merchant_summary_report_read');
            Route::any('agent-sales-summery', [ReportController::class, 'agentSalesSummerySearch'])->name('admin.serch.agent.sales.summary')->middleware('PermissionCheck:merchant_summary_report_read');

            //  sms preference
            Route::get('sms-preference', [PreferenceController::class, 'smsPreference'])->name('sms.preference.setting')->middleware('PermissionCheck:sms_setting_read');
            Route::post('sms-status', [PreferenceController::class, 'statusChange'])->middleware('PermissionCheck:sms_setting_update');
            Route::post('sms-masking-status', [PreferenceController::class, 'maskingStatusChange'])->middleware('PermissionCheck:sms_setting_update');

            //  live search
            Route::get('get-merchant-live', [LiveSearchController::class,'getMerchant'])->name('get-merchant-live');
            Route::get('get-delivery-man-live', [LiveSearchController::class,'getDeliveryMan'])->name('get-delivery-man-live');
            Route::get('get-user-man-live', [LiveSearchController::class,'getUser'])->name('get-user-live');
            Route::get('get-parcel-live', [LiveSearchController::class,'getParcel'])->name('get-parcel-live');
            Route::get('get-third-party-live', [LiveSearchController::class,'getThirdParty'])->name('get-third-party-live');
            Route::get('get-shuttle-man-live', [LiveSearchController::class,'getShuttleMan'])->name('get-shuttle-man-live');

            //sms routes for campaign and to pickup man
            Route::get('sms-campaign', [SmsCampaignController::class, 'sendSms'])->name('sms.campaign')->middleware('PermissionCheck:sms_campaign_message_send');
            Route::post('sms-campaign-post', [SmsCampaignController::class, 'sendSmsPost'])->name('sms.campaign.post')->middleware('PermissionCheck:sms_campaign_message_send');
            Route::get('custom-sms-campaign', [SmsCampaignController::class, 'sendCustomSms'])->name('custom.sms.campaign')->middleware('PermissionCheck:custom_sms_send');
            Route::post('custom-sms-post', [SmsCampaignController::class, 'sendSmsPost'])->name('sms.custom.post')->middleware('PermissionCheck:custom_sms_send');

            //bulk work routes
            Route::get('assigning-delivery-man', [BulkController::class, 'create'])->name('bulk.assigning')->middleware('PermissionCheck:parcel_delivery_assigned');
            Route::get('add-parcel-row/{parcel_no}', [BulkController::class, 'add'])->name('bulk.assigning.parcel');
            Route::post('assign-delivery-save', [BulkController::class, 'save'])->name('bulk.assigning.parcel.save');

            Route::get('assigning-pickup-man', [BulkController::class, 'createPickup'])->name('bulk.pickup.assign')->middleware('PermissionCheck:parcel_pickup_assigned');
            Route::get('get-merchant-parcels', [BulkController::class, 'getParcels'])->name('bulk.assigning.parcel.pickup');
            Route::post('assign-pickup-save', [BulkController::class, 'bulkPickupAssign'])->name('bulk.pickup-assigning.parcel.save');
            //bulk hub transfer routes
            Route::get('parcel-transfer-to-hub', [BulkController::class, 'bulkTransferCreate'])->name('bulk.transfer')->middleware('PermissionCheck:parcel_transfer_to_hub');
            Route::get('add-transfer-parcel-row/{parcel_no}', [BulkController::class, 'transferAdd'])->name('bulk.transfer.parcel');
            Route::post('parcel-transfer-to-hub-save', [BulkController::class, 'bulkTransferSave'])->name('bulk.transfer.parcel.save');
            //bulk transfer receive to hub routes
            Route::get('parcel-transfer-receive-to-hub', [BulkController::class, 'bulkTransferReceive'])->name('bulk.transfer.receive')->middleware('PermissionCheck:parcel_transfer_to_hub');
            Route::get('add-receive-parcel-row/{parcel_no}', [BulkController::class, 'transferReceive'])->name('bulk.transfer.receive.parcel');
            Route::post('parcel-transfer-to-hub-receive', [BulkController::class, 'bulkTransferReceivePost'])->name('bulk.transfer.parcel.receive');

            //bulk return routes
            Route::get('assigning-return-man', [BulkController::class, 'bulkReturn'])->name('bulk.return')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::get('add-return-parcel-row/{parcel_no}', [BulkController::class, 'returnAdd'])->name('bulk.return.parcel')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::post('assign-return-save', [BulkController::class, 'returnSave'])->name('bulk.return.parcel.save');
            Route::get('merchant-return-add/{batch_no}', [BulkController::class,'returnedit'])->name('merchant.return.eidt')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::post('merchant-return-reverse', [ParcelController::class, 'parcelReturnReverse'])->name('merchant.return.reverse')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::post('bulk-return-update', [BulkController::class, 'bulkReturnUpdate'])->name('bulk.return.parcel.update')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');


            //bulk batch return
            Route::get('create-return-batch', [BulkController::class, "creatReturnBatch"])->name('create-return-batch')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::post('create-return-batch', [BulkController::class, "creatReturnBatchStore"])->name('create-return-batch-store')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');

           //bulk delivery asssing list
            Route::get('delivery-assign', [BulkController::class, 'deliveryAssignList'])->name('parcel.delivery-assign.list')->middleware('PermissionCheck:parcel_read');
            Route::get('delivery-assign-list/{id}',  [BulkController::class, 'assignList'])->name('parcel.delivery-assign.eidt')->middleware('PermissionCheck:parcel_read');
            Route::Post('delivery-assign-add', [BulkController::class, 'deliveryAssignAdd'])->name('bulk.delivery-assign.add')->middleware('PermissionCheck:parcel_delivery_assigned');
            Route::Post('delivery-assign-print', [BulkController::class, 'deliveryAssignPrint'])->name('bulk.delivery-assign.print')->middleware('PermissionCheck:parcel_read');
            Route::Post('delivery-assign-reverse', [ParcelController::class, 'assignReverse'])->name('parcel.delivery-assign.reverse')->middleware('PermissionCheck:parcel_delivery_assigned');

            //payment filter
            Route::any('payment-filter', [AdminWithdrawController::class, 'filter'])->name('admin.payment.filter');
            Route::get('reverse-options', [ParcelController::class, 'reverseOptions'])->name('parcel.reverse.options');
            Route::get('transfer-options', [ParcelController::class, 'transferOptions'])->name('parcel.transfer.options');
            Route::get('parcel-reverse-from-cancel/{id}/{status}', [ParcelController::class, 'reverseUpdate']);

            //hub routes
            Route::get('hubs', [HubController::class, 'index'])->name('admin.hub')->middleware('PermissionCheck:hub_read');
            Route::get('hub-create', [HubController::class, 'create'])->name('admin.hub.create')->middleware('PermissionCheck:hub_create');
            Route::post('hub-store', [HubController::class, 'store'])->name('admin.hub.store')->middleware('PermissionCheck:hub_create');
            Route::get('hub-edit/{id}', [HubController::class, 'edit'])->name('admin.hub.edit')->middleware('PermissionCheck:hub_update');
            Route::post('hub-update', [HubController::class, 'update'])->name('admin.hub.update')->middleware('PermissionCheck:hub_update');
            Route::delete('hub-delete/{id}', [HubController::class, 'delete'])->middleware('PermissionCheck:hub_delete');

            Route::get('get-current-cod', [ParcelController::class, 'parcelCod'])->middleware('PermissionCheck:parcel_delivered');

            Route::any('invoice-filter', [AdminWithdrawController::class, 'filterByMerchantName'])->name('admin.invoice.filter');

            Route::get('logout-user-all-devices/{id}', [UserController::class, 'logoutUserDevices'])->name('logout.user.all.devices')->middleware('PermissionCheck:user_logout_from_devices');

            //settings
            Route::post('setting-store',[SettingsController::class,'store'])->name('setting.store');
            Route::post('packaging.and.charge.update',[SettingsController::class,'packagingChargeUpdate'])->name('packaging.and.charge.update')->middleware('PermissionCheck:charge_setting_update');
            Route::get('pagination-setting',[SettingsController::class,'pagination'])->name('pagination.setting');
            Route::get('charges-setting',[SettingsController::class,'charges'])->name('charges.setting');
            Route::get('time-and-days-setting',[SettingsController::class,'timeAndDays'])->name('time-and-days.setting');
            Route::get('sms-setting',[SettingsController::class,'sms'])->name('sms.setting');
            Route::get('preference-setting',[SettingsController::class,'preference'])->name('preference.setting');
            Route::get('packaging-charge-setting',[SettingsController::class,'packingCharge'])->name('packaging.charge.setting');
            Route::get('add-charge-packaging-row',[SettingsController::class,'packingChargeAdd']);
            Route::get('database-backup-storage-setting',[SettingsController::class,'databaseBackupSetting'])->name('database.backup.storage.setting');
            Route::get('mobile-app-setting',[SettingsController::class,'mobileAppSetting'])->name('mobile.app.setting');
            Route::post('delete-packaging-charge/{id}', [SettingsController::class, 'deletePackagingCharge'])->middleware('PermissionCheck:charge_setting_update');
            Route::post('preference-status', [SettingsController::class, 'statusChange'])->middleware('PermissionCheck:preference_setting_update');
            Route::post('delivery-otp-permission', [SettingsController::class, 'deliveryOtpPermission'])->name('setting.delivery-otp-permission')->middleware('PermissionCheck:preference_setting_update');
            Route::get('sip-domain-setting',[SettingsController::class,'sip_domain'])->name('sip_domain.setting');
            Route::get('app-info-setting',[SettingsController::class,'appInfo'])->name('app_info.setting');

            //notice
            Route::get('notice', [NoticeController::class, 'index'])->name('notice')->middleware('PermissionCheck:notice_read');
            Route::get('notice-create', [NoticeController::class, 'create'])->name('notice.create')->middleware('PermissionCheck:notice_create');
            Route::post('notice-store', [NoticeController::class, 'store'])->name('notice.store')->middleware('PermissionCheck:notice_create');
            Route::get('notice-edit/{id}', [NoticeController::class, 'edit'])->name('notice.edit')->middleware('PermissionCheck:notice_update');
            Route::post('notice-update', [NoticeController::class, 'update'])->name('notice.update')->middleware('PermissionCheck:notice_update');
            Route::post('notice-status', [NoticeController::class, 'statusChange'])->middleware('PermissionCheck:notice_update');
            Route::delete('notice/delete/{id}', [NoticeController::class, 'delete'])->middleware('PermissionCheck:notice_delete');

            //third party routes
            Route::get('third-parties',[ThirdPartyController::class,'index'])->name('admin.third-parties')->middleware('PermissionCheck:third_party_read');
            Route::get('third-party-create', [ThirdPartyController::class, 'create'])->name('admin.third-party.create')->middleware('PermissionCheck:third_party_create');
            Route::post('third-party-store', [ThirdPartyController::class, 'store'])->name('admin.third-party.store')->middleware('PermissionCheck:third_party_create');
            Route::get('third-party-edit/{id}', [ThirdPartyController::class, 'edit'])->name('admin.third-party.edit')->middleware('PermissionCheck:third_party_update');
            Route::post('third-party-update', [ThirdPartyController::class, 'update'])->name('admin.third-party.update')->middleware('PermissionCheck:third_party_update');
            Route::delete('third-party-delete/{id}', [ThirdPartyController::class, 'delete'])->middleware('PermissionCheck:third_party_delete');
            Route::POST('third-party-status', [ThirdPartyController::class, 'changeStatus'])->middleware('PermissionCheck:third_party_update');

            Route::get('get-parcel-location',[ParcelController::class, 'location']);

            Route::get('closing-report/{id}',[ParcelController::class, 'download'])->name('admin.merchant.closing.report')->middleware('PermissionCheck:download_closing_report');
            Route::get('batch-payment/{id}',[BulkWithdrawController::class, 'download'])->name('admin.payment.report')->middleware('PermissionCheck:download_payment_sheet');
            Route::delete('remove-from-batch/{id}',[AdminWithdrawController::class, 'remove'])->name('admin.payment.remove.payment')->middleware('PermissionCheck:add_to_bulk_withdraw');

            Route::get('import', [ImportExportController::class, 'importExportView'])->name('import.csv')->middleware('PermissionCheck:parcel_create');
            Route::post('import', [ImportExportController::class, 'import'])->name('import')->middleware('PermissionCheck:parcel_create');
            // third party parcel create
            Route::get('get-thana-union', [ParcelController::class, 'getThanaUnion'])->name('admin.get-thana-union')->middleware('PermissionCheck:send_to_paperfly');
            Route::get('get-district', [ParcelController::class, 'getDistrict'])->name('admin.get-district')->middleware('PermissionCheck:send_to_paperfly');
            Route::post('create-paperfly-parcel', [ParcelController::class, 'createPaperflyParcel'])->name('admin.create-paperfly-parcel')->middleware('PermissionCheck:send_to_paperfly');
            Route::get('paperfly-parcel-track/{id}', [ParcelController::class, 'trackParcel'])->name('admin.paperfly-parcel-track')->middleware('PermissionCheck:parcel_create');

            //bulk return list route
            Route::get('bulk-return-list', [ParcelController::class, 'returnList'])->name('parcel.return.list')->middleware('PermissionCheck:return_read');
            Route::get('return-assign-list/{id}',  [ParcelController::class, 'assignList'])->name('merchant.return.list')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::post('return-assign-list', [ParcelController::class, 'searchMerchant'])->name('search.merchant.return');
            Route::Post('bulk-otp-generate', [ParcelController::class, 'bulkOtpCode'])->name('bulk-otp-generate');
            Route::post('bulk-otp-generate-check', [ParcelController::class, 'bulkOtpCodeCheck'])->name('bulk-otp-generate-check');
            Route::post("confirm-bulk-return", [ParcelController::class, 'confirmBulkReturn'])->name('confirm.bulk.return')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');
            Route::get('return-list-filter', [ParcelController::class, 'returnListFilter'])->name('parcel.return.list.filter')->middleware('PermissionCheck:parcel_return_assigned_to_merchant');

            //single return
            Route::get('return', [ParcelController::class, 'parcelList'])->name('parcel.return')->middleware('PermissionCheck:parcel_read');



            Route::post('bulk-sticker', [ParcelController::class, "bulkStickerPrint"])->name('admin.bulk.sticker-print')->middleware('PermissionCheck:parcel_read');
            Route::post('bulk-parcel-print', [ParcelController::class, "bulkParcelPrint"])->name('admin.bulk.parcel-print')->middleware('PermissionCheck:parcel_read');

            //return sticker
            Route::post('bulk-return-sticker-print', [ParcelController::class, "bulkReturnSticker"])->name('admin.bulk.return-sticker-print')->middleware('PermissionCheck:parcel_read');
            Route::post('bulk-parcel-return-print', [ParcelController::class, 'bulkReturnParcel'])->name('admin.bulk.parcel-return-print');
            Route::get('return-sticker/{id}', [ParcelController::class, 'ReturnSticker'])->name('admin.parcel.return-sticker');
            Route::get('return-print/{id}', [ParcelController::class, 'ReturnPrint'])->name('admin.parcel.return-print');
            Route::post('parcel-export', [ParcelController::class, 'exportParcel'])->name('admin.parcel-export');

            //parcel-delivary-modal route
            Route::post('parcel-returned-to-merchant-modal', [ParcelController::class, "marchantReturnModal"])->name('parcel-returned-to-merchant-modal')->middleware('PermissionCheck:parcel_returned_to_merchant');
            Route::post('parcel-delivered-modal', [ParcelController::class, "deliveryModal"])->name('parcel-delivered-modal')->middleware('PermissionCheck:parcel_delivered');

            //delivery otp code
            Route::post('delivery-otp-generate', [ParcelController::class, "deliveryOtpRequest"])->name('delivery-otp-generate');

        });
    });

	Route::middleware(['LoginCheckMerchant'])->group(function () {

        //merchant routes
        Route::prefix('merchant')->group(function() {
            route::get('dashboard', [MerchantDashboardController::class, 'index'])->name('merchant.dashboard');
            route::get('logout', [MerchantAuthController::class, 'logout'])->name('merchant.logout');

            //merchant profile routes
            Route::get('profile', [ProfileController::class, 'profile'])->name('merchant.profile');
            Route::get('profile/delete/{merchant_id}', [ProfileController::class, 'profileDeletion'])->name('merchant.profile.delete');
            Route::get('company', [ProfileController::class, 'company'])->name('merchant.company');
            Route::get('notifications', [ProfileController::class, 'notification'])->name('merchant.notifications');
            Route::get('account-activity', [ProfileController::class, 'accountActivity'])->name('merchant.account-activity');
            Route::get('security-settings', [ProfileController::class, 'securitySetting'])->name('merchant.security-settings');
            Route::post('change-password', [ProfileController::class, 'changePassword'])->name('merchant.change-password');
            Route::post('profile-update', [ProfileController::class, 'profileUpdate'])->name('merchant.update.profile');
            Route::post('merchant-update', [ProfileController::class, 'merchantUpdate'])->name('merchant.update.merchant');
            Route::post('merchant.name.request', [ProfileController::class, 'merchantNameRequest'])->name('merchant.name.request');


            Route::get('statements', [ProfileController::class, 'statements'])->name('merchant.statements');
            Route::get('shops', [ProfileController::class, 'shops'])->name('merchant.shops');
            Route::post('shop-add', [ProfileController::class, 'shopStore'])->name('merchant.add.shop');
            Route::get('shop-edit', [ProfileController::class, 'shopEdit'])->name('merchant.edit.shop');
            Route::post('shop-update', [ProfileController::class, 'shopUpdate'])->name('merchant.update.shop');
            Route::delete('shop/delete/{id}', [ProfileController::class, 'shopDelete']);
            Route::get('shop-name-request', [ProfileController::class, 'merchantShopName'])->name('shop-name-request');
            Route::post('merchant.update.shop-name', [ProfileController::class, 'merchantShopNameRequest'])->name('merchant.update.shop_name');
            Route::get('shop-change-history', [ProfileController::class, 'merchantshopHistory'])->name('shop-change-history');


            //shop default status change
            Route::post('shop-default-update', [ProfileController::class, 'changeDefault'])->name('merchant.default.shop');
            Route::get('api-credentials', [ProfileController::class, 'apiCredentials'])->name('merchant.api.credentials');
            Route::post('api-credentials-update', [ProfileController::class, 'apiCredentialsUpdate'])->name('merchant.api.credentials.update')->middleware('PermissionCheck:update_api_credentials');

            //for getting shop phone number and address
            Route::get('shop', [ProfileController::class, 'shop'])->name('merchant.shop');

            //charges routes
            Route::get('charge', [ProfileController::class, 'charge'])->name('merchant.charge');
            Route::get('cod-charge', [ProfileController::class, 'codCharge'])->name('merchant.cod.charge');

            //merchant parcel routes
            Route::get('parcels', [MerchantParcelController::class, 'index'])->name('merchant.parcel');
            Route::get('request-parcel', [MerchantParcelController::class, 'create'])->name('merchant.parcel.create');
            Route::post('parcel-store', [MerchantParcelController::class, 'store'])->name('merchant.parcel.store');
            Route::get('parcel-edit/{id}', [MerchantParcelController::class, 'edit'])->name('merchant.parcel.edit');
            Route::post('parcel-update', [MerchantParcelController::class, 'update'])->name('merchant.parcel.update');
            Route::any('parcel-filter', [MerchantParcelController::class, 'filter'])->name('merchant.parcel.filter');
            Route::get('parcel-detail/{id}', [MerchantParcelController::class, 'detail'])->name('merchant.parcel.detail');

            Route::get('parcel-status-update/{id}/{status}', [MerchantParcelController::class, 'parcelStatusUpdate']);

            Route::get('parcel-print/{id}', [MerchantParcelController::class, 'print'])->name('merchant.parcel.print');
            Route::get('parcel-duplicate/{id}', [MerchantParcelController::class, 'duplicate'])->name('merchant.parcel.duplicate');
            //cancel parcel with note
            Route::post('parcel-cancel', [MerchantParcelController::class, 'parcelCancel'])->name('merchant.parcel-cancel');
            Route::post('parcel-delete', [MerchantParcelController::class, 'parcelDelete'])->name('merchant.parcel-delete');
            Route::post('parcel-re-request', [MerchantParcelController::class, 'parcelReRequest'])->name('merchant.parcel.re-request');

            Route::get('parcel-filtering/{slug}', [MerchantParcelController::class, 'parcelFiltering'])->name('merchant.parcel.filtering');

            //merchant withdraw routes
            Route::get('withdraws', [MerchantWithdrawController::class, 'index'])->name('merchant.withdraw');
            Route::get('request-withdraw', [MerchantWithdrawController::class, 'create'])->name('merchant.withdraw.create');
            Route::post('request-withdraw', [MerchantWithdrawController::class, 'store'])->name('merchant.withdraw.store');
            Route::get('edit-withdraw-request/{id}', [MerchantWithdrawController::class, 'edit'])->name('merchant.withdraw.edit');
            Route::post('update-withdraw-request', [MerchantWithdrawController::class, 'update'])->name('merchant.withdraw.update');
            Route::delete('withdraw-request/delete/{id}', [MerchantWithdrawController::class, 'delete']);
            Route::get('payment-invoice/{id}', [MerchantWithdrawController::class, 'invoice'])->name('merchant.invoice');
            Route::get('payment-invoice-print/{id}', [MerchantWithdrawController::class, 'invoicePrint'])->name('merchant.invoice.print');

            Route::get('withdraw-status/{id}/{status}', [MerchantWithdrawController::class, 'chargeStatus']);

            //merchant payment accounts routes
            Route::get('payment/accounts', [MerchantWithdrawController::class, 'paymentAccounts'])->name('merchant.payment.accounts');
            Route::post('bank/update', [MerchantWithdrawController::class, 'paymentBankUpdate'])->name('merchant.bank.account.update');
            Route::get('payment/account/others', [MerchantWithdrawController::class, 'paymentOthersAccount'])->name('merchant.payment.accounts.others');
            Route::post('others/account/update', [MerchantWithdrawController::class, 'paymentOthersAccountUpdate'])->name('merchant.others.account.update');

            //staff routes
            Route::get('staffs', [MerchantStaffController::class, 'index'])->name('merchant.staffs');
            Route::get('create-staff', [MerchantStaffController::class, 'create'])->name('merchant.staff.create');
            Route::post('create-staff', [MerchantStaffController::class, 'store'])->name('merchant.staff.store');
            Route::get('edit-staff/{id}', [MerchantStaffController::class, 'edit'])->name('merchant.staff.edit');
            Route::post('update-staff', [MerchantStaffController::class, 'update'])->name('merchant.staff.update');
            Route::POST('user-status', [MerchantStaffController::class, 'statusChange']);

            Route::get('staff-personal-info/{id}', [MerchantStaffController::class, 'personalInfo'])->name('merchant.staff.personal.info');
            Route::get('staff-account-activity/{id}', [MerchantStaffController::class, 'accountActivity'])->name('merchant.staffs.account-activity');
            Route::get('logout-staff-all-devices/{id}', [UserController::class, 'logoutUserDevices']);

            Route::get('download-sample', [ImportExportController::class, 'export'])->name('merchant.export');
            Route::get('import', [ImportExportController::class, 'importExportView'])->name('merchant.import.csv');
            Route::post('import', [ImportExportController::class, 'import'])->name('merchant.import');
	    Route::get('download', [MerchantParcelController::class, 'download'])->name('merchant.closing.report');
        });

    });

});

Route::get('old-migration', [DashboardController::class, 'oldMigration']);
    Route::get('track/{id}',[MerchantParcelController::class, 'track']);
// common for admin/merchant panel
    Route::get('charge-details', [ParcelController::class, 'chargeDetails']);
    Route::get('get-customer-info', [ParcelController::class, 'customerDetails']);

    Route::get('old-balance', [DashboardController::class, 'oldBalance']);

    Route::get('merge-update', [DashboardController::class, 'mergeUpdate']);

    Route::get('test-drive', function() {
        Storage::disk('google')->put('test.txt', 'Hello World');
    });
});



