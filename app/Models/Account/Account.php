<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Account\FundTransfer;

class Account extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    
    public function balance(){
        return $this->hasMany(CompanyAccount::class)->sum('amount');
    }

    public function incomes(){
        return $this->hasMany(CompanyAccount::class)->where('type', 'income');
    }

    public function expenses(){
        return $this->hasMany(CompanyAccount::class)->where('type', 'expense');
    }
    
    public function fundReceives(){
        return $this->hasMany(FundTransfer::class, 'to_account_id', 'id');
    }

    public function fundTransfers(){
        return $this->hasMany(FundTransfer::class, 'from_account_id', 'id');
    }

    public function funds(){
        return $this->hasMany(FundTransfer::class);
    }

    public function accounts(){
        return $this->hasMany(CompanyAccount::class);
    }
}
