<?php

namespace App\Repositories;
use App\Models\Hub;
use App\Models\User;
use App\Repositories\Interfaces\HubInterface;
use DB;

class HubRepository implements HubInterface {

    public function paginate()
    {
        return Hub::orderByDesc('id')->paginate(\Config::get('greenx.paginate'));
    }

    public function allUsers()
    {
        return User::get();
    }

    public function get($id)
    {
        return Hub::find($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $hub = new Hub();
            $hub->user_id       = $request->incharge;
            $hub->name          = $request->name;
            $hub->address       = $request->address;
            $hub->phone_number  = $request->phone_number;
            $hub->save();

            $user = $hub->user;
            $user->hub_id = $hub->id;
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function update($request)
    {

        DB::beginTransaction();
        try {
            $hub = $this->get($request->id);
            $hub->user_id       = $request->incharge;
            $hub->name          = $request->name;
            $hub->address       = $request->address;
            $hub->phone_number  = $request->phone_number;
            $hub->save();

            $user = $hub->user;
            $user->hub_id = $hub->id;
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{

            $hub = $this->get($id);

            $hub->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
