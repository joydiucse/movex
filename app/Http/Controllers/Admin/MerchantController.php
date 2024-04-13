<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Merchant\MerchantStoreRequest;
use App\Http\Requests\Admin\Merchant\MerchantUpdateRequest;
use App\Http\Requests\Merchant\OthersAccountRequest;
use App\Http\Requests\ShopRequest;
use App\Models\Hub;
use App\Models\Shop;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\CodCharge;
use App\Models\Charge;
use App\Models\Merchant;
use Sentinel;
use App\Models\NameChangeRequest;
use App\Models\User;

class MerchantController extends Controller
{
    protected $merchants;
    protected $users;

    public function __construct(MerchantInterface $merchants, UserInterface $users)
    {
        $this->merchants    = $merchants;
        $this->users         = $users;
    }

    public function index()
    {
        $merchants = $this->merchants->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        $hubs      = Hub::all();

        return view('admin.merchants.index', compact('merchants','hubs'));
    }

    public function create()
    {
        $cod_charges    = CodCharge::all();
        $charges        = Charge::all();
        $hubs           = Hub::all();
        return view('admin.merchants.create', compact('charges', 'cod_charges','hubs'));
    }
    public function store(MerchantStoreRequest $request)
    {
        if($this->merchants->store($request)):
            return redirect()->route('merchant')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function edit($id)
    {
        $merchant   = $this->merchants->get($id);
        $staff = User::where('user_type', "staff")->get();
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $user       = $merchant->user;
            $hubs       = Hub::all();
            return view('admin.merchants.edit', compact('merchant','user','hubs', 'staff'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function update(MerchantUpdateRequest $request)
    {
        if($this->merchants->update($request)):
            return redirect()->route('merchant')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function delete($id)
    {
        $merchant       = $this->merchants->get($id);

        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $user_id        = $merchant->user_id;

            if($this->merchants->delete($user_id, $merchant)):
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

        else:
            $success[0] = __('access_denied');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }

    public function filter(Request $request)
    {
        $merchants = $this->merchants->filter($request);
        $hubs      = Hub::all();
        return view('admin.merchants.index', compact('merchants','hubs'));
    }

    public function statusChange(Request $request)
    {
        if($this->merchants->statusChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }

    public function personalInfo($id)
    {
        $merchant = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            return view('admin.merchants.details.personal-info', compact('merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;

    }

    public function permissions($id)
    {
        $merchant = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            return view('admin.merchants.details.permissions', compact('merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;

    }

    public function accountActivity($id)
    {
        $merchant         = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $login_activities = LogActivity::where('user_id', $merchant->user_id)->orderBy('id', 'desc')->limit(20)->get();
            return view('admin.merchants.details.account-activity', compact('login_activities', 'merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function charge($id)
    {
        $merchant         = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            return view('admin.merchants.details.charge', compact('merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function codCharge($id)
    {
        $merchant         = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            return view('admin.merchants.details.cod-charge', compact('merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function company($id)
    {
        $merchant         = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $val = 1;
            return view('admin.merchants.details.merchant-company', compact('merchant', 'val'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function statements($id)
    {
        $merchant   = $this->merchants->get($id);

        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $statements = $merchant->accountStatements()->paginate(\Config::get('greenx.paginate'));
            return view('admin.merchants.details.statements', compact('statements','merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }
    public function shops($id)
    {
        $merchant   = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $shops      = $merchant->shops()->paginate(\Config::get('greenx.paginate'));
            return view('admin.merchants.details.shops',compact('shops','merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function shopDelete($id)
    {
        try{
            $shop = Shop::find($id);
            $shop->delete();

            $success = __('deleted_successfully');
            return response()->json($success);
        } catch (\Exception $e){
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        }
    }

    public function shopStore(ShopRequest $request)
    {
        if($this->merchants->shopStore($request)):
            return back()->with('success', __('added_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function paymentAccounts($id){
        $merchant   = $this->merchants->get($id);

        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $payment_account = $merchant->paymentAccount;
            return view('admin.merchants.details.bank_account', compact('payment_account','merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function paymentAccountEdit($id){
        $merchant   = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            if($payment_account = $this->merchants->paymentAccount($id)):
                return view('admin.merchants.details.bank_account_edit', compact('payment_account','merchant'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function paymentAccountUpdate(Request $request){
        if($this->merchants->updateBankDetails($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function paymentAccountOthersEdit($id){
        $merchant   = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            if($payment_account = $this->merchants->paymentAccount($id)):
                return view('admin.merchants.details.others_account_edit', compact('payment_account','merchant'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function paymentOthersAccount($id){
        $merchant   = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            $payment_account = $merchant->paymentAccount;
            return view('admin.merchants.details.accounts', compact('payment_account','merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function paymentAccountOthersUpdate(OthersAccountRequest $request){
        if($this->merchants->updateOthersAccountDetails($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function changeDefault(Request $request){
        try{
            $shop = Shop::find($request['shop_id']);
            $merchant = $this->merchants->get($request['merchant_id']);
            $old_default = $merchant->shops()->where('default',1)->first();
            if(!blank($old_default)):
                $old_default->default = 0;
                $old_default->save();
            endif;
            $shop->default = 1;
            $shop->save();
            $success = __('updated_successfully');
            return response()->json($success);

        }catch (\Exception $e){
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        }
    }

    public function shopEdit(Request $request){

        $shop = Shop::find($request->shop_id);

        return view('merchant.profile.shop-update', compact('shop'))->render();
    }

    public function shopUpdate(ShopRequest $request){
        if($this->merchants->shopUpdate($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function apiCredentials($id)
    {
        $merchant   = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            return view('admin.merchants.details.api-credentials',compact('merchant'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function apiCredentialsUpdate(Request $request)
    {
        $merchant   = $this->merchants->get($request->id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            if ($this->merchants->apiCredentialsUpdate($request)):
                return back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function permissionUpdate(Request $request, $id)
    {
        $merchant = $this->merchants->get($id);
        if(hasPermission('read_all_merchant') || $merchant->user->hub_id == Sentinel::getUser()->hub_id || $merchant->user->hub_id == ''):
            if ($this->merchants->permissionUpdate($request, $merchant)):
                return back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }


    public function chengeRequest(Request $request)
    {
        if(hasPermission('merchant_read')):
        $changes =  $this->merchants->chengeRequest();
        $val =1 ;
        return view('admin.merchants.details.name-changes', compact('changes', 'val'));
        else:
        return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function chengeRequestAuthorize($id)
    {
        $name_change = NameChangeRequest::find($id); 
        if(hasPermission('name_change_accept')){
            if($this->merchants->chengeRequestAuthorize($id)){
                return back()->with('success', __('updated_successfully'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
        }else{
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function chengeRequestDelete($id)
    {
        $name_change = NameChangeRequest::find($id); 
        if(hasPermission('name_change_accept')){
            if($this->merchants->chengeRequestDelete($id)){
                return back()->with('success', __('updated_successfully'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
        }else{
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function nameChangeFilter(Request $request)
    {


        if(hasPermission('name_change_read')):
            $changes = $this->merchants->nameChangeFilter($request);
            $val =1 ;
            return view('admin.merchants.details.name-changes', compact('changes', 'val'));
            else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function merchantLog($id)
    {
         
        if(hasPermission('read_all_merchant'))
        {
             $merchant_logs = $this->merchants->merchantLog($id);
             $merchant = Merchant::find($id);
             $val = 1;

             return view('admin.merchants.log-hisotry', compact('merchant_logs', 'val', 'merchant'));
        }

    }


}
