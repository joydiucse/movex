<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BankingPayments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\WithdrawBatchRequest;
use App\Models\Account\Account;
use App\Models\Account\MerchantWithdraw;
use App\Models\WithdrawBatch;
use App\Repositories\Interfaces\Admin\BankAccountInterface;
use App\Repositories\Interfaces\Admin\BulkWithdrawInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;

class BulkWithdrawController extends Controller
{
    protected $withdraws;
    protected $bank_accounts;

    public function __construct(BulkWithdrawInterface $withdraws, BankAccountInterface $bank_accounts)
    {
        $this->withdraws = $withdraws;
        $this->bank_accounts = $bank_accounts;
    }

    public function index()
    {
        $accounts           = Account::all()->where('user_id', \Sentinel::getUser()->id);
        $withdraws          = $this->withdraws->paginate(\Config::get('greenx.paginate'));
        return view('admin.withdraws.bulk.index', compact('withdraws','accounts'));
    }

    public function create()
    {
        if (@settingHelper('preferences')->where('title','create_payment_request')->first()->staff):
            $accounts         = Account::all()->where('user_id', \Sentinel::getUser()->id);

            return view('admin.withdraws.bulk.create', compact('accounts'));
        else:
            return back()->with('danger', __('service_unavailable'));
        endif;
    }

    public function store(WithdrawBatchRequest $request)
    {
        if (@settingHelper('preferences')->where('title','create_payment_request')->first()->staff):
            if($this->withdraws->store($request)):
                return redirect()->route('admin.withdraws.bulk')->with('success', __('created_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return redirect()->route('admin.withdraws')->with('danger', __('service_unavailable'));
        endif;
    }

    public function edit($id)
    {
        try{
            $batch         = $this->withdraws->get($id);
            return view('admin.withdraws.bulk.edit', compact('batch'));
        } catch (\Exception $e) {
            return back()->with('danger', __('not_found'));
        }
    }

    public function update(WithdrawBatchRequest $request)
    {
        if($this->withdraws->update($request)):
            return redirect()->route('admin.withdraws.bulk')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function processPayment(Request $request)
    {
        $batch                  = $this->withdraws->get($request->id);

        $total_withdraw_amount  = $batch->withdraws->sum('amount');

        $balance  = $this->bank_accounts->bankRemainingBalance('', $request->account, '', '');

        if($balance < $total_withdraw_amount){
            return back()->with('danger', __('the_account_has_no_available_balance'));
        }

        if($this->withdraws->changeStatus($request->id, 'processed', $request)):
            return back()->with('success', __('processed_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function batches(Request $request)
    {
        $withdraw = MerchantWithdraw::find($request->withdraw_id);

        $batches = WithdrawBatch::where('status','pending')->where('batch_type', $withdraw->withdraw_to)->get();

        return view('admin.withdraws.batch-options', compact('batches','withdraw'))->render();
    }

    public function invoice($id)
    {
        $withdraw = $this->withdraws->get($id);

        return view('admin.withdraws.bulk.invoice', compact('withdraw'));
    }


    public function download($id)
    {
        $batch      = $this->withdraws->get($id);
        $batch_type = $batch->batch_type;

        $file_name  = $batch->batch_no.' '.'-'.date('Y-m-d').'('.$batch_type.')'.'.xlsx';
        return Excel::download(new BankingPayments($id, $batch->batch_no, $batch_type), $file_name);
    }



    public function delete($id)
    {
        if($this->withdraws->delete($id)):
            $success[0] = __('deleted_successfully');
            $success[1] = 'success';
            $success[2] = __('deleted');
            return response()->json($success);
        else:
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }
}
