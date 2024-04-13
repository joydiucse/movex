<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_no',
        'merchant_id',
        'delivery_man_id'
    ];


    public function merchant(){
        return $this->belongsTo(Merchant::class, "merchant_id");
    }

    public function deliveryMan(){
        return $this->belongsTo(DeliveryMan::class, "delivery_man_id");
    }

    public function returnAssignMan(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function precessMan(){
        return $this->belongsTo(User::class, "processed_by");
    }

    public function returnmerchant(){
        return $this->belongsTo(Merchant::class, "merchant_id")->select(['id', 'company', 'phone_number', 'city', 'address']);
    }
    


}
