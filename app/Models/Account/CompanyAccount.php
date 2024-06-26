<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryMan;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\User;
use App\Models\Account\MerchantWithdraw;
use App\Models\Account\Account;

class CompanyAccount extends Model
{
    use HasFactory;

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }


    public function withdraw()
    {
        return $this->belongsTo(MerchantWithdraw::class, 'merchant_withdraw_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function cashCollected()
    {
        return $this->belongsTo(User::class, 'cash_received_by', 'id');
    }

    public function merchantAccount()
    {
        return $this->hasOne(MerchantAccount::class, 'company_account_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
