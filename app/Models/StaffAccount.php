<?php

namespace App\Models;

use App\Models\Account\CompanyAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\Account;
use App\Models\Account\FundTransfer;

class StaffAccount extends Model
{
    use HasFactory;

    public function fromAccount(){
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function toAccount(){
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }


    public function fundTransfer(){
        return $this->belongsTo(FundTransfer::class);
    }

    public function companyAccount()
    {
        return $this->belongsTo(CompanyAccount::class);
    }
}
