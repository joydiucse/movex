<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Http\Requests\Merchant\MerchantUpdateRequest;
use App\Http\Requests\ShopRequest;
use App\Models\LogActivity;
use App\Models\Merchant;
use App\Models\Shop;
use App\Models\User;
use App\Models\Account\MerchantAccount;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Http\Request;
use Sentinel;

class ProfileController extends Controller
{

    protected $users;
    protected $merchants;

    public function __construct(UserInterface $users, MerchantInterface $merchants)
    {
        $this->users           = $users;
        $this->merchants      = $merchants;

    }
        public function profileDeletion()
    {
        // Check if the user is authenticated
        if (\Sentinel::check() && \Sentinel::getUser()->id == 46) {
            $user = \Sentinel::getUser();
            // Delete the user's profile
            $user->delete();
            // Delete associated merchant, if exists
            $merchant = Merchant::where('user_id', \Sentinel::getUser()->id)->first();
            if ($merchant) {
                $merchant->delete();
            }
            // Log out the user
            \Sentinel::logout();
            // Redirect to the login page
            return redirect()->route('admin.login');
        } else {
            // User is not authenticated, handle accordingly (e.g., redirect to login page)
            return redirect()->route('admin.login');
        }
    }
    
    public function profile()
    {
        return view('merchant.profile.staff-profile');
    }
    public function company()
    {
        $merchant = Merchant::where('user_id', Sentinel::getUser()->id)->first();
        $val = 1;
        return view('merchant.profile.staff-company', compact('merchant', 'val'));
    }

    public function securitySetting()
    {
        $login_activities = LogActivity::where('user_id', Sentinel::getUser()->id)->orderBy('id', 'desc')->limit(20)->get();
        $data = [
            'login_activities' => $login_activities
        ];
        return view('merchant.profile.staff-security-settings', $data);
    }

    public function notification()
    {
        return view('merchant.profile.staff-notification');
    }

    public function accountActivity()
    {
        $login_activities = LogActivity::where('user_id', Sentinel::getUser()->id)->orderBy('id', 'desc')->limit(20)->get();
        return view('merchant.profile.staff-account-activity', compact('login_activities'));
    }

    public function changePassword(Request $request)
    {
        $hasher         = Sentinel::getHasher();

        $current_password    = $request->current_password;
        $password       = $request->new_password;

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

    public function profileUpdate(UserUpdateRequest $request){

        $request->id = Sentinel::getUser()->id;

        if($this->users->updateProfile($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function merchantUpdate(MerchantUpdateRequest $request){
        if($this->merchants->updateMerchantByMerchant($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function statements()
    {
        $statements = Sentinel::getUser()->merchant->accountStatements();

        $statements = $statements->where('source', '!=', 'paid_parcels_delivery_reverse')->paginate(\Config::get('greenx.paginate'));

        return view('merchant.profile.statements', compact('statements'));

    }

    public function formatLogs($logs){
        $balance = 0;
        foreach ($logs as $log):
            $log['id'] = $log->id;
            $log['source'] = $log->source;
            $log['details'] = $log->details;
            $log['date'] = date('d-M-Y',strtotime($log->date));
            $amount = $log->type == 'income' ? $log->amount : - $log->amount ;
            $balance += $amount;
            $log['amount'] = number_format($amount, 2);
            $log['balance'] = number_format($balance ,2);
            $log['parcel_no'] = @$log->parcel->parcel_no;

            unset($log->company_account_id);
            unset($log->delivery_man_id);
            unset($log->parcel_id);
            unset($log->parcel);
        endforeach;

        return $logs;
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

    public function changeDefault(Request $request){
        try{
            $shop = Shop::find($request['shop_id']);
            $old_default = Sentinel::getUser()->merchant->shops()->where('default',1)->first();
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

    public function apiCredentials()
    {
        if (@settingHelper('preferences')->where('title','read_merchant_api')->first()->merchant):
            return view('merchant.profile.api-credentials');
        else:
            return abort(403, 'Access Denied');
        endif;
    }

    public function apiCredentialsUpdate(Request $request)
    {
        if ($this->merchants->apiCredentialsUpdate($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function merchantNameRequest(Request $request)
    {

            if($this->merchants->merchantNameRequest($request)){
                return back()->with('success', __('request_successfully_send'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }

    }

    public function merchantShopName(Request $request)
    {

        $shop = Shop::find($request->shop_id);
        return view('merchant.profile.shop-name-change', compact('shop'))->render();
    }

    public function merchantShopNameRequest(Request $request)
    {

            if($this->merchants->merchantShopNameRequest($request)){
                return back()->with('success', __('request_successfully_send'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }

    }

    public function merchantshopHistory(Request $request)
    {
        $shop = Shop::find($request->shop_id);
        return view('merchant.profile.shop-name-change-history', compact('shop'))->render();
    }
}
