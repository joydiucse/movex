<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkDelivery extends Model
{
    use HasFactory;

    public function deliveryMan(){
        return $this->belongsTo(DeliveryMan::class, "delivery_man_id");
    }

    public function returnAssignMan(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function precessMan(){
        return $this->belongsTo(User::class, "processed_by");
    }
}
