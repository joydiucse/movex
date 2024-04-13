<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account\CompanyAccount;
use App\Models\Account\Account;
use App\Models\Account\DeliveryManAccount;
use App\Models\Account\GovtVat;
use App\Models\Merchant;
use App\Models\DeliveryMan;
use App\Models\MerchantPaymentAccount;
use App\Models\Account\MerchantAccount;
use App\Models\Account\MerchantWithdraw;
use App\Models\Notice;
use App\Models\PackageAndCharge;
use App\Models\Parcel;
use App\Models\Shop;
use App\Models\StaffAccount;
use App\Models\ParcelEvent;
use App\Models\User;
use App\Traits\RandomStringTrait;
use App\Traits\ShortenLinkTrait;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\str;
use DB;
use App\Repositories\Interfaces\AccountInterface;

class DashboardController extends Controller
{
    use RandomStringTrait, ShortenLinkTrait;

    public function index()
    {

        $today        = date('Y-m-d');
        $parcels        = Parcel::where('created_at', '>=', $today. ' 00:00:00')
                                ->where('created_at', '<=', $today. ' 23:59:59')
                                ->when(!hasPermission('read_all_parcel'), function ($query){
                                    $query->where(function ($q){
                                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                            ->orWhereNull('pickup_hub_id')
                                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                    });
                                })
                                ->latest()
                                ->get();

        $parcel_delivered = ParcelEvent::whereDate('created_at', $today. ' 00:00:00')
                                    ->whereDate('created_at', '<=', $today. ' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

        $data['totalParcelDelivered'] = $parcel_delivered->count();

        $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

        for($i = 0; $i <= 11; $i++){

            $j  = $i * 2;

            $j  = str_pad($j, 2, "0", STR_PAD_LEFT);
            $in = $j + 1;
            if ($in < 10) {
                $in = str_pad($in, 2, "0", STR_PAD_LEFT);
            }

            //date range parcels
            $start  = date('Y-m-d ') .$j.':00:00';
            $end    = date('Y-m-d ') .$in.':59:59';

            $merchant_parcels             = $parcels->where('created_at', '>=', $start);
            $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

            // count
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

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $today)->sum('amount');

        $profits = $this->profits($today, $today);

        $recent_parcels = Parcel::when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where('hub_id', \Sentinel::getUser()->hub_id)
                                    ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                    ->orWhereNull('pickup_hub_id')
                                    ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                })
                                ->latest()->take(5)->get();

        $withdraws = MerchantWithdraw::where('status','processed')->take(7)->latest()->get();

        $counts = $this->get_counts($parcels);

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $notices = Notice::where('status', true)->where('staff', true)->where('start_time' ,'<=', $current_time )->where('end_time', '>=', $current_time)->get();

        return view('dashboard',compact('data','counts','withdraws','profits','recent_parcels','notices'));
    }

    public function get_counts($parcels)
    {
        $delivered_cod              = $parcels->whereIn('status',['delivered','delivered-and-verified'])->sum('price');
        $data['total_cod']          = number_format($parcels->sum('price') + $delivered_cod, 2).' '.__('tk');
        $data['parcels_count']      = $parcels->count();
        $data['processing_count']   = $parcels->whereNotIn('status', ['delivered','delivered-and-verified', 'cancel', 'returned-to-merchant','deleted'])->where('is_partially_delivered', false)->count();
        $data['cancelled_count']    = $parcels->where('status','cancel')->count();
        $data['deleted_count']      = $parcels->where('status','deleted')->count();
        $data['partial_delivered_count'] = $parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']     = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['delivery_count']      = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        return $data;
    }

    public function report(Request $request)
    {
        $report_type = $request->report_type;

        if ($report_type == 'today'):
            $today          = date('Y-m-d');
            $parcels    = Parcel::where('created_at', '>=', $today. ' 00:00:00')
                                ->where('created_at', '<=', $today. ' 23:59:59')
                                ->when(!hasPermission('read_all_parcel'), function ($query){
                                    $query->where(function ($q){
                                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                            ->orWhereNull('pickup_hub_id')
                                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                    });
                                })
                                ->latest()->get();

            $parcel_delivered = ParcelEvent::whereDate('created_at', $today. ' 00:00:00')
                                    ->whereDate('created_at', '<=', $today. ' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();


            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for($i = 0; $i <= 11; $i++){

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = date('Y-m-d ') .$j.':00:00';
                $end = date('Y-m-d ') .$in.':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $today)->sum('amount');

            $profits = $this->profits($today, $today);

        elseif ($report_type == 'yesterday'):
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $parcels = Parcel::where('created_at', '>=', $yesterday. ' 00:00:00')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->where('created_at', '<=', $yesterday. ' 23:59:59')
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::whereDate('created_at', $yesterday. ' 00:00:00')
                            ->whereDate('created_at', '<=', $yesterday. ' 23:59:59')
                            ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                            ->where('reverse_status', null)
                            ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for($i = 0; $i <= 11; $i++){

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = $yesterday.' '.$j.':00:00';
                $end =  $yesterday.' '.$in.':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $yesterday)->sum('amount');

            $profits = $this->profits($yesterday, $yesterday);

        elseif($report_type == 'this_week'):
            $now = Carbon::now();

            $start_day = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY)));
            $end_day = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY)));

            $parcels = Parcel::where('created_at', '>=', $start_day. ' 00:00:00')
                            ->where('created_at', '<=', $end_day. ' 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_day. ' 00:00:00')
                                    ->where('created_at', '<=', $end_day. ' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 0; $i <= 6; $i++){

                $created_at = date('Y-m-d',strtotime($start_day."+".$i. ' days' ));

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at. ' 00:00:00')->where('created_at', '<=', $created_at. ' 23:59:59');

                // dates
                $data['dates'][]              = date('d M, Y',strtotime($start_day."+".$i. ' days' ));

                // count
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

            $start = date('Y-m-d',strtotime($start_day));
            $end = date('Y-m-d',strtotime($end_day));

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date','>=', $start)
                ->where('date', '<=',$end)
                ->sum('amount');

            $profits = $this->profits($start, $end);

        elseif($report_type == 'last_week'):
            $now = Carbon::now();

            $start_day = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY).('-1 week')));
            $end_day = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY).('-1 week')));

            $parcels = Parcel::where('created_at', '>=', $start_day. ' 00:00:00')
                ->where('created_at', '<=', $end_day. ' 23:59:59')
                ->when(!hasPermission('read_all_parcel'), function ($query){
                    $query->where(function ($q){
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    });
                })
                ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_day. ' 00:00:00')
                                    ->where('created_at', '<=', $end_day. ' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 0; $i <= 6; $i++){

                $created_at = date('Y-m-d',strtotime($start_day."+".$i. ' days' ));

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at. ' 00:00:00')->where('created_at', '<=', $created_at. ' 23:59:59');

                // dates
                $data['dates'][]              = date('d M, Y',strtotime($start_day."+".$i. ' days' ));

                // count
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

            $start = date('Y-m-d',strtotime($start_day));
            $end = date('Y-m-d',strtotime($end_day));

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date','>=', $start)
                ->where('date', '<=',$end)
                ->sum('amount');

            $profits = $this->profits($start, $end);
        elseif($report_type == 'this_month'):

            $start = date('Y-m-'.'01');
            $end = date('Y-m-t');

            $parcels = Parcel::where('created_at', '>=', $start.' 00:00:00')
                            ->where('created_at', '<=', $end.' 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();
            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start.' 00:00:00')
                                    ->where('created_at', '<=', $end.' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 1; $i <= date('t'); $i++){
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m-'.$i);

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at.' 00:00:00')->where('created_at', '<=', $created_at.' 23:59:59');

                // dates
                $data['dates'][]              = $i.' '. date('M');

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date','>=', $start)
                ->where('date', '<=',$end)
                ->sum('amount');

            $profits = $this->profits($start, $end);
        elseif($report_type == 'last_month'):

            $start  = date('Y-m-d',strtotime("first day of -1 month"));
            $end    = date('Y-m-d',strtotime("last day of -1 month"));

            $parcels = Parcel::where('created_at', '>=', $start.' 00:00:00')
                            ->where('created_at', '<=', $end.' 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start.' 00:00:00')
                                    ->where('created_at', '<=', $end.' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 1; $i <= date('t', strtotime('last day of -1 month')); $i++){
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m', strtotime('first day of -1 month')).'-'.$i;

                $merchant_parcels             = $parcels->where('created_at', '>=', $created_at.' 00:00:00')->where('created_at', '<=', $created_at.' 23:59:59');

                // dates
                $data['dates'][]              = $i.' '. date('M', strtotime('first day of -1 month'));

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])
                ->where('date','>=', $start)
                ->where('date', '<=',$end)
                ->sum('amount');

            $profits = $this->profits($start, $end);

        elseif($report_type == 'last_3_month'):
            $start_month    = date('Y-m', strtotime('-3 month'));
            $end_month      = date('Y-m', strtotime('first day of -1 month'));

            $parcels    = Parcel::where('created_at', '>=', $start_month.'-01'.' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')). ' 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month.'-01'.' 00:00:00')
                                    ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')). ' 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 3; $i >= 1; $i--){

                $start = date('Y-m-d', strtotime('first day of -'.$i.' month'));
                $end   = date('Y-m-d', strtotime('last day of -'.$i.' month'));

                $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');

                // dates
                $data['dates'][]              = $start = date('Y-m', strtotime('first day of -'.$i.' month'));

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_month)->where('date', '<=',$end_month)->sum('amount');

            $start = date('Y-m-d', strtotime('first day of -3 month')                                               );
            $end = date('Y-m-d', strtotime('last day of -1 month'));

            $profits = $this->profits($start, $end);

        elseif($report_type == 'last_6_month'):
            $start_month    = date('Y-m', strtotime('-6 month'));
            $end_month      = date('Y-m', strtotime('first day of -1 month'));

            $parcels = Parcel::where('created_at', '>=', $start_month.'-01'.' 00:00:00')
                            ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')). ' 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month.'-01'.' 00:00:00')
                                ->where('created_at', '<=', date('Y-m-d', strtotime('last day of -1 month')). ' 23:59:59')
                                ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                ->where('reverse_status', null)
                                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 6; $i >= 1; $i--){

                $start = date('Y-m-d', strtotime('first day of -'.$i.' month'));
                $end   = date('Y-m-d', strtotime('last day of -'.$i.' month'));

                $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');

                // dates
                $data['dates'][]              = $start = date('Y-m', strtotime('first day of -'.$i.' month'));

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_month)->where('date', '<=',$end_month)->sum('amount');
            $start = date('Y-m-d', strtotime('first day of -6 month'));
            $end = date('Y-m-d', strtotime('last day of -1 month'));

            $profits = $this->profits($start, $end);

        elseif($report_type == 'this_year'):

            $start_month = date('Y-'.'01');
            $end_month = date('Y-'.'12');

            $parcels = Parcel::where('created_at', '>=', $start_month.'-01 00:00:00')
                            ->where('created_at', '<=', $end_month. '-31 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month.'-01 00:00:00')
                                    ->where('created_at', '<=', $end_month. '-31 23:59:59')
                                    ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                    ->where('reverse_status', null)
                                    ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 1; $i <= 12; $i++){

                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }

                $created_at = date('Y-'.$i);

                $start = $created_at.'-01';
                $end   = $created_at.'-'.$this->getLastDateOfMonth(01);

                $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');
                // dates
                $data['dates'][]              = $created_at;

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_month)->where('date', '<=',$end_month)->sum('amount');

            $profits = $this->profits($start_month.'-01', $end_month.'-31');
        elseif($report_type == 'last_year'):
            $start_month = date('Y-'.'01', strtotime('-1 year'));
            $end_month = date('Y-'.'12',strtotime('-1 year'));

            $parcels = Parcel::where('created_at', '>=', $start_month.'-01 00:00:00')
                            ->where('created_at', '<=', $end_month. '-31 23:59:59')
                            ->when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })
                            ->latest()->get();

            $parcel_delivered = ParcelEvent::where('created_at', '>=', $start_month.'-01 00:00:00')
                                ->where('created_at', '<=', $end_month. '-31 23:59:59')
                                ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                ->where('reverse_status', null)
                                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            for($i = 1; $i <= 12; $i++){

                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }

                $created_at = date('Y-'.$i,strtotime('-1 year'));

                $start = $created_at.'-01';
                $end   = $created_at.'-'.$this->getLastDateOfMonth(01);

                $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');

                // dates
                $data['dates'][]              = $created_at;

                // count
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

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_month)->where('date', '<=',$end_month)->sum('amount');

            $profits = $this->profits($start_month.'-01', $end_month.'-31');
        elseif($report_type == 'lifetime'):

            $parcels = Parcel::when(!hasPermission('read_all_parcel'), function ($query){
                                $query->where(function ($q){
                                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                        ->orWhereNull('pickup_hub_id')
                                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                                });
                            })->latest()->get();

            $parcel_delivered = ParcelEvent::whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                ->where('reverse_status', null)
                                ->get();

            $data['totalParcelDelivered'] = $parcel_delivered->count();

            $start_year = date('Y', strtotime($parcels->min('date')));
            $last_year = date('Y');

            if ($start_year - $last_year == 0):
                $start_year = $last_year;
                for($i = 1; $i <= 12; $i++){

                    if ($i < 10) {
                        $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }

                    $created_at = date('Y-'.$i);

                    $start = $created_at.'-01';
                    $end   = $created_at.'-'.$this->getLastDateOfMonth(01);

                    $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                    $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');
                    // dates
                    $data['dates'][]              = $created_at;

                    // count
                    $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                    $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
                    $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
                    $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                    $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
                    $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
                    $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
                }
            else:
                for($i = $start_year; $i <= $last_year ; $i++){
                    $start = $i.'-01-01';
                    $end   = $i.'-12-31';

                    $merchant_parcels             = $parcels->where('created_at', '>=',$start.' 00:00:00'. '%');
                    $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$end.' 23:59:59'. '%');

                    $data['dates'][]              = $i;

                    // count
                    $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                    $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
                    $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
                    $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                    $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
                    $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
                    $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
                }
            endif;

            $data['totalParcels']       = $parcels->count();
            $data['totalCancelled']     = $parcels->where('status','cancel')->count();
            $data['totalDeleted']       = $parcels->where('status','deleted')->count();
            $data['totalDelivered']     = $parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_year.'-01-01')->where('date', '<=',$last_year.'-12-31')->sum('amount');

            $profits = $this->profits($start_year.'-01-01', $last_year.'-12-31');
        endif;

        $counts = $this->get_counts($parcels);

        return view('admin.dashboard.report',compact('data', 'counts','profits'))->render();
    }

    public function customDateRange(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $parcels = Parcel::where('created_at', '>=', $start_date.' 00:00:00')
            ->where('created_at', '<=', $end_date. ' 23:59:59')
            ->when(!hasPermission('read_all_parcel'), function ($query){
                $query->where(function ($q){
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhereNull('pickup_hub_id')
                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                });
            })
            ->latest()->get();

        $parcel_delivered = ParcelEvent::whereDate('created_at', '>=', $start_date.' 00:00:00')
                                ->whereDate('created_at', '<=', $end_date. ' 23:59:59')
                                ->whereIn('title', ['parcel_delivered_event','parcel_partial_delivered_event','parcel_partial_delivered_event'])
                                ->where('reverse_status', null)
                                ->get();

        $data['totalParcelDelivered'] = $parcel_delivered->count();

        $start_date = date_create($start_date);
        $end_date = date_create($end_date);

        $different_days = date_diff($start_date, $end_date);

        $days = $different_days->format("%a");

        if ($days == 0):

            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for($i = 0; $i <= 11; $i++){

                $j = $i * 2;

                $j = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start = $request->start_date.' '.$j.':00:00';
                $end =  $request->start_date.' '.$in.':59:59';

                $merchant_parcels             = $parcels->where('created_at', '>=', $start);
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=', $end);

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
                $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }
        else:
            for($i = $days; $i >=0 ; $i--){
                if ($i < 10) {
                    $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                }
                //date range parcels

                $created_at = date('Y-m-d', strtotime('-'.$i.' days',strtotime($request->end_date)));

                $merchant_parcels             = $parcels->where('created_at', '>=',$created_at.' 00:00:00'. '%');
                $merchant_parcels             = $merchant_parcels->where('created_at', '<=',$created_at.' 23:59:59'. '%');

                // dates
                $data['dates'][]              = $created_at;

                // count
                $data['totalParcel'][]        = $totalParcel         = $merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status','cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status','deleted')->count();
                $data['partially_delivered'][]= $partially_delivered = $merchant_parcels->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $totalParcel - ($cancelled + $deleted + $partially_delivered + $delivered + $returned);
            }
        endif;
        $data['totalParcels']       = $parcels->count();
        $data['totalCancelled']     = $parcels->where('status','cancel')->count();
        $data['totalDeleted']       = $parcels->where('status','deleted')->count();
        $data['totalDelivered']     = $parcels->whereIn('status',['delivered','delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status','returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing']    = $data['totalParcels'] - ($data['totalCancelled'] + $data['totalDeleted'] + $data['totalDelivered'] + $data['totalPartialDelivered'] + $data['totalReturned']);

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date','>=', $start_date)->where('date', '<=',$end_date)->sum('amount');

        $profits = $this->profits($request->start_date, $request->end_date);

        $counts = $this->get_counts($parcels);

        return view('admin.dashboard.report',compact('data','counts','profits'))->render();

    }

    public function profits($start, $end){

        $total_vat_income                         = GovtVat::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'income')
                                                    ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery','parcel_return'])
                                                    ->sum('amount');

        $total_vat_expense                        = GovtVat::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'expense')
                                                    ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery','parcel_return'])
                                                    ->sum('amount');
        $return_income                            = MerchantAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'income')
                                                    ->where(function ($query){
                                                        $query->where('source','parcel_return')
                                                            ->orWhere(function ($query){
                                                                $query->where('source','vat_adjustment')
                                                                    ->whereIn('details',['govt_vat_for_parcel_return','govt_vat_for_parcel_return_reversed']);
                                                            });
                                                    })
                                                    ->sum('amount');
        $return_expense                            = MerchantAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type', 'expense')
                                                    ->where(function ($query){
                                                        $query->where('source','parcel_return')
                                                            ->orWhere(function ($query){
                                                                $query->where('source','vat_adjustment')
                                                                    ->whereIn('details',['govt_vat_for_parcel_return','govt_vat_for_parcel_return_reversed']);
                                                            });
                                                    })
                                                    ->sum('amount');

        $data['total_parcel_return_charge']       = $return_expense - $return_income;


        $data['total_vat']                        = $total_vat_income - $total_vat_expense;


        $total_charge_vat                         =  Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                              ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('total_delivery_charge');

        $data['total_charge_vat']                 =  $total_charge_vat + $data['total_parcel_return_charge'];


        $total_delivery_charge_income             = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('source', ['pickup_commission','parcel_delivery', 'parcel_return'])
                                                    ->where('type', 'income')
                                                    ->sum('amount');

        $total_delivery_charge_expense            = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('source', ['pickup_commission','parcel_delivery', 'parcel_return'])
                                                    ->where('type', 'expense')
                                                    ->sum('amount');

        $data['total_delivery_charge']            = $total_delivery_charge_expense - $total_delivery_charge_income;

        $data['total_fragile_charge']             = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('fragile_charge');
        $data['total_packaging_charge']           = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('packaging_charge');

        $data['total_profit']                     = abs($data['total_charge_vat']) - $data['total_delivery_charge'] -  $data['total_vat'] + $data['total_fragile_charge'] + $data['total_packaging_charge'];


        $data['total_payable_to_merchant']         = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('price');

        $data['total_paid_to_merchant']           = MerchantWithdraw::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('status', ['processed', 'pending','approved'])
                                                    ->sum('amount');

        $data['pending_payments']                 = MerchantWithdraw::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->whereIn('status', ['pending','approved'])
                                                    ->sum('amount');

        $data['total_paid_by_merchant']           = CompanyAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('source', 'delivery_charge_receive_from_merchant')
                                                    ->where('type', 'income')
                                                    ->where('merchant_id', '!=', '')
                                                    ->sum('amount');

        $data['current_payable']                  = abs($data['total_payable_to_merchant']) + $data['total_paid_by_merchant'] - $data['total_paid_to_merchant'] -  $data['total_charge_vat'];

        $data['total_cash_on_delivery']           = Parcel::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('price');

        $data['total_paid_by_delivery_man']       = DeliveryManAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('delivery_man_id', '!=', '')
                                                    ->where('source','cash_given_to_staff')
                                                    ->where('type','expense')
                                                    ->sum('amount');

        $data['total_expense_from_account']        = CompanyAccount::where('date', '>=', $start)
                                                    ->where('date', '<=', $end)
                                                    ->where('type','expense')
                                                    ->where('create_type', 'user_defined')
                                                    ->sum('amount');

        $start = $start.' '.'00:00:00';
        $end =  $end.' '.'23:59:59';

        $data['total_bank_opening_balance']        = Account::where('created_at', '>=', $start)
                                                    ->where('created_at', '<=', $end)
                                                    ->sum('balance');

        return $data;

    }

    public function getLastDateOfMonth($month)
    {
        $date = date('Y').'-'.$month.'-01';  //make date of month
        return date('t', strtotime($date));
    }

    public function oldMigration(){


        // $from_merchant = CompanyAccount::where('merchant_id', '!=', '')->where('merchant_withdraw_id', NULL)->where('type', 'income')->sum('amount');

        // $from_merchant = CompanyAccount::where('delivery_man_id', '!=', '')->where('type', 'income')->sum('amount');

        // $from_merchant = CompanyAccount::where('delivery_man_id', NULL)->where('merchant_withdraw_id', NULL)->where('type', 'expense')->sum('amount');
        // dd($from_merchant);

        // $withdraws = MerchantWithdraw::whereIn('status', ['pending', 'processed'])->sum('amount');

        // dd($withdraws);



        // $withdraws = MerchantWithdraw::whereIn('status', ['rejected', 'cancelled'])->where('id', '<=', 613)->get();

        // foreach($withdraws as $withdraw){

        //     $merchant = Merchant::find($withdraw->merchant_id);
        //     // dd($merchant);
        //     echo $merchant->id.'-'.$merchant->balance($merchant->id).'<br>';


        // }
        // dd('csdc');



    //     DB::beginTransaction();
    //     try{

    //     $withdraws = MerchantWithdraw::whereIn('status', ['rejected', 'cancelled'])->where('id', '<=', 613)->get();

    //     foreach($withdraws as $withdraw){

    //         //company table inserting the credit amount
    //         $company_account                       = new CompanyAccount();
    //         $company_account->source               = 'delivery_charge_receive_from_merchant';
    //         $company_account->details              = 'Adjust Merchant Balance';
    //         $company_account->date                 = date('Y-m-d', strtotime($withdraw->date));
    //         $company_account->merchant_id          = $withdraw->merchant_id;
    //         $company_account->type                 = 'income';
    //         $company_account->amount               = - $withdraw->amount;
    //         $company_account->created_by           = 1;
    //         $company_account->user_id              = 1;
    //         $company_account->create_type          = 'user_defined';
    //         $company_account->account_id           = 1;
    //         $company_account->created_at            = $withdraw->created_at;
    //         $company_account->updated_at            = $withdraw->updated_at;
    //         $company_account->save();
    //         //end for company

    //         //staff account calculation and insertion
    //         $staff_account                      = new StaffAccount();
    //         $staff_account->source              = 'delivery_charge';
    //         $staff_account->details             = 'Adjust Merchant Balance';
    //         $staff_account->date                = date('Y-m-d', strtotime($withdraw->date));
    //         $staff_account->type                = 'income';
    //         $staff_account->amount              = - $withdraw->amount;
    //         $staff_account->user_id             = $withdraw->user_id;
    //         $staff_account->account_id          = 1;
    //         $staff_account->company_account_id  = $company_account->id;
    //         $staff_account->created_at            = $withdraw->created_at;
    //         $staff_account->updated_at            = $withdraw->updated_at;
    //         $staff_account->save();
    //         //end for staff account

    //         $merchant_account                       = new MerchantAccount();
    //         $merchant_account->source               = 'cash_given_for_delivery_charge';
    //         $merchant_account->details              = 'Adjust Merchant Balance';
    //         $merchant_account->date                 = date('Y-m-d', strtotime($withdraw->date));
    //         $merchant_account->type                 = 'income';
    //         $merchant_account->amount               = - $withdraw->amount;
    //         $merchant_account->merchant_id          = $withdraw->merchant_id;
    //         $merchant_account->company_account_id   = $company_account->id;
    //         $merchant_account->created_at            = $withdraw->created_at;
    //         $merchant_account->updated_at            = $withdraw->updated_at;
    //         $merchant_account->save();
    //         //end  cash from merchant
    //     }


    //     DB::commit();
    //     dd('success');


    // } catch (\Exception $e) {
    //     DB::rollback();
    //     dd('failed');
    // }

    // dd('jgjhgfj');
        // DB::beginTransaction();
        // // try{

        // $withdraws = MerchantWithdraw::whereIn('status', ['rejected', 'cancelled'])->get();


        // foreach($withdraws as $withdraw){
        //     // dd($withdraw);
        //     //echo $withdraw->merchant_id.',';

        //     $company_account = CompanyAccount::where('merchant_withdraw_id', $withdraw->id)->first();
        //     if(!blank($company_account)){



        //     $merchant_Account = MerchantAccount::where('merchant_withdraw_id', $withdraw->id)->first();
        //     echo $withdraw->id.'-'.$withdraw->merchant_id.'-'.$merchant_Account->company_account_id.'-'.$merchant_Account->id.'-'.$withdraw->amount.'<br>';
        // }

            // //company table data insertion and calculation
            // $company_account                       = new CompanyAccount();
            // $company_account->source               = 'payment_withdraw_by_merchant';
            // $company_account->details              = __('payment_withdraw_by_merchant');
            // $company_account->date                 = $withdraw->date;
            // $company_account->type                 = 'expense';
            // $company_account->amount               = $withdraw->amount;
            // $company_account->created_by           = 1;
            // $company_account->merchant_id          = $withdraw->merchant_id;
            // $company_account->merchant_withdraw_id = $withdraw->id;
            // if($withdraw->status == 'processed'):
            //     $company_account->transaction_id       = $withdraw->transaction_id ? $withdraw->transaction_id : '';

            //         $company_account->account_id       = 1;
            //         $company_account->user_id          = 1;
            // endif;

            // $company_account->created_at            = $withdraw_tbl->created_at;
            // $company_account->updated_at            = $withdraw_tbl->updated_at;

            // $company_account->save();


            // $merchant_account                       = new MerchantAccount();
            // $merchant_account->source               = 'payment_withdraw_by_merchant';
            // $merchant_account->merchant_withdraw_id = $withdraw->id;
            // $merchant_account->details              = __('payment_withdraw_by_merchant');
            // $merchant_account->date                 = $withdraw->date;
            // $merchant_account->type                 = 'expense';
            // $merchant_account->amount               = $withdraw->amount;
            // $merchant_account->merchant_id          = $withdraw->merchant_id;
            // $merchant_account->company_account_id   = $company_account->id;

            // $merchant_account->created_at            = $withdraw_tbl->created_at;
            // $merchant_account->updated_at            = $withdraw_tbl->updated_at;
            // $merchant_account->save();

        // }

        // DB::commit();
        // dd('success');


    // } catch (\Exception $e) {
    //     DB::rollback();
    //     dd('failed');
    // }

    // dd('success');

        // $rejecteds            = MerchantWithdraw::whereIn('status', ['rejected', 'cancelled'])->sum('amount');




        // $total_charge_vat_income                  = MerchantAccount:: whereIn('source', ['delivery_charge', 'parcel_return', 'vat_adjustment'])
        //                                             ->where('type', 'expense')
        //                                             ->sum('amount');

        // $total_charge_vat_expense                 = MerchantAccount::whereIn('source', ['delivery_charge', 'parcel_return', 'vat_adjustment'])
        //                                             ->where('type', 'income')
        //                                             ->sum('amount');

        // $data['total_charge_vat']                  = $total_charge_vat_income - $total_charge_vat_expense;
        // echo $data['total_charge_vat'].'<br>';

        // $total_payable_to_merchant_income         = MerchantAccount::whereIn('source', ['opening_balance','parcel_delivery'])
        //                                             ->where('type', 'income')
        //                                             ->sum('amount');

        // $total_payable_to_merchant_expense        = MerchantAccount::whereIn('source', ['parcel_delivery'])
        //                                             ->where('type', 'expense')
        //                                             ->sum('amount');

        // $data['total_payable_to_merchant']         = $total_payable_to_merchant_income - $total_payable_to_merchant_expense;
        // echo $data['total_payable_to_merchant'].'<br>';



        // $data['total_paid_to_merchant']            = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->sum('amount');

        // $data['total_paid_by_merchant']            = CompanyAccount::where('source', 'delivery_charge_receive_from_merchant')
        //                                             ->where('type', 'income')
        //                                             ->where('merchant_id', '!=', '')
        //                                             ->sum('amount');

        // $data['current_payable']                   = abs($data['total_payable_to_merchant']) + $data['total_paid_by_merchant'] - $data['total_paid_to_merchant'] -  $data['total_charge_vat'];

        // echo $data['current_payable'].'<br>';

        // // $data['total_paid_by_merchant']            = CompanyAccount::where('source', 'delivery_charge_receive_from_merchant')
        // //                                             ->where('type', 'income')
        // //                                             ->where('merchant_id', '!=', '')
        // //                                             ->sum('amount');

        // //                                             dd($data['total_paid_by_merchant']);


        // $data['dfdsfsd']            = MerchantWithdraw::whereIn('status', ['rejected', 'cancelled'])->sum('amount');
        // echo $data['dfdsfsd'].'<br>';

        // $merchants =  Merchant::all();
        // $balance = 0;
        // foreach($merchants as $merchant){
        //     $balance += $merchant->balance($merchant->id);
        // }
        // dd($balance);

        // $deliverymans =  DeliveryMan::all();
        // $balance = 0;
        // foreach($deliverymans as $deliveryman){
        //     $balance += $deliveryman->balance($deliveryman->id);
        // }
        // dd($balance);

        // $total_delivery_charge_income             = DeliveryManAccount::whereIn('source', ['pickup_commission','parcel_delivery', 'parcel_return'])
        //                                             ->where('type', 'income')
        //                                             ->sum('amount');

        // $total_delivery_charge_expense             = DeliveryManAccount::whereIn('source', ['pickup_commission','parcel_delivery', 'parcel_return'])
        //                                             ->where('type', 'expense')
        //                                             ->sum('amount');

        // $data['total_delivery_charge']             = $total_delivery_charge_expense - $total_delivery_charge_income;

        // dd($data['total_delivery_charge']);

        // $parcels = Parcel::whereIn('status', ['delivered', 'delivered-and-verified', 'returned-to-merchant'])->get();
        // $cod = 0;
        // $delivery_charge = 0;
        // foreach($parcels as $parcel){

        //     if($parcel->status == 'delivered'){
        //         $cod += $parcel->price;
        //         $delivery_charge += $parcel->delivery_fee;

        //     }elseif($parcel->status == 'returned-to-merchant'){
        //         $delivery_charge += $parcel->return_fee;

        //     }

        // }
        // echo $cod.'<br>';
        // echo $delivery_charge.'<br>';
        // exit();

        // $data['total_paid_by_delivery_man']        = DeliveryManAccount::where('source','cash_given_to_staff')
        //                                             ->where('type','expense')
        //                                             ->sum('amount');
        //                                             dd($data['total_paid_by_delivery_man']);

        // $total_payable_to_merchant_income         = MerchantAccount::where('type', 'income')
        //                                             ->sum('amount');

        // $total_payable_to_merchant_expense        = MerchantAccount::where('type', 'expense')
        //                                             ->sum('amount');

        // $data['total_payable_to_merchant']         = $total_payable_to_merchant_income - $total_payable_to_merchant_expense;
        // dd($data['total_payable_to_merchant']);

        // dd($data['total_payable_to_merchant']);

        // $total_charge_vat_income                  = MerchantAccount::whereIn('source', ['delivery_charge', 'parcel_return', 'vat_adjustment'])
        //                                             ->where('type', 'expense')
        //                                             ->sum('amount');

        // $total_charge_vat_expense                 = MerchantAccount::whereIn('source', ['delivery_charge', 'parcel_return', 'vat_adjustment'])
        //                                             ->where('type', 'income')
        //                                             ->sum('amount');

        // $data['total_charge_vat']                  = $total_charge_vat_income - $total_charge_vat_expense;

        // dd($data['total_charge_vat']);

        // $data['total_paid_to_merchant']            = MerchantWithdraw::where('status', 'processed')
        //                                             ->sum('amount');

        //                                             dd($data['total_paid_to_merchant']);


        // $parcels = Parcel::whereIn('status', ['delivered', 'delivered-and-verified', 'returned-to-merchant'])->get();

        // $total_charge = 0;
        // foreach($parcels as $parcel){

        //     if($parcel->status == 'delivered'){

        //         $vat                   = $parcel->vat ?? 0.00;
        //         $total_delivery_charge = $parcel->charge + $parcel->price / 100 * $parcel->cod_charge;
        //         $total_vat             = $total_delivery_charge / 100 * $vat;
        //         $total_charge += $total_delivery_charge;
        //         $total_charge += $total_vat;

        //     }elseif($parcel->status == 'returned-to-merchant'){

        //         $vat                   = $parcel->vat ?? 0.00;
        //         $total_vat             = $parcel->charge / 100 * $vat;

        //         $total_charge += $parcel->charge;
        //         $total_charge += $total_vat;


        //     }

        // }
        // echo $total_charge.'<br>';
        // exit();




        // $paid_by_deliveryman = CompanyAccount::where('delivery_man_id', '!=', '')->where('type', 'income')->get()->dd();
        // dd($paid_by_deliveryman);
        // exit();

    }

    public function oldBalance()
    {
         DB::beginTransaction();
         try {

//            getting merchant all old records here
            //1st part
//
//            $merchants = Merchant::all();
//
//            foreach ($merchants as $merchant)
//            {
//                $total_income   = MerchantAccount::where('merchant_id', $merchant->id)->where('type','income')->sum('amount');
//                $total_expense  = MerchantAccount::where('merchant_id', $merchant->id)->where('type','expense')->sum('amount');
//                $balance        = $total_income - $total_expense;
//
//                // merchant account entry
//                $merchant_account_expense               = new MerchantAccount();
//                $merchant_account_expense->merchant_id  = $merchant->id;
//                $merchant_account_expense->date         = date('Y-m-d');
//                $merchant_account_expense->source       = 'previous_balance_adjust';
//                $merchant_account_expense->amount       = $balance;
//                $merchant_account_expense->type         = 'expense';
//                $merchant_account_expense->details      = 'report_adjust_purpose';
//                $merchant_account_expense->save();
//                // merchant account entry
//                $merchant_account_income               = new MerchantAccount();
//                $merchant_account_income->merchant_id  = $merchant->id;
//                $merchant_account_income->date         = date('Y-m-d');
//                $merchant_account_income->source       = 'previous_balance';
//                $merchant_account_income->amount       = $balance;
//                $merchant_account_income->type         = 'income';
//                $merchant_account_income->details      = 'merchant_all_unpaid_amount';
//                $merchant_account_income->save();
//
//            }

            // 2nd part
        //    $merchants_accounts = MerchantAccount::whereIn('source',['cash_given_for_delivery_charge','parcel_return','vat_adjustment','opening_balance'])->get();

        //    foreach ($merchants_accounts as $merchant_acc){
        //        $merchant_acc->is_paid = true;
        //        $merchant_acc->save();
        //    }

        //    $parcels = Parcel::where(function ($query) {
        //        $query->whereIn('status',['delivered','delivered-and-verified']);
        //    })->get();

        //    foreach ($parcels as $parcel){
        //        $parcel->is_paid = true;
        //        $parcel->save();
        //    }


//            //3rd part
//            $merchants = Merchant::where('vat', null)->orWhere('vat', '')->get();
//
//            foreach ($merchants as $merchant){
//                $merchant->vat = 0.00;
//                $merchant->save();
//            }

            // dd('csdc');


             DB::commit();
             dd('success');
         } catch (\Exception $e) {
              DB::rollback();
              dd('failed');
         }
    }



    public function mergeUpdate()
    {
	\Artisan::call('database:backup');
    }
}
