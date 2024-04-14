<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PathaoServiceController extends Controller
{
    public function parcelDetails(Request $request)
    {
        if($request->has('id')){
            $percelId=$request->id;
            if($percelId!=''){
                $percel = Parcel::whereIn('id', explode(',', $percelId))->get();
                if($percel){

                    $html=View::make('admin.parcel.parcel-details.parcel-short-details', compact('percel'))->render();

                    /*$percelData=[];
                    foreach ($percel as $item){
                        $percelData[]=[
                            'id'=>$item->id,
                            'parcel_no'=>$item->parcel_no ?? '',
                            'merchant'=>$item->merchant->company ?? '',
                            'customer_name'=>$item->customer_name ?? '',
                            'customer_phone_number'=>$item->customer_phone_number ?? '',
                        ];
                    }*/
                    $response=[
                        'status'=>1,
                        'count'=>$percel->count(),
                        'html'=>$html
                    ];
                    return response()->json($response);
                }
            }else{
                return response()->json(['status'=>0, 'msg'=>"Please Provide Percel Id"]);
            }
        }
    }
}
