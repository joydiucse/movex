<?php

namespace App\Repositories;

use App\Models\Notice;
use App\Repositories\Interfaces\NoticeInterface;
use DB;

class NoticeRepository implements NoticeInterface {

    public function get($id)
    {
        return Notice::find($id);
    }

    public function paginate($limit)
    {
        return Notice::orderBy('id', 'desc')->paginate($limit);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try{
            $notice                 = new Notice();
            $notice->title          = $request->title;
            $notice->alert_class    = $request->alert_class;
            $notice->details        = $request->details;
            $notice->start_time     = date('Y-m-d H:i:s', strtotime($request->start_date.' '.$request->start_time));
            $notice->end_time       = date('Y-m-d H:i:s', strtotime($request->end_date.' '.$request->end_time));
            $notice->save();
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
        try{
            $notice                 = $this->get($request->id);
            $notice->title          = $request->title;
            $notice->alert_class    = $request->alert_class;
            $notice->details        = $request->details;
            $notice->start_time     = date('Y-m-d H:i:s', strtotime($request->start_date.' '.$request->start_time));
            $notice->end_time       = date('Y-m-d H:i:s', strtotime($request->end_date.' '.$request->end_time));
            $notice->save();
            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function statusChange($request)
    {
        DB::beginTransaction();
        try{
            $notice = $this->get($request['data']['id']);
            $notice[$request['data']['change_for']] = $request['data']['status'];
            $notice->save();
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
            Notice::find($id)->delete();
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

}
