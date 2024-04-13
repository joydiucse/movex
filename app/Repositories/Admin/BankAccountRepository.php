<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\BankAccountInterface;
use App\Models\Merchant;
use App\Models\Account\Account;
use App\Models\Account\CompanyAccount;
use App\Models\StaffAccount;
use App\Models\Account\FundTransfer;
use DB;
use App\Traits\CommonHelperTrait;
use App\Models\AccountLog;
class BankAccountRepository implements BankAccountInterface {

    use CommonHelperTrait;

    public function all()
    {
        return Account::all();
    }

    public function paginate()
    {
        return Account::orderByDesc('id')->paginate(\Config::get('greenx.paginate'));
    }

    public function get($id)
    {
        return Account::find($id);
    }
    public function store($request)
    {
        DB::beginTransaction();
        try{

            $account                             = new Account();
            $account->user_id                    = $request->user;
            $account->method                     = $request->method;
            $account->account_holder_name        = $request->account_holder_name;
            if($request->method == 'bank'):
                $account->account_no                 = $request->account_no;
                $account->bank_name                  = $request->bank;
                $account->bank_branch                = $request->branch;
            else:
                $account->number                     = $request->number;
                $account->type                       = $request->account_type;
            endif;
            $account->balance                       = $request->opening_balance;
            $account->save();


            // for new account system
            $company_account                        = new CompanyAccount();
            $company_account->details               = 'staff_account_opening_balance';
            $company_account->source                = 'opening_balance';
            $company_account->date                  = date('Y-m-d');
            $company_account->type                  = 'income';
            $company_account->amount                = $request->opening_balance;
            $company_account->created_by            = \Sentinel::getUser()->id;
            $company_account->user_id               = $request->user;
            $company_account->account_id            = $account->id;
            $company_account->save();

            $staff_account                     = new StaffAccount();
            $staff_account->details            = 'staff_account_opening_balance';
            $staff_account->source             = 'opening_balance';
            $staff_account->date               = date('Y-m-d');
            $staff_account->type               = 'income';
            $staff_account->amount             = $request->opening_balance;
            $staff_account->user_id            = $request->user;
            $staff_account->account_id         = $account->id;
            $staff_account->company_account_id = $company_account->id;
            $staff_account->save();
            // for new account system

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    public function update($request)
    {
        DB::beginTransaction();
        try{

            $account                             = Account::find($request->id);
            if($this->accountLog($account->id, "account_update", $account)):
                $account->user_id                    = $request->user;
                $account->method                     = $request->method;
                $account->account_holder_name        = $request->account_holder_name;
                if($request->method == 'bank'):

                    $account->account_no                 = $request->account_no;
                    $account->bank_name                  = $request->bank;
                    $account->bank_branch                = $request->branch;

                    $account->number                     = '';
                    $account->type                       = '';

                else:
                    $account->account_no                 = '';
                    $account->bank_name                  = '';
                    $account->bank_branch                = '';

                    $account->number                     = $request->number;
                    $account->type                       = $request->account_type;

                endif;
                $account->balance                       = $request->opening_balance;

                $account->save();
            endif;

             // for new account system
             $company_account                        = CompanyAccount::where('account_id', $account->id)->where('source', 'opening_balance')->first();
             if($this->accountLog($company_account->id, "company_account_update", $company_account)):
                $company_account->details               = 'staff_account_opening_balance';
                $company_account->source                = 'opening_balance';
                $company_account->date                  = date('Y-m-d');
                $company_account->type                  = 'income';
                $company_account->amount                = $request->opening_balance;
                $company_account->created_by            = \Sentinel::getUser()->id;
                $company_account->user_id               = $request->user;
                $company_account->save();
            endif;

             $staff_account                     = StaffAccount::where('account_id', $account->id)->where('source', 'opening_balance')->first();
             if($this->accountLog($staff_account->id, "staff_account_update", $staff_account)):
             $staff_account->details            = 'staff_account_opening_balance';
             $staff_account->source             = 'opening_balance';
             $staff_account->date               = date('Y-m-d');
             $staff_account->type               = 'income';
             $staff_account->amount             = $request->opening_balance;
             $staff_account->user_id            = $request->user;
             $staff_account->company_account_id = $company_account->id;
             $staff_account->save();
             // for new account system
            endif;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{

            $account = Account::find($id);
            $company_account = CompanyAccount::where('account_id', $account->id)->where('source', 'opening_balance')->first();
            if($this->accountLog($company_account->id, "company_account_delete", $company_account)):
                // for new account system
                CompanyAccount::where('account_id', $account->id)->where('source', 'opening_balance')->delete();
            endif;

            $staff_account = StaffAccount::where('account_id', $account->id)->where('source', 'opening_balance')->first();
            if($this->accountLog($staff_account->id, "staff_account_delete", $staff_account)):
                StaffAccount::where('account_id', $account->id)->where('source', 'opening_balance')->delete();
            // for new account system
            endif;

            $account->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function bankRemainingBalance($table_name, $account_id, $row_id, $purpose)
    {
        if($purpose == 'update'){

            if($table_name == 'company_accounts'){
                $old_account_id             = CompanyAccount::find($row_id)->account_id;
            }else{
                $old_account_id             = FundTransfer::find($row_id)->from_account_id;
            }
            //$old_account_id          = $expense->account_id;
            //removed cause already added to CompanyAccount as an income for that account
//            $balance                = Account::find($account_id)->balance;
            $total_income           = CompanyAccount::where('type', 'income')->where('account_id', $account_id)->sum('amount');
            $total_expense          = CompanyAccount::where('type', 'expense')->where('account_id', $account_id)->sum('amount');
            if($table_name == 'company_accounts' && $old_account_id == $account_id){
                $total_expense          = CompanyAccount::where('type', 'expense')->where('account_id', $account_id)->where('id', '!=', $row_id)->sum('amount');
            }

            $total_fund_received    = FundTransfer::where('to_account_id', $account_id)->sum('amount');
            $total_fund_transfered  = FundTransfer::where('from_account_id', $account_id)->sum('amount');
            if($table_name == 'fund_transfers'  && $old_account_id == $account_id){
                $total_fund_transfered  = FundTransfer::where('from_account_id', $account_id)->where('id', '!=', $row_id)->sum('amount');
            }
//            $balance +
            $balance =  $total_income + $total_fund_received - $total_expense - $total_fund_transfered;


        }elseif($purpose == 'edit'){

            if($table_name == 'company_accounts'){
                $account_id             = CompanyAccount::find($row_id)->account_id;
            }else{
                $account_id             = FundTransfer::find($row_id)->from_account_id;
            }

//            $balance                = Account::find($account_id)->balance;

            $total_income           = CompanyAccount::where('type', 'income')->where('account_id', $account_id)->sum('amount');
            $total_expense          = CompanyAccount::where('type', 'expense')->where('account_id', $account_id)->sum('amount');
            if($table_name == 'company_accounts'){
                $total_expense          = CompanyAccount::where('type', 'expense')->where('account_id', $account_id)->where('id', '!=', $row_id)->sum('amount');
            }

            $total_fund_received    = FundTransfer::where('to_account_id', $account_id)->sum('amount');
            $total_fund_transfered  = FundTransfer::where('from_account_id', $account_id)->sum('amount');
            if($table_name == 'fund_transfers'){
                $total_fund_transfered  = FundTransfer::where('from_account_id', $account_id)->where('id', '!=', $row_id)->sum('amount');
            }
//            $balance +
            $balance =  $total_income + $total_fund_received - abs($total_expense) - $total_fund_transfered;

        }else{

//            $balance                = Account::find($account_id)->balance;
            $total_income           = CompanyAccount::where('type', 'income')->where('account_id', $account_id)->sum('amount');
            $total_expense          = CompanyAccount::where('type', 'expense')->where('account_id', $account_id)->sum('amount');

            $total_fund_received    = FundTransfer::where('to_account_id', $account_id)->sum('amount');
            $total_fund_transfered  = FundTransfer::where('from_account_id', $account_id)->sum('amount');

            $balance =  $total_income + $total_fund_received - abs($total_expense) - $total_fund_transfered;

        }

        return $balance;

    }

    public function accountsByUser($id)
    {
        return Account::with('user')->where('user_id', $id)->get();
    }

    public function accountLog($id, $title, $data)
    {
        DB::beginTransaction();
        try{
            $log      = new AccountLog();
            $log->type = $title;
            $log->log_id = $id;
            $log->data_log = json_encode($data);
            $log->date = date('Y-m-d');
            $log->user_id = \Sentinel::getUser()->id;
            $log->save();
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
