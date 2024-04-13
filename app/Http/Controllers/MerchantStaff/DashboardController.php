<?php

namespace App\Http\Controllers\MerchantStaff;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Parcel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $all_parcels = \Sentinel::getUser()->staffMerchant->parcels()->when(!hasPermission('all_parcel'), function ($query){
            $query->where('user_id', \Sentinel::getUser()->id);
        });

        $latest_parcel = $all_parcels->take(5)->get();

        $data['all_parcels_count'] = $all_parcels->count();
        $data['cod']               = $all_parcels->where(function ($query){
                                        $query->whereIn('status',['delivered','delivered-and-verified'])
                                            ->orWhere('is_partially_delivered',true);
                                        })
                                        ->sum('price');
        $data['all_delivered_parcels'] = $all_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
        $month = date('Y-m');
        $parcels = \Sentinel::getUser()->staffMerchant->parcels()
            ->when(!hasPermission('all_parcel'), function ($query){
                $query->where('user_id', \Sentinel::getUser()->id);
            })
            ->where('created_at', 'like', '%' . $month . '%')->get();

        for($i = 1; $i <= date('t'); $i++){
            if ($i < 10) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            //date range parcels

            $created_at = date('Y-m-'.$i);

            $merchant_parcels             = $parcels->where('date', $created_at);

            // dates
            $data['dates'][]              = $i.' '. date('M');

            $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
            $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
            $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
            $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
            $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
            $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
        }

        $data['totalParcels']       = $parcels->count();
        $data['totalCancelled']     = $parcels->where('status','cancel')->count();
        $data['totalDeleted']       = $parcels->where('status','deleted')->count();
        $data['totalDelivered']     = $parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);
        $counts = $this->get_counts($parcels);

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $notices = Notice::where('status', true)->where('merchant', true)->where('start_time' ,'<=', $current_time )->where('end_time', '>=', $current_time)->get();

        return view('merchant-staff.dashboard',compact('data','counts', 'notices','latest_parcel'));
    }

    public function get_counts($parcels)
    {
       $parcels = \Sentinel::getUser()->staffMerchant->parcels()
            ->when(!hasPermission('all_parcel'), function ($query){
                $query->where('user_id', \Sentinel::getUser()->id);
            })->get();

        $delivered_cod              = $parcels->whereIn('status',['delivered','delivered-and-verified'])->sum('price');
        $data['total_cod']          = number_format($parcels->where('is_partially_delivered', true)->sum('price') + $delivered_cod, 2).' '.__('tk');
        $data['parcels_count']      = $parcels->count();
        $data['processing_count']   = $parcels->whereNotIn('status', ['delivered','delivered-and-verified', 'cancel', 'returned-to-merchant','deleted'])->where('is_partially_delivered', false)->count();
        $data['cancelled_count']    = $parcels->where('status','cancel')->count();
        $data['deleted_count']      = $parcels->where('status','deleted')->count();
        $data['partial_delivered_count'] = $parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']     = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['delivered']          = $parcels->whereIn('status', ['delivered','delivered-and-verified'])->count();

        return $data;
    }
}
