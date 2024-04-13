<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryMan;
use App\Models\Parcel;

class DeliveryManAccount extends Model
{
    use HasFactory;

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function companyAccount()
    {
        return $this->belongsTo(CompanyAccount::class);
    }

}
