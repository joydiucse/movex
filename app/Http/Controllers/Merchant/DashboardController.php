<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Parcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Sentinel;
class DashboardController extends Controller
{
    public function index()
    {

        $data['cod'] = Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)
            ->where(function ($query){
                $query->whereIn('status',['delivered','delivered-and-verified'])
                    ->orWhere('is_partially_delivered',true);
            })
            ->sum('price');

        $data['charge'] = Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)
            ->where(function ($query){
                $query->whereIn('status',['delivered','delivered-and-verified'])
                    ->orWhere('is_partially_delivered',true);
            })
            ->sum('total_delivery_charge');

        $month = date('Y-m');
        $parcels = \Sentinel::getUser()->merchant->parcels()->where('date', 'like', '%' . $month . '%')->get();
        $create_parcels = \Sentinel::getUser()->merchant->parcels()->where('created_at', 'like', '%' . $month . '%')->get();

        for($i = 1; $i <= date('t'); $i++){
            if ($i < 10) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
            }
            //date range parcels

            $created_at = date('Y-m-'.$i);

            $merchant_parcels             = $parcels->where('date', $created_at);

            // dates
            $data['dates'][]              = $i.' '. date('M');

            $data['totalParcel'][]        = $totalParcel         = $create_parcels->count();
            $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
            $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
            $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
            $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
            $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['processing'][]         = $merchant_parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        }


        $data['totalParcels']       = $create_parcels->count();
        $data['totalCancelled']     = $parcels->where('status','cancel')->count();
        $data['totalDeleted']       = $parcels->where('status','deleted')->count();
        $data['totalDelivered']     = $parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing']    = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        $counts = $this->get_counts($parcels, $create_parcels);

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $notices = Notice::where('status', true)->where('merchant', true)->where('start_time' ,'<=', $current_time )->where('end_time', '>=', $current_time)->get();

        $total_delivered = Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)
                            ->where(function ($query){
                                $query->whereIn('status',['delivered','delivered-and-verified']);
                                $query->orwhere('is_partially_delivered', true);
                            })
                            ->count();
        $delivery_parcent = $this->getPercentage($total_delivered);

        $total_pending =  Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->where(function ($query){
                $query->whereNotIn('status', ['delivered', 'returned-to-merchant', 'deleted', 'delivered-and-verified', 'cancel', 'returned-to-greenx', 'return-assigned-to-merchant']);
        })->count();
        $pending_parcent = $this->getPercentage($total_pending);

        $total_cancel =  Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->whereIn('status', ['cancel', 'returned-to-greenx', 'return-assigned-to-merchant', 'returned-to-merchant'])->where('is_partially_delivered', false)->count();
        $cancel_parcent = $this->getPercentage($total_cancel);

        $total_delete =  Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->where('status', 'deleted')->count();
        $delete_parcent = $this->getPercentage($total_delete);


        $total_return_pending =  Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->whereIn('status', ['returned-to-greenx', 'return-assigned-to-merchant', 'cancel'])->where('is_partially_delivered', false)->count();
        $return_pending_parcent = $this->getRetunParcent($total_return_pending);

        $total_return =  Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $return_parcent = $this->getRetunParcent($total_return);

        $info = [
            'data' => $data,
            'counts' => $counts,
            'notices' => $notices,
            'total_delivered' => $total_delivered,
            'delivery_parcent' => $delivery_parcent,
            'total_pending' => $total_pending,
            'pending_parcent' => $pending_parcent,
            'total_cancel' => $total_cancel,
            'cancel_parcent' => $cancel_parcent,
            'total_delete' => $total_delete,
            'delete_parcent' => $delete_parcent,
            'total_return_pending' => $total_return_pending,
            'return_pending_parcent' => $return_pending_parcent,
            'total_return' => $total_return,
            'return_parcent' => $return_parcent
        ];
        return view('merchant.dashboard',$info);
    }

    public function get_counts($parcels, $create_parcels)
    {
        $delivered_cod              = $parcels->whereIn('status',['delivered','delivered-and-verified'])->sum('price');
        $data['total_cod']          = number_format($parcels->where('is_partially_delivered', true)->sum('price') + $delivered_cod, 2).' '.__('tk');
        $data['parcels_count']      = $create_parcels->count();
        $data['processing_count']   = $parcels->whereNotIn('status', ['delivered','delivered-and-verified', 'cancel', 'returned-to-merchant','deleted'])->where('is_partially_delivered', false)->count();
        $data['cancelled_count']    = $parcels->where('status','cancel')->count();
        $data['deleted_count']      = $parcels->where('status','deleted')->count();
        $data['partial_delivered_count'] = $parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']     = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['delivered']          = $parcels->whereIn('status', ['delivered','delivered-and-verified'])->count();

        return $data;
    }


    public function getPercentage($total_parcel)
    {
        $parcels  = Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->count();
        if($parcels == 0 ): $parcels = 1; endif;
        return $parcentage = ($total_parcel * 100) / $parcels ;

    }

    public function getRetunParcent($total_parcel)
    {
        $parcels  = Parcel::where('merchant_id', \Sentinel::getUser()->merchant->id)->whereIn('status', ['cancel', 'returned-to-greenx', 'return-assigned-to-merchant', 'returned-to-merchant'])->where('is_partially_delivered', false)->count();
        if($parcels == 0 ): $parcels = 1; endif;
        return $parcentage = ($total_parcel * 100) / $parcels ;
    }
}
