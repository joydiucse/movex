<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'user_id',
        'title'
    ];

    public function parcel(){
        return $this->belongsTo(Parcel::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pickupMan(){
        return $this->hasOne(DeliveryMan::class)->latest();
    }

    public function deliveryPerson(){
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id', 'id');
    }

    public function returnPerson(){
        return $this->belongsTo(DeliveryMan::class, 'return_delivery_man_id', 'id');
    }

    public function transferPerson(){
        return $this->belongsTo(DeliveryMan::class, 'transfer_delivery_man_id', 'id');
    }

    public function pickupPerson(){
        return $this->belongsTo(DeliveryMan::class, 'pickup_man_id', 'id');
    }

    public function hub(){
        return $this->belongsTo(Hub::class);
    }

    public function thirdParty(){
        return $this->belongsTo(ThirdParty::class);
    }
}
