<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\Account;
use App\Models\StaffAccount;
class FundTransfer extends Model
{
    use HasFactory;

    public function fromAccount(){
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function toAccount(){
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }

    public function StaffTransferAmount()
    {
        return $this->hasOne(StaffAccount::class, 'fund_transfer_id', 'id');
    }

    public function Account()
    {
        return $this->belongsTo(Account::class,  'from_account_id');
    }
}
