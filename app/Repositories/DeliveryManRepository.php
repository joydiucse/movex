<?php

namespace App\Repositories;
use App\Models\Account\CompanyAccount;
use App\Models\Account\DeliveryManAccount;
use App\Models\DeliveryMan;
use App\Models\User;
use App\Repositories\Interfaces\DeliveryManInterface;
use DB;
use Image;
use App\Models\Image as ImageModel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Sentinel;
use App\Traits\CommonHelperTrait;
use App\Traits\EditLogTrait;
use App\Models\DeliveryManEditLog;

class DeliveryManRepository implements DeliveryManInterface {

    use CommonHelperTrait;
    use EditLogTrait;

    public function all()
    {
        return DeliveryMan::all();
    }

    public function activeAll()
    {
        return DeliveryMan::whereHas('user', function ($query) {
                                $query->where('status', 1);
                            })
                            ->when(!hasPermission('read_all_delivery_man'), function ($query){
                                $query->whereHas('user', function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                      ->orWhere('hub_id', null);
                                });
                            })->get();
    }

    public function paginate($limit)
    {
        return DeliveryMan::with('user.image')
            ->when(!hasPermission('read_all_delivery_man'), function ($query){
                $query->whereHas('user', function ($q){
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('hub_id', null);
                });
            })->orderBy('id', 'desc')->paginate($limit);
    }

    public function get($id)
    {
        return DeliveryMan::find($id);
    }

    public function save($role, $data)
    {

    }

    public function store($request)
    {
        if($request->vertual == 1 && $request->shuttel == 1){
              return false;
        }

        DB::beginTransaction();
        try{
            $originalImageUrl = '';
            $imageSmallOneUrl = '';
            $imageSmallTwoUrl = '';
            $imageSmallThreeUrl = '';

            if (!blank($request->file('image'))) {

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage      = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne      = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo      = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree    = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                if(!is_dir($directory)) {
                    mkdir($directory);
                }

                $originalImageUrl       = $directory . $originalImage;
                $imageSmallOneUrl       = $directory . $imageSmallOne;
                $imageSmallTwoUrl       = $directory . $imageSmallTwo;
                $imageSmallThreeUrl     = $directory . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(40, 40)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image                          = new ImageModel();
                $image->original_image          = $originalImageUrl;
                $image->image_small_one         = $imageSmallOneUrl;
                $image->image_small_two         = $imageSmallTwoUrl;
                $image->image_small_three       = $imageSmallThreeUrl;
                $image->save();
            }

            $user = new User();
            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->phone_number  = $request->phone_number;
            $user->hub_id        = $request->hub ? $request->hub : 1;
            $user->password      = bcrypt($request->password);
            $user->permissions   = \Config::get('greenx.delivery_man_permissions');
            $user->image_id      = $image->id ?? null;
            $user->user_type      = 'delivery';
            $user->save();

            $deliveryman = new DeliveryMan();
            $deliveryman->user_id          = $user->id;
            $deliveryman->phone_number     = $request->phone_number;
            $deliveryman->city             = $request->city;
            $deliveryman->zip              = $request->zip;
//            $deliveryman->state            = $request->state;
            $deliveryman->address            = $request->address;
            $deliveryman->delivery_fee     = $request->delivery_fee;
            $deliveryman->pick_up_fee      = $request->pick_up_fee;
            $deliveryman->return_fee       = $request->return_fee;
            $deliveryman->driving_license    = $request->file('driving_license') ? $this->imageUpload($request->file('driving_license'), 'driving-license') : '' ;
            $deliveryman->is_vertual       = $request->vertual;
            $deliveryman->is_shuttle       = $request->shuttel;
            $deliveryman->sip_extension    = $request->sip_extension;
            $deliveryman->sip_password     = bcrypt($request->sip_password);
            $deliveryman->save();

            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);
            //$superAdminRole->users()->attach($superAdmin);
            // new account

            $company_account                        = new CompanyAccount();
            $company_account->details               = 'delivery_man_opening_balance';
            $company_account->source                = 'opening_balance';
            $company_account->date                  = date('Y-m-d');
            $company_account->type                  = 'income';
            $company_account->amount                = $request->opening_balance;
            $company_account->created_by            = Sentinel::getUser()->id;
            $company_account->delivery_man_id       = $deliveryman->id;
            $company_account->save();

            $deliveryman_account                    = new DeliveryManAccount();
            $deliveryman_account->details           = 'delivery_man_opening_balance';
            $deliveryman_account->source            = 'opening_balance';
            $deliveryman_account->date              = date('Y-m-d');
            $deliveryman_account->type              = 'income';
            $deliveryman_account->amount            = $request->opening_balance;
            $deliveryman_account->delivery_man_id   = $deliveryman->id;
            $deliveryman_account->company_account_id = $company_account->id;
            $deliveryman_account->save();
            // new account

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function update($request)
    {
        $delivery_man_old_data =[];
        $delivery_man_new_data =[];
        DB::beginTransaction();
        try{

            $delivery_man_old_data['user'] = User::find($request->user_id);
            $user = User::find($request->user_id);

            if (!blank($request->file('image'))) {

                $image           = ImageModel::find($request->image_id);

                if(!blank($image)):
                    if($image->original_image != "" && file_exists($image->original_image)):
                        unlink($image->original_image);
                    endif;
                    if($image->image_small_one != "" && file_exists($image->image_small_one)):
                        unlink($image->image_small_one);
                    endif;
                    if($image->image_small_two != "" && file_exists($image->image_small_two)):
                        unlink($image->image_small_two);
                    endif;
                    if($image->image_small_three != "" && file_exists($image->image_small_three)):
                        unlink($image->image_small_three);
                    endif;
                else:
                    $image     = new ImageModel();
                endif;

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage          = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne          = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo          = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree        = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                if(!is_dir($directory)) {
                    mkdir($directory);
                }

                $originalImageUrl       = $directory . $originalImage;
                $imageSmallOneUrl       = $directory . $imageSmallOne;
                $imageSmallTwoUrl       = $directory . $imageSmallTwo;
                $imageSmallThreeUrl     = $directory . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image->original_image          = $originalImageUrl;
                $image->image_small_one         = $imageSmallOneUrl;
                $image->image_small_two         = $imageSmallTwoUrl;
                $image->image_small_three       = $imageSmallThreeUrl;
                $image->save();

                $user->image_id    = $image->id;

            }

            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->hub_id        = $request->hub ? $request->hub : 1;
            $user->phone_number  = $request->phone_number;
            if($request->password != ""):
                $user->password      = bcrypt($request->password);
            endif;
            $user->save();

            $delivery_man_old_data['deliveryMan'] = DeliveryMan::find($request->id);

            $deliveryman = DeliveryMan::find($request->id);
            $deliveryman->user_id          = $user->id;
            $deliveryman->phone_number     = $request->phone_number;
            $deliveryman->city             = $request->city;
            $deliveryman->zip              = $request->zip;
//            $deliveryman->state            = $request->state;
            $deliveryman->address            = $request->address;
            if(hasPermission('update_delivery_man_commission')):
                $deliveryman->delivery_fee     = $request->delivery_fee;
                $deliveryman->pick_up_fee      = $request->pick_up_fee;
                $deliveryman->return_fee       = $request->return_fee;
            endif;

            $deliveryman->driving_license    = $request->file('driving_license') ? $this->imageUpload($request->file('driving_license'), 'driving-license', $request->id) : '';
            if(hasPermission('sip_update')):
            $deliveryman->sip_extension    = $request->sip_extension;
            $deliveryman->sip_password     = $request->sip_password;
            $deliveryman->dial_enable      = $request->dial_permission;
            endif;
            $deliveryman->save();

            // new account

            $delivery_man_old_data['company_account']      = CompanyAccount::where('source', 'opening_balance')->where('delivery_man_id', $deliveryman->id)->first();

            $company_account                        = CompanyAccount::where('source', 'opening_balance')->where('delivery_man_id', $deliveryman->id)->first();
            $company_account->details               = 'delivery_man_opening_balance';
            $company_account->source                = 'opening_balance';
            $company_account->date                  = date('Y-m-d');
            $company_account->type                  = 'income';
            $company_account->amount                = $request->opening_balance;
            $company_account->created_by            = Sentinel::getUser()->id;
            $company_account->delivery_man_id       = $deliveryman->id;
            $company_account->save();

            $delivery_man_old_data['delivery_account']                    = DeliveryManAccount::where('source', 'opening_balance')->where('delivery_man_id', $deliveryman->id)->first();

            $deliveryman_account                    = DeliveryManAccount::where('source', 'opening_balance')->where('delivery_man_id', $deliveryman->id)->first();
            $deliveryman_account->details           = 'delivery_man_opening_balance';
            $deliveryman_account->source            = 'opening_balance';
            $deliveryman_account->date              = date('Y-m-d');
            $deliveryman_account->type              = 'income';
            $deliveryman_account->amount            = $request->opening_balance;
            $deliveryman_account->delivery_man_id   = $deliveryman->id;
            $deliveryman_account->company_account_id = $company_account->id;
            $deliveryman_account->save();
            // new account

            $delivery_man_new_data['user'] = $user;
            $delivery_man_new_data['deliveryMan'] = $deliveryman;
            $delivery_man_new_data['company_account'] = $company_account;
            $delivery_man_new_data['delivery_account'] = $deliveryman_account;

            $this->deliveryMan_edit_log($delivery_man_old_data, $delivery_man_new_data, $deliveryman->id);

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

            $delivery_man = DeliveryMan::find($id);
            if($delivery_man->driving_license != "" && file_exists($delivery_man->driving_license)):
                unlink($delivery_man->driving_license);
            endif;

            $user  = User::find($delivery_man->user_id);
            $image = ImageModel::find($user->image_id);
            if(!blank($image)):
                if($image->original_image != "" && file_exists($image->original_image)):
                    unlink($image->original_image);
                endif;
                if($image->image_small_one != "" && file_exists($image->image_small_one)):
                    unlink($image->image_small_one);
                endif;
                if($image->image_small_two != "" && file_exists($image->image_small_two)):
                    unlink($image->image_small_two);
                endif;
                if($image->image_small_three != "" && file_exists($image->image_small_three)):
                    unlink($image->image_small_three);
                endif;
                $image->delete();
            endif;

            CompanyAccount::where('source', 'opening_balance')->where('delivery_man_id', $delivery_man->id)->delete();
            DeliveryManAccount::where('source', 'opening_balance')->where('delivery_man_id', $delivery_man->id)->delete();
            $user->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function imageUpload($image, $type, $delivery_man_id = '')
    {
        if($delivery_man_id != ''):
            $delivery = DeliveryMan::find($delivery_man_id);
            if($delivery->driving_license != "" && file_exists($delivery->driving_license)):
                unlink($delivery->driving_license);
            endif;
        endif;

        $requestImage           = $image;
        $fileType               = $requestImage->getClientOriginalExtension();
        $originalImage          = date('YmdHis') .'-'. $type . rand(1, 50) . '.' . $fileType;
        $directory              = 'admin/'.$type.'/';

        if(!is_dir($directory)) {
            mkdir($directory);
        }
        $originalImageUrl       = $directory . $originalImage;
        Image::make($requestImage)->save($originalImageUrl, 80);
        return $originalImageUrl;
    }

    public function statusChange($request)
    {
        $user = User::find($request['id']);
        $user->status = $request['status'];
        $result = $user->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function vertualChange($request)
    {
        $delivery_man = DeliveryMan::find($request['id']);
        if($request['status'] == 1):
            $delivery_man->is_vertual = $request['status'];
            $delivery_man->is_shuttle = 0;
        endif;
        $delivery_man->is_vertual = $request['status'];
        $result = $delivery_man->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function shuttleChange($request)
    {
        $delivery_man = DeliveryMan::find($request['id']);
        if($request['status'] == 1):
            $delivery_man->is_shuttle = $request['status'];
            $delivery_man->is_vertual = 0;
        endif;
        $delivery_man->is_shuttle = $request['status'];
        $result = $delivery_man->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function filter($request)
    {
        $query = DeliveryMan::query();

        if(!hasPermission('read_all_delivery_man')){
            $query->whereHas('user', function ($q){
                $q->where('hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhere('hub_id', null);
            });
        }

        if ($request->hub != 'all'){
            $query->whereHas('user', function ($q) use ($request){
                $q->when($request->hub == 'pending', function ($search){
                   $search->where('hub_id', null);
                })->when($request->hub != 'pending', function ($search) use ($request){
                    $search->where('hub_id', $request->hub);
                });
            });
        }


        if($request->name != ""){
            $query->whereHas('user', function ($q) use ($request){
                $q->where('first_name', 'like', '%'.$request->name.'%')
                   ->orWhere('last_name', 'like', '%'.$request->name.'%');
            });
        }

        if($request->email != ""){
            $query->whereHas('user', function ($q) use ($request){
                $q->where('email', 'like', '%'.$request->email.'%');
            });
        }

        if($request->status != "any"){
            $query->whereHas('user', function ($q) use ($request){
                $q->where('status', $request->status);
            });
        }

        return $query->orderByDesc('id')->paginate(\Config::get('greenx.paginate'));

    }

    public function editLog($id)
    {
        return  DeliveryManEditLog::where('delivery_man_id', $id)->orderByDesc('id')->paginate(\Config::get('greenx.paginate'));
    }

}
