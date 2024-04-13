<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_no',
        'merchant_id',
        'return_man_id',
        'user_id'
    ];


    public function parcels(){
        return $this->hasMany(Parcel::class, 'parcel_no', 'parcel_no');
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function returnParcel(){
        return$this->hasOne(Parcel::class, 'parcel_no', 'merchant_id', 'return_man_id', 'user_id');;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function returnmerchant(){
        return $this->belongsTo(Merchant::class, "merchant_id")->select(['id', 'company', 'phone_number', 'city', 'address']);
    }
    

}
