<?php


namespace App\Repositories\Admin;

use App\Models\ThirdParty;
use App\Repositories\Interfaces\Admin\ThirdPartyInterface;
use DB;

class ThirdPartyRepository implements ThirdPartyInterface
{
    public function paginate()
    {
        return ThirdParty::orderByDesc('id')->paginate(\Config::get('greenx.paginate'));
    }

    public function get($id)
    {
        return ThirdParty::find($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $third_party = new ThirdParty();
            $third_party->name          = $request->name;
            $third_party->address       = $request->address;
            $third_party->phone_number  = $request->phone_number;
            $third_party->save();

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
            $third_party = $this->get($request->id);
            $third_party->name          = $request->name;
            $third_party->address       = $request->address;
            $third_party->phone_number  = $request->phone_number;
            $third_party->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function changeStatus($request)
    {
        DB::beginTransaction();
        try{

            $third_party = $this->get($request['id']);
            $third_party->status = $request['status'];
            $third_party->save();

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
        try {
            $third_party = $this->get($id);

            $third_party->delete();

            DB::commit();

            return true;
        }   catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }
}
