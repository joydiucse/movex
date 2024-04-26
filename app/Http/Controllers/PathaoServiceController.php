<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\PathaoArea;
use App\Models\PathaoCity;
use App\Models\PathaoZone;
use App\Models\ThirdParty;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class PathaoServiceController extends Controller
{

    public function getCity(Request $request)
    {
        $cities=PathaoCity::all();
        $formattedCities=[];
        foreach ($cities as $city) {
            $formattedCities[]=[
                'id'=>  $city->city_id,
                'text'=>  $city->city_name,
            ];
        }
        return $formattedCities;
    }



    public function parcelDetails(Request $request)
    {
        if($request->ajax()) {
            if ($request->has('id')) {
                $percelId = $request->id;
                if ($percelId != '') {
                    $percel = Parcel::whereIn('id', explode(',', $percelId))->get();
                    if ($percel) {

                        $html = View::make('admin.parcel.parcel-details.parcel-short-details', compact('percel'))->render();

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
                        $response = [
                            'status' => 1,
                            'count' => $percel->count(),
                            'html' => $html
                        ];
                        return response()->json($response);
                    }
                } else {
                    return response()->json(['status' => 0, 'msg' => "Please Provide Percel Id"]);
                }
            }
        }
        abort(403);
    }
    public function pathaoBulkOrder(Request $request)
    {
        if($request->ajax()) {
            if ($request->has('id')) {
                $percelId = $request->id;
                if ($percelId != '') {
                    $percel = Parcel::whereIn('id', explode(',', $percelId))->get();
                    if ($percel) {

                        $apiDetails=$this->getAuthToken();
                        if($apiDetails['status']==1){
                            $authDetails=$apiDetails['authDetails'];
                            $base_url = "https://api-hermes.pathao.com";
                            //$base_url = "https://courier-api-sandbox.pathao.com";
                            //$requestUrl = $base_url . '/aladdin/api/v1/countries/1/city-list';
                            $requestUrl = $base_url . '/aladdin/api/v1/orders';

                            $headers = [
                                'Authorization' => 'Bearer ' . $authDetails->access_token,
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                            ];
                            $apiRes=[];
                            foreach ($percel as $item){
                                $body=[

                                    'store_id' => 161700,
                                    'merchant_order_id' => 'test-2'.$item->parcel_no ?? '',
                                    'sender_name' => $item->merchant->company ?? '',
                                    'sender_phone' => $item->merchant->phone_number ?? '',
                                    'recipient_name' => $item->customer_name ?? '',
                                    'recipient_phone' => $item->customer_phone_number ?? '',
                                    'recipient_address' => $item->customer_address = str_pad($item->customer_address, 10, ' '),
                                    'recipient_city' => 1,
                                    'recipient_zone' => 2,
                                    'recipient_area' => 0,
                                    'delivery_type' => 48, // is provided by the merchant and not changeable. 48 for Normal Delivery, 12 for On Demand Delivery
                                    'item_type' => 2, // is provided by the merchant and not changeable. 1 for Document, 2 for Parcel
                                    'special_instruction' => '',
                                    'item_quantity' => 1,
                                    'item_weight' => $item->weight ?? '',
                                    'amount_to_collect' => (int)$item->payable ?? 0,
                                    'item_description' => '',
                                ];
                                $response = Http::withHeaders($headers)->post($requestUrl, $body);
                                $apiRes[]=$response->body();

                            }
                            return $apiRes;


                        }else{
                            return 0;
                        }


                        /*$response = [
                            'status' => 1,
                            'count' => $percel->count(),
                            'data' => $percelData
                        ];
                        return response()->json($response);*/
                    }
                } else {
                    return response()->json(['status' => 0, 'msg' => "Please Provide Percel Id"]);
                }
            }
        }
        abort(403);
    }
    public function getAuthToken()
    {
        $pathao=ThirdParty::where('name', 'Pathao')->first();
        if($pathao){
            if($pathao->api_details){
                $tokenDetails=json_decode($pathao->api_details);
                if(time()>$tokenDetails->expires_in){
                    return ['status'=>0, 'msg'=>"get Refresh Tocken"];
                }else{
                    return ['status'=>1, 'authDetails'=>$tokenDetails, 'exp'=>time()>$tokenDetails->expires_in];
                }

            }else{
                $apiResponse=json_decode('{"token_type":"Bearer","expires_in":7776000,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImYwNzEyNjJjYzk5ZGRkOWFhMzdhODJlYzFmMDU0Mzk3MzMzM2YwZTI3NmJkNGU1ZmI2ZWUxYWYzY2JhNzBjOTA3NGE5ODk2NmEyMTUxOGUwIn0.eyJhdWQiOiI1MTA5IiwianRpIjoiZjA3MTI2MmNjOTlkZGQ5YWEzN2E4MmVjMWYwNTQzOTczMzMzZjBlMjc2YmQ0ZTVmYjZlZTFhZjNjYmE3MGM5MDc0YTk4OTY2YTIxNTE4ZTAiLCJpYXQiOjE3MTMyMDA3ODgsIm5iZiI6MTcxMzIwMDc4OCwiZXhwIjoxNzIwOTc2Nzg4LCJzdWIiOiIxODEwODgiLCJzY29wZXMiOltdfQ.2Ld3JrQ6mrMkv46GLlzswwUZ3rP88rxvii9N775tlFTlTA48HNzO3wqEihvs-tbqu9ZQ38t9Sr0QhzsVvoGn_2bGxCG-0AWWObvQCZDjeFCStXTbhuaKXjQS1Nz2NNfQqsQ5TqnsLpx6j7_1pOwF22v1PcbKUmOIg9-DYT-Fs7yGXgK1UZYoA7I4hYTcePgrBZRLTZ52S39DSmN9Bo917Wf_LDnC6tY-xSyylPd7YQuUhUt-VxDPujblPB0DG2bM6--6_g5sRQJyy_X1d6QHTgHkTOV9Sgw-9Cans66AGRkJEkec3z0LI944oXULw2Uwwjt2xN5wo20RX0H77SN8aAWIACG-gd7UTb03ERIpmGJ2qLuyDR4YyX8NzR2O9H5Clfr2mOqQAYxGNOBW2evXDFdN4OBr3v6b4p6kvc8ITwi7E7jzoiQ7mi5fyEp5oAzRKhB-uiwHC082IpcD_AmJFPdt8lhVsK7OK1ElOJIuT_QHs_2sRV6sq5iIY5O_qWARy5WbpMf87VTBDhe2Jk1EDVTIkARPpt5eLEbf5576fufprN5KcsAf81fyZz283I-FUAa_ImAalR9FqVO9srYRVm3qAP42tro6ewT1sxepCOj_CzLQkKO0oLTw5Tf4A8arQWNCWOZ1Ut092aPPinRbNWdB3z6hSar8LOc8-Vf98Rk","refresh_token":"def50200fdf8e5ec63d674b0412e852a385fc622c7920c1e6d94f46d78b10e9509cd54e72c6980dfacee2bd1500e0386acdb33a753a607565c012c1f487b00e0174087ed7e6bb5602792c6e34120cfd0fd9d14d655c5e5af41cceacd7d3cb398a1d0d6a8f41b0e6d07c825567f4ce8f8fabbe1cb84e08b753d69d00f1c467b5c3cc67cf908c896a9bf970a8d4447868d95bb12fae56842e3cc129798cd55f138b6ad4225b6c5cfe5cb6d84adc9d4050880e0eab54f12654ab783a233b0eadae51651f81dbf79927b4055616dc7bc00d8ce474d3762fc1fa3459beb13c415227db952ed5d553e49ea4b672b6c09ca29e7065e5e917e56f1b4ee26bbd15ac673cf72555b957c6082cf394a9b6a48d581ed91ed30fa3da5bd53f07a0e1bda1904ec00994bd45282fc0b33870a9832142ff5ef77aac96abbf5f40c13598d40730affd485ee5c2ad5104085d0d8968372980dbe6e8fc5cdd0a51a57dd042a734d0b4d02ba4ccdfa3aa20635"}');
                //return $apiResponse;
                //return $this->getAccessToken();
                if($apiResponse->token_type=="Bearer"){
                    $expires_in=$apiResponse->expires_in ?? 0;
                    $expireTime=Carbon::now()->addSecond($expires_in)->timestamp;
                    $tokenDetails=[
                        'expires_in'=>$expireTime,
                        'access_token'=>$apiResponse->access_token ?? '',
                        'refresh_token'=>$apiResponse->refresh_token ?? '',
                        'token_type'=>$apiResponse->token_type ?? '',
                    ];
                    $pathao->api_details=json_encode($tokenDetails);
                    if($pathao->save()){
                        return $tokenDetails;
                    }else{
                        return ['status'=>0, 'msg'=>"Token not saved!"];
                    }
                }else{
                    return ['status'=>0, 'msg'=>"Invalid Credentials!"];
                }
            }

        }else{
            return ['status'=>0, 'msg'=>"Not Found!"];
        }
    }

    /*public function bulkSendToPathao()
    {

    }*/


    public function getAccessToken()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->post('https://api-hermes.pathao.com/aladdin/api/v1/issue-token', [
                'client_id' => 'MYer0x2bOB',
                'client_secret' => 'OuzBo2ZL3gbMmlOL3Uv0vmjZMXBkSbfMHasEMLn',
                'username' => 'movexcourier3@gmail.com',
                'password' => 'shan9997',
                'grant_type' => 'password',
            ]);

        return $response;
    }




    public function insertZones($cityId)
    {

        //return PathaoCity::get();

        $zones= PathaoCourier::area()->zone($cityId);

        $zones = $zones->data;
        $zonesFormat=[];
        foreach ($zones as $item){
            $zonesFormat[]=[
                'city_id'=>$cityId,
                'zone_id'=>$item->zone_id,
                'zone_name'=>$item->zone_name,
            ];
        }
        PathaoZone::insert($zonesFormat);

    }
    public function insertArea($zoneId)
    {

        //return PathaoCity::get();
        $area= PathaoCourier::area()->area($zoneId);
        $area = $area->data;

        $areaFormat=[];
        foreach ($area as $item){
            $areaFormat[]=[
                'zone_id'=>$zoneId,
                'area_id'=>$item->area_id,
                'area_name'=>$item->area_name,
                'home_delivery_available'=>$item->home_delivery_available,
                'pickup_available'=>$item->pickup_available,
            ];
        }
        PathaoArea::insert($areaFormat);

    }
}
