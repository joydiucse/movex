<?php

namespace App\Models;

use App\Models\Account\Account;
use App\Models\Account\MerchantWithdraw;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawBatch extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdraws()
    {
        return $this->hasMany(MerchantWithdraw::class);
    }
}
