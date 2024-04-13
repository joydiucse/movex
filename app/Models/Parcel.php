<?php

namespace App\Models;

use App\Models\Account\MerchantAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_no',
        'packaging',
        'packaging_charge',
        'fragile',
        'fragile_charge',
        'parcel_type',
        'weight',
        'charge',
        'cod_charge',
        'vat',
        'location',
        'total_delivery_charge',
        'payable',
        'return_charge',
        'price',
        'merchant_id',
        'pickup_hub_id',
        'user_id',
        'customer_name',
        'customer_invoice_no',
        'customer_phone_number',
        'customer_address',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
        'date',
        'pickup_shop_phone_number',
        'pickup_address',
        'note',
        'selling_price',
        'shop_id'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function account(){
        return $this->hasOne(MerchantAccount::class);
    }

    public function events(){
        return $this->hasMany(ParcelEvent::class)->orderByDesc('id');
    }

    public function event(){
        return $this->hasOne(ParcelEvent::class)->where('title', 'parcel_delivered_event')->latest();
    }

    public function cancelnote(){
        return $this->hasOne(ParcelEvent::class)->where('title', 'parcel_cancel_event')->latest();
    }

    public function pickupPerson(){
        return $this->hasOne(ParcelEvent::class)->where('title', ['assign_pickup_man_event', 'parcel_re_schedule_pickup_event'])->latest();
    }

    public function deliveryPerson(){
        return $this->hasOne(ParcelEvent::class)->where('title', ['assign_delivery_man_event', 'parcel_re_schedule_delivery_event'])->latest();
    }

    public function deliveryMan(){
        return $this->hasOne(DeliveryMan::class, 'id', 'delivery_man_id');
    }

    public function pickupMan(){
        return $this->hasOne(DeliveryMan::class, 'id', 'pickup_man_id');
    }

    public function returnDeliveryMan(){
        return $this->hasOne(DeliveryMan::class, 'id', 'return_delivery_man_id');
    }

    public function transferDeliveryMan(){
        return $this->hasOne(DeliveryMan::class, 'id', 'transfer_delivery_man_id');
    }

    public function returnEvent(){
        return $this->hasOne(ParcelEvent::class)->where('title', 'parcel_returned_to_merchant_event')->latest();
    }

    public function getEvent($title){
        return $this->hasOne(ParcelEvent::class)->where('title', $title)->latest();
    }

    public function hub(){
        return $this->belongsTo(Hub::class);
    }

    public function pickupHub(){
        return $this->belongsTo(Hub::class, 'pickup_hub_id', 'id');
    }

    public function transferToHub(){
        return $this->belongsTo(Hub::class, 'transfer_to_hub_id', 'id');
    }

    public function thirdParty(){
        return $this->belongsTo(ThirdParty::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function editEvent(){
        return $this->hasOne(ParcelEvent::class)->where('title', 'parcel_update_event')->latest();
    }

    public function returnParcels(){
        return $this->belongsTo(ParcelReturn::class, "parcel_no", "parcel_no");
    }

}
