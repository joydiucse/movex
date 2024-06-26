<?php

namespace App\Repositories\Interfaces\Merchant;

interface MerchantInterface{
    public function all();

    public function activeAll();

    public function paginate($limit);

    public function get($id);

    public function store($data);

    public function update($data);

    public function delete($id, $merchant);

    public function saveMerchant($data, $userId);

    public function updateMerchant($data);

    public function fileUpload($image, $type);

    public function removeOldFile($image);

    public function filter($data);

    public function statusChange($data);

    public function tempStore($data);

    public function otpConfirm($request);

    public function resendOtp($id);

    public function registerMerchant($data, $user_id);

    public function updateMerchantByMerchant($request);

    public function saveMerchantPaymentAccount($merchant_id);

    public function shopStore($request);

    public function shopUpdate($request);

    public function saveMerchantShop($merchant_id, $request);

    public function paymentAccount($id);

    public function updateBankDetails($data);

    public function updateOthersAccountDetails($data);

    public function apiCredentialsUpdate($request);

    public function permissionUpdate($request, $merchant);

    public function chengeRequest();
    public function chengeRequestAuthorize($id);
    public function chengeRequestDelete($id);
    public function merchantNameRequest($request);
    public function merchantShopNameRequest($request);
    public function nameChangeFilter($request);
    public function merchantLog($request);

}
