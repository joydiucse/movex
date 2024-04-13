<?php

namespace App\Models\Account;

use App\Models\Merchant;
use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccount extends Model
{
    use HasFactory;

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function withdraw()
    {
        return $this->belongsTo(MerchantWithdraw::class,'merchant_withdraw_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }


}
