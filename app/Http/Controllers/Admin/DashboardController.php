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
        $parcels = Parcel::whereRaw("date(date) = '$today' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();


        $delivered_parcels = Parcel::whereRaw("date(delivered_date) = '$today' ")
        ->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where(
                function ($q) {
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhereNull('pickup_hub_id')
                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                }
            );
        })->latest()->get();

        $create_parcels = Parcel::whereRaw("date(created_at) = '$today' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

        $partial_delivery = $delivered_parcels->where('is_partially_delivered', true)->count();


        $data['totalParcelDelivered'] = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count() + $partial_delivery;


        $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

        for ($i = 0; $i <= 11; $i++) {

            $j  = $i * 2;

            $j  = str_pad($j, 2, "0", STR_PAD_LEFT);
            $in = $j + 1;
            if ($in < 10) {
                $in = str_pad($in, 2, "0", STR_PAD_LEFT);
            }

            //date range parcels
            $start  = $today . " " . $j . ':00:00';
            $end    = $today . " " . $in . ':59:59';

            $create_merchant_parcels             = $create_parcels->whereBetween('created_at', [$start, $end]);
            $merchant_parcels             = $parcels->whereBetween('date', [$start, $end]);
            $merchant_parcels_delivered             = $delivered_parcels->whereBetween('delivered_date', [$start, $end]);


            // count
            $data['totalParcel'][]        = $totalParcel         = $create_merchant_parcels->count();
            $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
            $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
            $data['partially_delivered'][] = $partially_delivered = $merchant_parcels_delivered->where('is_partially_delivered', true)->count();
            $data['delivered'][]          = $delivered           = $merchant_parcels_delivered->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['processing'][]         = $merchant_parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        }


        $data['totalParcels']       = $create_parcels->count();
        $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
        $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
        $data['totalDelivered']     = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $delivered_parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing'] = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->where('date', $today)->sum('amount');
        $profits = $this->profits($today, $today);

        $recent_parcels = Parcel::with('merchant')->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where('hub_id', \Sentinel::getUser()->hub_id)
                ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                ->orWhereNull('pickup_hub_id')
                ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
        })
            ->latest()->take(5)->get();

        $withdraws = MerchantWithdraw::with('merchant')->where('status', 'processed')->take(7)->latest()->get();

        $counts = $this->get_counts($parcels, $create_parcels, $delivered_parcels);

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $notices = Notice::where('status', true)->where('staff', true)->where('start_time', '<=', $current_time)->where('end_time', '>=', $current_time)->get();


        return view('dashboard', compact('data', 'counts', 'withdraws', 'profits', 'recent_parcels', 'notices'));
    }

    public function get_counts($parcels, $create_parcels, $delivered_parcels)
    {
        $delivered_cod              = $parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->sum('price');
        $data['total_cod']          = number_format($delivered_cod, 2) . ' ' . __('tk');
        $data['parcels_count']      = $create_parcels->count();
        $data['processing_count']   = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        $data['cancelled_count']    = $parcels->where('status', 'cancel')->count();
        $data['deleted_count']      = $parcels->where('status', 'deleted')->count();
        $data['partial_delivered_count'] = $delivered_parcels->where('is_partially_delivered', true)->count();
        $data['returned_count']     = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['delivery_count']      = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        return $data;
    }

    public function report(Request $request)
    {
        $report_type = $request->report_type;
        $now = Carbon::now();
        if ($report_type == 'today') :
            $start           = date('Y-m-d');
            $end             = date('Y-m-d');
        elseif ($report_type == 'yesterday') :
            $start = date('Y-m-d', strtotime('-1 day'));
            $end = date('Y-m-d', strtotime('-1 day'));
        elseif ($report_type == 'this_week') :
            $start = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY)));
            $end = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY)));
        elseif ($report_type == 'last_week') :
            $start = date('Y-m-d', strtotime($now->startOfWeek(Carbon::SATURDAY) . ('-1 week')));
            $end = date('Y-m-d', strtotime($now->endOfWeek(Carbon::FRIDAY) . ('-1 week')));
        elseif ($report_type == 'this_month') :
            $start = date('Y-m-' . '01');
            $end = date('Y-m-t');
        elseif ($report_type == 'last_month') :
            $start  = date('Y-m-d', strtotime("first day of -1 month"));
            $end    = date('Y-m-d', strtotime("last day of -1 month"));
        elseif ($report_type == 'last_3_month') :
            $start    = date('Y-m-d', strtotime('-3 month'));
            $end      = date('Y-m-t', strtotime('first day of -1 month'));
        elseif ($report_type == 'last_6_month') :
            $start    = date('Y-m-d', strtotime('-6 month'));
            //$end      = date('Y-m-t', strtotime('first day of -1 month'));
            $end      = date('Y-m-t');
        elseif ($report_type == 'this_year') :
            $start = date('Y-' . '01-01');
            $end = date('Y-' . '12-31');

        elseif ($report_type == 'last_year') :
            $start = date('Y-' . '01-01', strtotime('-1 year'));
            $end = date('Y-' . '12-31', strtotime('-1 year'));

        elseif ($report_type == 'lifetime') :
                $start = date('2010-' . '01-01');
                $end = date('Y-' . '12-31');

        endif;
        if ($report_type == 'lifetime'):
            $parcels = Parcel::whereRaw("date(date) between '$start' and '$end' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

            $delivered_parcels = Parcel::whereRaw("date(delivered_date) between '$start' and '$end' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

        $create_parcels = Parcel::whereRaw("date(created_at) between '$start' and '$end' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

        $partial_delivery = $delivered_parcels->where('is_partially_delivered', true)->count();


        $data['totalParcelDelivered'] = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count() + $partial_delivery;


        $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

        for ($i = 0; $i <= 11; $i++) {

            $j  = $i * 2;

            $j  = str_pad($j, 2, "0", STR_PAD_LEFT);
            $in = $j + 1;
            if ($in < 10) {
                $in = str_pad($in, 2, "0", STR_PAD_LEFT);
            }

            //date range parcels
            $start_dt  = $start . " " . $j . ':00:00';
            $end_dt    = $end . " " . $in . ':59:59';

            $create_merchant_parcels             = $create_parcels->whereBetween('created_at', [$start_dt, $end_dt]);
            $merchant_parcels             = $parcels->whereBetween('date', [$start_dt, $end_dt]);
            $merchant_parcels_delivered             = $delivered_parcels->whereBetween('delivered_date', [$start_dt, $start_dt]);;


            // count
            $data['totalParcel'][]        = $totalParcel         = $create_merchant_parcels->count();
            $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
            $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
            $data['partially_delivered'][] = $partially_delivered = $merchant_parcels_delivered->where('is_partially_delivered', true)->count();
            $data['delivered'][]          = $delivered           = $merchant_parcels_delivered->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['processing'][]         = $merchant_parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        }


        $data['totalParcels']       = $create_parcels->count();
        $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
        $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
        $data['totalDelivered']     = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $delivered_parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing'] = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->whereBetween('date', [$start, $end])->sum('amount');
        $profits = $this->profits($start, $end);

        $recent_parcels = Parcel::with('merchant')->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where('hub_id', \Sentinel::getUser()->hub_id)
                ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                ->orWhereNull('pickup_hub_id')
                ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
        })
            ->latest()->take(5)->get();

        $withdraws = MerchantWithdraw::with('merchant')->where('status', 'processed')->take(7)->latest()->get();

        $counts = $this->get_counts($parcels, $create_parcels, $delivered_parcels);

        else:
            $parcels = Parcel::whereRaw("date(date) between '$start' and '$end' ")
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(
                        function ($q) {
                            $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                ->orWhereNull('pickup_hub_id')
                                ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                        }
                    );
                })->latest()->get();

            $delivered_parcels = Parcel::whereRaw("date(delivered_date) between '$start' and '$end' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

            $create_parcels = Parcel::whereRaw("date(created_at) between '$start' and '$end' ")
                ->when(!hasPermission('read_all_parcel'), function ($query) {
                    $query->where(
                        function ($q) {
                            $q->where('hub_id', \Sentinel::getUser()->hub_id)
                                ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                                ->orWhereNull('pickup_hub_id')
                                ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                        }
                    );
                })->latest()->get();

            $partial_delivery = $delivered_parcels->where('is_partially_delivered', true)->count();


            $data['totalParcelDelivered'] = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count() + $partial_delivery;


            $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

            for ($i = 0; $i <= 11; $i++) {

                $j  = $i * 2;

                $j  = str_pad($j, 2, "0", STR_PAD_LEFT);
                $in = $j + 1;
                if ($in < 10) {
                    $in = str_pad($in, 2, "0", STR_PAD_LEFT);
                }

                //date range parcels
                $start_dt  = $start . " " . $j . ':00:00';
                $end_dt    = $end . " " . $in . ':59:59';

                $create_merchant_parcels             = $create_parcels->whereBetween('created_at', [$start_dt, $end_dt]);
                $merchant_parcels             = $parcels->whereBetween('date', [$start_dt, $end_dt]);
                $merchant_parcels_delivered             = $delivered_parcels->whereBetween('delivered_date', [$start_dt, $end_dt]);


                // count
                $data['totalParcel'][]        = $totalParcel         = $create_merchant_parcels->count();
                $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
                $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
                $data['partially_delivered'][] = $partially_delivered = $merchant_parcels_delivered->where('is_partially_delivered', true)->count();
                $data['delivered'][]          = $delivered           = $merchant_parcels_delivered->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
                $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $data['processing'][]         = $merchant_parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
            }


            $data['totalParcels']       = $create_parcels->count();
            $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
            $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
            $data['totalDelivered']     = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['totalPartialDelivered'] = $delivered_parcels->where('is_partially_delivered', true)->count();
            $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['totalProcessing'] = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();

            $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->whereBetween('date', [$start, $end])->sum('amount');
            $profits = $this->profits($start, $end);

            $recent_parcels = Parcel::with('merchant')->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where('hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhereNull('pickup_hub_id')
                    ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
            })
                ->latest()->take(5)->get();

            $withdraws = MerchantWithdraw::with('merchant')->where('status', 'processed')->take(7)->latest()->get();

            $counts = $this->get_counts($parcels, $create_parcels, $delivered_parcels);



        endif;

        return view('admin.dashboard.report', compact('data', 'counts', 'profits'));
    }

    public function customDateRange(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start_date));
        $end = date('Y-m-d', strtotime($request->end_date));
        $parcels = Parcel::whereRaw("date(date) between '$start' and '$end' ")
        ->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where(
                function ($q) {
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhereNull('pickup_hub_id')
                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                }
            );
        })->latest()->get();

        $delivered_parcels = Parcel::whereRaw("date(delivered_date) between '$start' and '$end' ")
        ->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where(
                function ($q) {
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhereNull('pickup_hub_id')
                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                }
            );
        })->latest()->get();

        $create_parcels = Parcel::whereRaw("date(created_at) between '$start' and '$end' ")
            ->when(!hasPermission('read_all_parcel'), function ($query) {
                $query->where(
                    function ($q) {
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    }
                );
            })->latest()->get();

        $partial_delivery = $delivered_parcels->where('is_partially_delivered', true)->count();


        $data['totalParcelDelivered'] = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count() + $partial_delivery;


        $data['dates']              = ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"];

        for ($i = 0; $i <= 11; $i++) {

            $j  = $i * 2;

            $j  = str_pad($j, 2, "0", STR_PAD_LEFT);
            $in = $j + 1;
            if ($in < 10) {
                $in = str_pad($in, 2, "0", STR_PAD_LEFT);
            }

            //date range parcels
            $start_dt  = $start . " " . $j . ':00:00';
            $end_dt    = $end . " " . $in . ':59:59';

            $create_merchant_parcels             = $create_parcels->whereBetween('created_at', [$start_dt, $end_dt]);
            $merchant_parcels             = $parcels->whereBetween('date', [$start_dt, $end_dt]);
            $merchant_parcels_delivered             = $delivered_parcels->whereBetween('delivered_date', [$start_dt, $end_dt]);


            // count
            $data['totalParcel'][]        = $totalParcel         = $create_merchant_parcels->count();
            $data['cancelled'][]          = $cancelled           = $merchant_parcels->where('status', 'cancel')->count();
            $data['deleted'][]            = $deleted             = $merchant_parcels->where('status', 'deleted')->count();
            $data['partially_delivered'][] = $partially_delivered = $merchant_parcels_delivered->where('is_partially_delivered', true)->count();
            $data['delivered'][]          = $delivered           = $merchant_parcels_delivered->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
            $data['returned'][]           = $returned            = $merchant_parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
            $data['processing'][]         = $merchant_parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();
        }


        $data['totalParcels']       = $create_parcels->count();
        $data['totalCancelled']     = $parcels->where('status', 'cancel')->count();
        $data['totalDeleted']       = $parcels->where('status', 'deleted')->count();
        $data['totalDelivered']     = $delivered_parcels->whereIn('status', ['delivered', 'delivered-and-verified'])->count();
        $data['totalPartialDelivered'] = $delivered_parcels->where('is_partially_delivered', true)->count();
        $data['totalReturned']      = $parcels->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
        $data['totalProcessing'] = $parcels->whereNotIn('status', ['delivered', 'delivered-and-verified', 'cancel', 'returned-to-merchant', 'deleted'])->where('is_partially_delivered', false)->count();

        $data['merchant_total_withdraw'] = MerchantWithdraw::whereIn('status', ['processed', 'pending'])->whereBetween('date', [$start, $end])->sum('amount');
        $profits = $this->profits($start, $end);

        $recent_parcels = Parcel::with('merchant')->when(!hasPermission('read_all_parcel'), function ($query) {
            $query->where('hub_id', \Sentinel::getUser()->hub_id)
                ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                ->orWhereNull('pickup_hub_id')
                ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
        })
            ->latest()->take(5)->get();

        $withdraws = MerchantWithdraw::with('merchant')->where('status', 'processed')->take(7)->latest()->get();

        $counts = $this->get_counts($parcels, $create_parcels, $delivered_parcels);

            return view('admin.dashboard.report', compact('data', 'counts', 'profits'));
    }

    public function profits($start, $end)
    {

        $total_vat_income                         = GovtVat::whereBetween('date', [$start, $end])
        ->where('type', 'income')
        ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery', 'parcel_return'])
        ->sum('amount');

            $total_vat_expense                        = GovtVat::whereBetween('date', [$start, $end])
                ->where('type', 'expense')
                ->where('parcel_id', '!=', '')->whereIn('source', ['parcel_delivery', 'parcel_return'])
                ->sum('amount');
            $return_income                            = MerchantAccount::whereBetween('date', [$start, $end])
                ->where('type', 'income')
                ->where(function ($query) {
                    $query->where('source', 'parcel_return')
                        ->orWhere(function ($query) {
                            $query->where('source', 'vat_adjustment')
                                ->whereIn('details', ['govt_vat_for_parcel_return', 'govt_vat_for_parcel_return_reversed']);
                        });
                })
                ->sum('amount');
            $return_expense                            = MerchantAccount::whereBetween('date', [$start, $end])
                ->where('type', 'expense')
                ->where(function ($query) {
                    $query->where('source', 'parcel_return')
                        ->orWhere(function ($query) {
                            $query->where('source', 'vat_adjustment')
                                ->whereIn('details', ['govt_vat_for_parcel_return', 'govt_vat_for_parcel_return_reversed']);
                        });
                })
                ->sum('amount');

            $data['total_parcel_return_charge']       = $return_expense - $return_income;


            $data['total_vat']                        = $total_vat_income - $total_vat_expense;


            $total_charge_vat                         =  Parcel::whereBetween('date', [$start, $end])
                ->where(function ($query) {
                    $query->where('is_partially_delivered', true)
                        ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                })
                ->sum('total_delivery_charge');

            $data['total_charge_vat']                 =  $total_charge_vat + $data['total_parcel_return_charge'];


            $total_delivery_charge_income             = DeliveryManAccount::whereBetween('date', [$start, $end])
                ->whereIn('source', ['pickup_commission', 'parcel_delivery', 'parcel_return'])
                ->where('type', 'income')
                ->sum('amount');

            $total_delivery_charge_expense            = DeliveryManAccount::whereBetween('date', [$start, $end])
                ->whereIn('source', ['pickup_commission', 'parcel_delivery', 'parcel_return'])
                ->where('type', 'expense')
                ->sum('amount');

            $data['total_delivery_charge']            = $total_delivery_charge_expense - $total_delivery_charge_income;

            $data['total_fragile_charge']             = Parcel::whereBetween('date', [$start, $end])
                ->where(function ($query) {
                    $query->where('is_partially_delivered', true)
                        ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                })
                ->sum('fragile_charge');
            $data['total_packaging_charge']           = Parcel::whereBetween('date', [$start, $end])
                ->where(function ($query) {
                    $query->where('is_partially_delivered', true)
                        ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                })
                ->sum('packaging_charge');

            $data['total_profit']                     = abs($data['total_charge_vat']) - $data['total_delivery_charge'] -  $data['total_vat'] + $data['total_fragile_charge'] + $data['total_packaging_charge'];


            $data['total_payable_to_merchant']         = Parcel::whereBetween('date', [$start, $end])
                ->where(function ($query) {
                    $query->where('is_partially_delivered', true)
                        ->orWhereIn('status', ['delivered', 'delivered-and-verified']);
                })
                ->sum('price');

            $data['total_paid_to_merchant']           = MerchantWithdraw::whereBetween('date', [$start, $end])
                ->whereIn('status', ['processed', 'pending', 'approved'])
                ->sum('amount');

            $data['pending_payments']                 = MerchantWithdraw::whereBetween('date', [$start, $end])
                ->whereIn('status', ['pending', 'approved'])
                ->sum('amount');

            $data['total_paid_by_merchant']           = CompanyAccount::whereBetween('date', [$start, $end])
                ->where('source', 'delivery_charge_receive_from_merchant')
                ->where('type', 'income')
                ->where('merchant_id', '!=', '')
                ->sum('amount');

            $data['current_payable']                  = abs($data['total_payable_to_merchant']) + $data['total_paid_by_merchant'] - $data['total_paid_to_merchant'] -  $data['total_charge_vat'];

            $data['total_cash_on_delivery']           =  $data['total_payable_to_merchant'];

            $data['total_paid_by_delivery_man']       = DeliveryManAccount::whereBetween('date', [$start, $end])
                ->where('delivery_man_id', '!=', '')
                ->where('source', 'cash_given_to_staff')
                ->where('type', 'expense')
                ->sum('amount');

            $data['total_expense_from_account']        = CompanyAccount::whereBetween('date', [$start, $end])
                ->where('type', 'expense')
                ->where('create_type', 'user_defined')
                ->sum('amount');

            $data['total_new_merchant'] = Merchant::whereRaw("date(created_at) between '$start' and '$end' ")->count();

            $start = $start . ' ' . '00:00:00';
            $end =  $end . ' ' . '23:59:59';

            $data['total_bank_opening_balance']        = Account::whereRaw("date(created_at) between '$start' and  '$end' ")->sum('balance');

            return $data;
    }

    public function getLastDateOfMonth($month)
    {
        $date = date('Y') . '-' . $month . '-01';  //make date of month
        return date('t', strtotime($date));
    }

    public function oldMigration()
    {


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
