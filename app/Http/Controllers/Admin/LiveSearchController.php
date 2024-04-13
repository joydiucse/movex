<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ThirdParty;
use App\Models\User;
use Illuminate\Http\Request;

class LiveSearchController extends Controller
{
    public function getMerchant(Request $request)
    {
        $term           = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        $merchants             = Merchant::with('user')
                                        ->when(!hasPermission('use_all_merchant'), function ($query){
                                            $query->whereHas('user',function ($q){
                                                $q->where('hub_id', \Sentinel::getUser()->hub_id);
                                            });
                                        })
                                        ->where('company', 'LIKE', '%' . $term . '%')
                                        ->where('status', '1')
                                        ->limit(50)->get();

        $formatted_merchants    = [];

        foreach ($merchants as $merchant) {
            $formatted_merchants[] = ['id' => $merchant->id, 'text' => $merchant->user->first_name.' '.$merchant->user->last_name.' ('.$merchant->company.')'];
        }

        return \Response::json($formatted_merchants);
    }
    public function getDeliveryMan(Request $request)
    {
        $term           = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }

        $delivery_men = DeliveryMan::whereHas('user', function($inner_query) use ($term) {
                                        $inner_query->where('status', true);
                                        $inner_query->where(function ($query) use ($term){
                                            $query->where('first_name',  'LIKE', '%' . $term . '%')
                                                  ->orWhere('last_name',  'LIKE', '%' . $term . '%');
                                        });
                                    })
                                    ->when(!hasPermission('use_all_delivery_man') or !hasPermission('read_all_delivery_man'), function ($query){
                                        $query->whereHas('user', function ($q){
                                            $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                                ->orWhere('hub_id', null);
                                        });
                                    })
                                    ->when(!hasPermission('access_vertual_delivery_man'), function ($query){
                                         $query->where('is_vertual', 0);
                                    })
                                    ->limit(50)->get();

        $formatted_delivery_men   = [];

        foreach ($delivery_men as $delivery_man) {
            $formatted_delivery_men[] = ['id' => $delivery_man->id, 'text' => $delivery_man->user->first_name.' '.$delivery_man->user->last_name];
        }

        return \Response::json($formatted_delivery_men);
    }
    public function getUser(Request $request)
    {
        $term           = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }

        $users = User::where('user_type', 'staff')->where('first_name', 'LIKE', '%' . $term . '%')->limit(50)->get();


        $formatted_users   = [];

        foreach ($users as $user) {
            $formatted_users[] = ['id' => $user->id, 'text' => $user->first_name.' '.$user->last_name];
        }

        return \Response::json($formatted_users);
    }
    public function getParcel(Request $request)
    {
        $term           = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }

        $parcels = Parcel::where('parcel_no', 'LIKE', '%' . $term . '%')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where('hub_id', \Sentinel::getUser()->hub_id)
                                    ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                    ->orWhereNull('pickup_hub_id')
                                    ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                            })
                            ->limit(50)->get();


        $formatted_parcels   = [];

        foreach ($parcels as $parcel) {
            $formatted_parcels[] = ['id' => $parcel->id, 'text' => $parcel->parcel_no.' ('.$parcel->merchant->company.')'];
        }

        return \Response::json($formatted_parcels);
    }

    public function getThirdParty(Request $request)
    {
        $term       = trim($request->q);
        if (empty($term)){
            return \Response::json([]);
        }

        $third_parties = ThirdParty::where('status', true)->where('name','LIKE', '%'.$term.'%')
                                    ->limit(20)->get();

        $formatted_third_parties = [];

        foreach ($third_parties as $third_party){
            $formatted_third_parties[] = ['id' => $third_party->id, 'text' => $third_party->name.' ('.$third_party->address.')'];
        }

        return \Response::json($formatted_third_parties);
    }


    public function getShuttleMan(Request $request)
    {
        $term           = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }

        $delivery_men = DeliveryMan::whereHas('user', function($inner_query) use ($term) {
                                        $inner_query->where('status', true);
                                        $inner_query->where(function ($query) use ($term){
                                            $query->where('first_name',  'LIKE', '%' . $term . '%')
                                                  ->orWhere('last_name',  'LIKE', '%' . $term . '%');
                                        });
                                    })
                                    ->where('is_shuttle', 1)
                                    ->limit(50)->get();

        $formatted_delivery_men   = [];

        foreach ($delivery_men as $delivery_man) {
            $formatted_delivery_men[] = ['id' => $delivery_man->id, 'text' => $delivery_man->user->first_name.' '.$delivery_man->user->last_name];
        }

        return \Response::json($formatted_delivery_men);
    }
}
