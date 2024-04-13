<?php

namespace App\Repositories\Admin;

use App\Repositories\Interfaces\Admin\ExpenseInterface;
use App\Models\Account\MerchantWithdraw;
use App\Models\Account\CompanyAccount;
use App\Models\Account\MerchantAccount;
use App\Models\StaffAccount;
use App\Models\Account\Account;
use App\Models\Merchant;
use DB;
use Image;
use App\Traits\CommonHelperTrait;
use App\Models\AccountLog;
class ExpenseRepository implements ExpenseInterface {

    use CommonHelperTrait;

    public function all()
    {
        return CompanyAccount::where('type', 'expense')->where('create_type', 'user_defined')->orderBy('id', 'desc')->paginate(\Config::get('greenx.paginate'));
    }
    public function get($id)
    {
        return CompanyAccount::find($id);
    }
    public function store($request)
    {
        DB::beginTransaction();
        try{
            // for new account system
            $user_id = Account::find($request->account)->user_id;

            $income                   = new CompanyAccount();
            $income->amount           = $request->amount;
            $income->date             = date('Y-m-d', strtotime($request->date));
            $income->date_time        = date('Y-m-d h:i:s', strtotime($request->date.' '.$request->time));
            $income->type             = 'expense';
            $income->create_type      = 'user_defined';
            $income->details          = $request->details;
            $income->transaction_id   = $request->transaction_id;
            $income->receipt          = $request->file('receipt') ? $this->fileUpload($request->file('receipt')) : '';
            $income->account_id       = $request->account;
            $income->user_id          = $user_id;
            $income->save();

            $staff_account                     = new StaffAccount();
            $staff_account->details            = $request->details;
            $staff_account->date               = date('Y-m-d', strtotime($request->date));
            $staff_account->date_time          = date('Y-m-d h:i:s', strtotime($request->date.' '.$request->time));
            $staff_account->type               = 'expense';
            $staff_account->amount             = $request->amount;
            $staff_account->user_id            = $user_id;
            $staff_account->account_id         = $request->account;
            $staff_account->company_account_id = $income->id;
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
            // for new account system
            $user_id = Account::find($request->account)->user_id;

            $income                   = CompanyAccount::find($request->id);
           if($this->expenceLog($income->id, "company_account_update", $income)):
                $income->amount           = $request->amount;
                $income->date             = date('Y-m-d', strtotime($request->date));
                $income->date_time        = date('Y-m-d h:i:s', strtotime($request->date.' '.$request->time));
                $income->type             = 'expense';
                $income->details          = $request->details;
                $income->transaction_id   = $request->transaction_id;
                $income->account_id       = $request->account;
                $income->user_id          = $user_id;

                if ($request->file('receipt')):
                    $this->removeOldFile($income->receipt);
                    $income->receipt              = $this->fileUpload($request->file('receipt'));
                endif;

                $income->save();
            endif;

            $staff_account                     = StaffAccount::where('company_account_id',  $income->id)->first();
            if($this->expenceLog($staff_account->id, "staff_account_update", $staff_account)):
                $staff_account->details            = $request->details;
                $staff_account->date               = date('Y-m-d', strtotime($request->date));
                $staff_account->date_time          = date('Y-m-d h:i:s', strtotime($request->date.' '.$request->time));
                $staff_account->type               = 'expense';
                $staff_account->amount             = $request->amount;
                $staff_account->user_id            = $user_id;
                $staff_account->account_id         = $request->account;
                $staff_account->save();
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
        $company_account = CompanyAccount::find($id);
        if($this->expenceLog($company_account->id, "company_account_delete", $company_account)):
            DB::beginTransaction();
            try{

                CompanyAccount::destroy($id);

                DB::commit();
                return true;

            } catch (\Exception $e) {
                DB::rollback();
                return false;
            }
        endif;
    }

    public function fileUpload($image)
    {

        $requestImage           = $image;
        $fileType               = $requestImage->getClientOriginalExtension();
        $original               = date('YmdHis') .'-receipt' . rand(1, 50) . '.' . $fileType;
        $directory              = 'admin/images/';

        if(!is_dir($directory)) {
            mkdir($directory);
        }

        $originalFileUrl       = $directory . $original;

        if($fileType == 'pdf'):
            $requestImage->move($directory,$original);
        else:
            Image::make($requestImage)->save($originalFileUrl, 80);
        endif;

        return $originalFileUrl;
    }

    public function removeOldFile($image)
    {
        if($image != "" && file_exists($image)):
            unlink($image);
        endif;
    }

    public function expenceLog($id, $title, $data)
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
