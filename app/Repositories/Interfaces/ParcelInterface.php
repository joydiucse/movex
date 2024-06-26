<?php

namespace App\Repositories\Interfaces;

interface ParcelInterface{
    public function all();

    public function get($id);

    public function store($data);

    public function update($data);

    public function parcelDelete($request);

    public function getMerchants();

    public function getDeliveryMan();

    public function imageUpload($image, $type, $delivery_man_id);

    public function statusChange($data);

    public function assignPickupMan($data);

    public function assignDeliveryMan($data);

    public function reSchedulePickupMan($data);

    public function reScheduleDeliveryMan($data);

    public function parcelCancel($data);

    public function deliveryReverse($data);

    public function returnAssignToMerchant($data);

    public function reSchedulePickup($data);

    public function reScheduleDelivery($data);

    public function parcelEvent($parcel_id, $title, $delivery_man = '', $pickup_man = '', $return_delivery_man = '', $cancel_note = '', $status = '', $hub = null, $transfer_delivery_man = null, $created_at = '');

    public function parcelStatusUpdate($id, $status, $note, $hub = null, $delivery_man = null);

    public function generate_random_string($length);

    public function reverseUpdate($id, $status, $note);

    //delivery reverse functions
    public function requestPending($id);

    public function requestPickupPending($request);

    public function requestPickupManReceivedPickupPending($request);

    public function uptoReceived($request);

    public function uptoDeliveryAssigned($request);
    //delivery reverse functions end

    //partial delivery
    public function partialDelivery($request);

    public function chargeDetails($request);
    //partial delviery ends

    public function customerDetails($request);

    public function getThanaUnion($request);

    public function getDistrict();

    public function createPaperflyParcel($request);

    public function trackParcel($id);

    public function parcelOtpGenerate($id);
    public function otpCodevarified($parcel_id, $otp_code);
    public function merchantReturnConfirm($id, $otp);

    public function returnList();
    public function returnListFilter($request);
    public function assignList($id);
    public function searchMerchant($merchant_name);
    public function bulkOtpCode($request);
    public function bulkOtpCodeCheck($request);
    public function confirmBulkReturn($request);
    public function parcelReturnReverse($request);
    public function parcelList($request);
    public function deliveryOtpGenerate($parcel_id, $title);

}
