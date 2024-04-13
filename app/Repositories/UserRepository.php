<?php

namespace App\Repositories;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use Image;
use App\Models\Image as ImageModel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use DB;
use Sentinel;

class UserRepository implements UserInterface {
    public function all()
    {
        return User::get();
    }

    public function paginate($limit)
    {
        return User::where('id', '!=', \Sentinel::getUser()->id)->where('id', '!=', '1')->where('user_type', 'staff')->paginate($limit);
    }

    public function get($id)
    {
        return User::find($id);
    }


    public function store($request)
    {

        DB::beginTransaction();
        try{

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
            $user->password      = bcrypt($request->password);
            $user->permissions   = isset($request->permissions) ? $request->permissions : [];
            $user->image_id      = $image->id ?? null;
            $user->hub_id        = $request->hub ? $request->hub : null;
            $user->save();

            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);
            //$superAdminRole->users()->attach($superAdmin);

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

            $user = User::find($request->id);

            if (!blank($request->file('image'))) {

                $image           = ImageModel::find($user->image_id);

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
            $user->hub_id        = $request->hub ? $request->hub : null;
            if($request->password != ""):
                $user->password      = bcrypt($request->password);
            endif;
            $user->permissions      = isset($request->permissions) ? $request->permissions : [];
            $user->save();

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

            $user  = User::find($id);
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
            else:
                $user->delete();
            endif;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
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

    public function updateProfile($request)
    {
        DB::beginTransaction();
        try{

            $user = User::find(Sentinel::getUser()->id);

            if (!blank($request->file('image'))) {

                $image           = ImageModel::find($user->image_id);

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
            if($user->user_type == 'merchant_staff'):
                $user->phone_number = $request->phone_number;
            endif;
            $user->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
