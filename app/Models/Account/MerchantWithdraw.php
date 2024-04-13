<?php

namespace App\Models\Account;

use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\User;
use App\Models\WithdrawBatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantWithdraw extends Model
{
    use HasFactory;

    protected $attribute   = ['account_details' => []];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function account(){
        return $this->hasOne(MerchantAccount::class);
    }

    public function companyAccount(){
        return $this->hasOne(CompanyAccount::class)->latest();
    }

    public function companyAccountFirst(){
        return $this->hasOne(CompanyAccount::class)->first();
    }

    public function companyAccountReason(){
        return $this->hasOne(CompanyAccount::class)->latest();
    }

    public function parcels(){
        return $this->hasMany(Parcel::class, 'withdraw_id', 'id');
    }

    public function merchantAccounts(){
        return $this->hasMany(MerchantAccount::class, 'payment_withdraw_id', 'id');
    }

    public function paidReverseParcels(){
        return $this->hasMany(MerchantAccount::class, 'parcel_withdraw_id', 'id');
    }

    public function withdrawBatch(){
        return $this->belongsTo(WithdrawBatch::class);
    }
}
