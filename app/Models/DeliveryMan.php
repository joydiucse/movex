<?php

namespace App\Models;

use App\Models\Account\DeliveryManAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\CompanyAccount;

class DeliveryMan extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountStatements(){
        return $this->hasMany(DeliveryManAccount::class)->latest();
    }

    public function paymentLogs(){
        return $this->hasMany(DeliveryManAccount::class);
    }

    public function companyAccount(){
        return $this->belongsTo(CompanyAccount::class, 'id', 'delivery_man_id')->where('source', 'opening_balance');
    }
    public function balance($id)
    {
        $total_income  = DeliveryManAccount::where('delivery_man_id', $id)->where('type','income')->sum('amount');
        $total_expense  = DeliveryManAccount::where('delivery_man_id', $id)->where('type','expense')->sum('amount');
        $balance = $total_income - $total_expense;

        return $balance;
    }
}
