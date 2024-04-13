<?php

namespace App\Http\Controllers\MerchantStaff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Http\Requests\Merchant\MerchantUpdateRequest;
use App\Http\Requests\ShopRequest;
use App\Models\LogActivity;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Repositories\Interfaces\UserInterface;
use Sentinel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $users;
    protected $merchants;

    /**
     * ProfileController constructor.
     * @param $users
     */
    public function __construct(UserInterface $users, MerchantInterface $merchants)
    {
        $this->users        = $users;
        $this->merchants    = $merchants;
    }

    public function profile()
    {
        return view('merchant.profile.staff-profile');
    }

    public function company()
    {
        $merchant = Sentinel::getUser()->staffMerchant;
        $val = 1;
        return view('merchant.profile.staff-company', compact('merchant', 'val'));
    }

    public function profileUpdate(UserUpdateRequest $request){

        $request->id = Sentinel::getUser()->id;

        if($this->users->updateProfile($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function merchantUpdate(MerchantUpdateRequest $request)
    {
        if ($this->merchants->updateMerchantByMerchant($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function accountActivity()
    {
        $login_activities = LogActivity::where('user_id', Sentinel::getUser()->id)->orderBy('id', 'desc')->limit(20)->get();
        return view('merchant.profile.staff-account-activity', compact('login_activities'));
    }

    public function securitySetting()
    {
        $login_activities = LogActivity::where('user_id', Sentinel::getUser()->id)->orderBy('id', 'desc')->limit(20)->get();
        return view('merchant.profile.staff-security-settings', compact('login_activities'));
    }

    public function changePassword(Request $request)
    {
        $hasher         = Sentinel::getHasher();

        $current_password   = $request->current_password;
        $password           = $request->new_password;

        $user           = Sentinel::getUser();

        if (!$hasher->check($current_password, $user->password)) {
            return back()->with('danger', __('current_password_is_invalid'));
        }

        $user = User::find(Sentinel::getUser()->id);
        $user->password = bcrypt($password);
        $user->last_password_change = date('Y-m-d H:i:s');
        $user->save();

        return back()->with('success', __('updated_successfully'));
    }

    public function charge()
    {
        return view('merchant.profile.charge');
    }

    public function codCharge()
    {
        return view('merchant.profile.cod-charge');
    }

    public function shops()
    {
        return view('merchant.profile.shops');
    }

    public function changeDefault(Request $request){
        try{
            $shop = Shop::find($request['shop_id']);
            $old_default = Sentinel::getUser()->staffMerchant->shops()->where('default',1)->first();
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

    public function shopStore(ShopRequest $request)
    {
        if($this->merchants->shopStore($request)):
            return back()->with('success', __('added_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
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

    public function shop(Request $request){
        $shop = Shop::find($request->shop_id);

        $data['shop_phone_number'] = $shop->shop_phone_number;
        $data['address'] = $shop->address;
        return response()->json($data);
    }

    public function statements()
    {
        $statements = Sentinel::getUser()->staffMerchant->accountStatements()
        ->whereNotIn('source',['previous_balance','cash_given_for_delivery_charge','opening_balance']);

        if (!hasPermission('all_parcel_logs') && !hasPermission('all_payment_logs')){
            $statements->whereHas('parcel',function ($q){
                $q->where('user_id', Sentinel::getUser()->id);
            })->orWhereHas('withdraw', function ($q){
                $q->where('created_by', Sentinel::getUser()->id);
            });
        }elseif (!hasPermission('all_parcel_logs')){
            $statements->whereHas('parcel',function ($q){
                $q->where('user_id', Sentinel::getUser()->id);
            })->orWhereHas('withdraw');
        } elseif (!hasPermission('all_payment_logs')){
            $statements->whereHas('withdraw', function ($q){
                $q->where('created_by', Sentinel::getUser()->id);
            })->orWhereHas('parcel');
        }

        $statements = $statements->where('source', '!=', 'paid_parcels_delivery_reverse')->paginate(\Config::get('greenx.paginate'));

        return view('merchant.profile.statements', compact('statements'));

    }

    public function NameRequest(Request $request)
    {
            if(hasPermission("manage_company_information"))
            {
                if($this->merchants->merchantNameRequest($request)){
                    return back()->with('success', __('request_successfully_send'));
                }else{
                    return back()->with('danger', __('something_went_wrong_please_try_again'));
                }
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }

    }

    public function shopName(Request $request)
    {

        $shop = Shop::find($request->shop_id);
        return view('merchant.profile.shop-name-change', compact('shop'))->render();
    }

    public function shopNameRequest(Request $request)
    {
        if(hasPermission('manage_shops')):
            if($this->merchants->merchantShopNameRequest($request)){
                return back()->with('success', __('request_successfully_send'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function shopHistory(Request $request)
    {
        $shop = Shop::find($request->shop_id);
        return view('merchant.profile.shop-name-change-history', compact('shop'))->render();
    }
}
