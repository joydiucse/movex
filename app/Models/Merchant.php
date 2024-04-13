<?php

namespace App\Models;

use App\Models\Account\MerchantAccount;
use App\Models\Account\MerchantWithdraw;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\CompanyAccount;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'phone_number',
        'handling_fee',
        'parcel_rate',
    ];

    protected $casts = [
        'charges' => 'array',
        'cod_charges' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parcels(){
        return $this->hasMany(Parcel::class)->latest();
    }

    public function withdraws(){
        return $this->hasMany(MerchantWithdraw::class)->latest();
    }

    public function paymentAccount(){
        return $this->hasone(MerchantPaymentAccount::class);
    }

    public function accountStatements(){
        return $this->hasMany(MerchantAccount::class)->orderByDesc('id');
    }

    public function paymentLogs(){
        return $this->hasMany(MerchantAccount::class);
    }

    public function merchantAccount(){
        return $this->belongsTo(MerchantAccount::class, 'id', 'merchant_id')->where('source', 'opening_balance');
    }

    public function shops(){
        return $this->hasMany(Shop::class);
    }

    public function balance($id)
    {
        $parcels = Parcel::where('merchant_id', $id)
            ->where(function ($query) {
                $query->where('is_partially_delivered', '=', 1)
                    ->orWhereIn('status',['delivered','delivered-and-verified']);
            })
            ->where("withdraw_id", "=", null)
            ->where('is_paid',false)
            ->get();

        $payable   = $parcels->sum('payable');

        $merchant_accounts = MerchantAccount::where('merchant_id', $id)
            ->where(function ($query){
                $query->whereIn('source', ['previous_balance','cash_given_for_delivery_charge','parcel_return','paid_parcels_delivery_reverse','opening_balance'])
                ->orWhere(function ($query){
                    $query->where('source','vat_adjustment')
                        ->whereIn('details',['govt_vat_for_parcel_return','govt_vat_for_parcel_return_reversed']);
                });
            })
            ->where('payment_withdraw_id', null)->where('is_paid',false)->get();

        $income = $merchant_accounts->where('type', 'income')->sum('amount');
        $expense = $merchant_accounts->where('type', 'expense')->sum('amount');

        $balance = $payable + $income - $expense;

        return $balance;
    }

    public function staffs()
    {
        return $this->hasMany(User::class)->where('user_type','merchant_staff');
    }

    public function merchantnamechanges()
    {
        return $this->hasMany(NameChangeRequest::class, "merchant_id")->where('type', "company")->orderBy('id', 'desc');
    }

    public function nameChange()
    {
        return $this->hasOne(NameChangeRequest::class, "merchant_id")->where('type', "company")->latest();
    }

    public function keyAccountUser()
    {
        return $this->hasOne(User::class, 'id', 'key_account_id');
    }

    public function salesAgent()
    {
        return $this->hasOne(User::class, 'id', 'sales_agent_id');
    }


}
