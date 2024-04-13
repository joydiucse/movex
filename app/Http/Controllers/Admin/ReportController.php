<?php

namespace App\Http\Controllers\Admin;
use App\Exports\AgentSalesReport;
use App\Exports\AccountSummaryReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\AccountRequest;
use App\Http\Requests\Admin\Reports\ParcelRequest;
use App\Models\DeliveryMan;
use App\Models\Parcel;
use App\Models\ParcelEvent;
use App\Models\User;
use Google\Service\ContainerAnalysis\Details;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Reports\StatementRequest;
use App\Models\Account\CompanyAccount;
use App\Models\Account\GovtVat;
use App\Models\Account\Account;
use App\Models\Account\MerchantAccount;
use App\Models\Account\MerchantWithdraw;
use App\Models\Account\FundTransfer;
use App\Models\Account\DeliveryManAccount;
use App\Models\Merchant;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Repositories\Interfaces\Admin\ReportInterface;
use App\Repositories\Interfaces\DeliveryManInterface;
use App\Repositories\Interfaces\UserInterface;
use Faker\Provider\ar_EG\Company;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    protected $merchants;
    protected $delivery_man;
    protected $reports;
    protected $users;

    public function __construct(MerchantInterface $merchants, DeliveryManInterface $delivery_man, ReportInterface $reports, UserInterface $users)
    {
        $this->merchants = $merchants;
        $this->delivery_man = $delivery_man;
        $this->reports = $reports;
        $this->users = $users;
    }

    public function parcels()
    {
        return view('admin.reports.parcel');
    }

    public function parcelSearch(ParcelRequest $request)
    {
        $data = $this->reports->parcelSearch($request);

        $date['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $date['end_date']   = date('Y-m-d', strtotime($request->end_date));
        return view('admin.reports.parcel', compact('date', 'data'));
    }

    public function incomeExpense()
    {
        $users = $this->users->all()->where('user_type', 'staff');
        $type = '';
        return view('admin.reports.income-expense', compact('users', 'type'));
    }

    public function incomeExpenseSearch(StatementRequest $request)
    {
        if ($request->report_type == "statement") {

            $account = CompanyAccount::query();
            $account->where('create_type', 'user_defined');
            $account->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            $account->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            $account->orderByDesc('id');
            if (!blank($request->user)):
                $account->where('user_id', $request->user);
            endif;
            if (!blank($request->type)):
                $account->where('type', $request->type);
            endif;
            if (!blank($request->account)):
                $account->where('account_id', $request->account);
            endif;

            $accounts = $account->paginate(\Config::get('greenx.paginate'));

            //common
            $transaction = CompanyAccount::query();
            $transaction->where('create_type', 'user_defined');
            $transaction->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            $transaction->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            $transaction->orderByDesc('id');
            if (!blank($request->user)):
                $transaction->where('user_id', $request->user);
            endif;
            if (!blank($request->account)):
                $transaction->where('account_id', $request->account);
            endif;
            //

            if ($request->type != ''):
                if (!blank($request->type)):
                    $transaction->where('type', $request->type);
                endif;

                $grand_total = $transaction->sum('amount');
            else:
                $income = $transaction->where('type', 'income')->sum('amount');

                $expense = CompanyAccount::query();
                $expense->where('create_type', 'user_defined');
                $expense->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $expense->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $expense->orderByDesc('id');
                if (!blank($request->user)):
                    $expense->where('user_id', $request->user);
                endif;
                $expense->where('type', 'expense');
                if (!blank($request->account)):
                    $expense->where('account_id', $request->account);
                endif;

                $expenses = $expense->sum('amount');

                $grand_total = $income - $expenses;
            endif;

            if (blank($accounts)) {
                return redirect()->route('admin.income.expense')->with('danger', __('no_record_found'));
            }

            $type = $request->type ?? 'income/expense';
            $users = $this->users->all()->where('user_type', 'staff');
            return view('admin.reports.income-expense', compact('accounts', 'type', 'users', 'grand_total'));

        } else {

            //common
            $summery_accounts = CompanyAccount::query();
            $summery_accounts->where('create_type', 'user_defined');
            $summery_accounts->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            $summery_accounts->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));

            if (!blank($request->user)):
                $summery_accounts->where('user_id', $request->user);
            endif;
            if (!blank($request->account)):
                $summery_accounts->where('account_id', $request->account);
            endif;
            //
            if ($request->type != ''):
                if (!blank($request->type)):
                    $summery_accounts->where('type', $request->type);
                endif;

                $data['grand_total'] = $summery_accounts->sum('amount');
                if ($request->type == 'income'):
                    $data['income'] = $summery_accounts->sum('amount');
                else:
                    $data['expense'] = $summery_accounts->sum('amount');
                endif;
            else:
                $data['income'] = $summery_accounts->where('type', 'income')->sum('amount');

                $expense = CompanyAccount::query();
                $expense->where('create_type', 'user_defined');
                $expense->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $expense->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));

                if (!blank($request->user)):
                    $expense->where('user_id', $request->user);
                endif;
                $expense->where('type', 'expense');
                if (!blank($request->account)):
                    $expense->where('account_id', $request->account);
                endif;
                $data['expense'] = $expense->sum('amount');
                $data['grand_total'] = $data['income'] - $data['expense'];
            endif;

            $summery_accounts = CompanyAccount::query();
            $summery_accounts->where('create_type', 'user_defined');
            $summery_accounts->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            $summery_accounts->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            $summery_accounts->select('date', 'type', \DB::raw('sum(amount) as amount'));
            $summery_accounts->groupBy(['date', 'type']);
            $summery_accounts->orderByDesc('id');
            if (!blank($request->type)):
                $summery_accounts->where('type', $request->type);
            endif;
            if (!blank($request->user)):
                $summery_accounts->where('user_id', $request->user);
            endif;
            if (!blank($request->account)):
                $summery_accounts->where('account_id', $request->account);
            endif;

            $summery_accounts = $summery_accounts->paginate(\Config::get('greenx.paginate'));

            $type = $request->type ?? 'income/expense';

            $main_datas = [];
            foreach ($summery_accounts as $summery_account) {
                if (array_key_exists($summery_account->date, $main_datas)) {

                    $main_datas[$summery_account->date]['data2'] = $summery_account;

                } else {

                    $main_datas[$summery_account->date]['data1'] = $summery_account;

                }
            }

            $users = $this->users->all()->where('user_type', 'staff');
            return view('admin.reports.income-expense', compact('summery_accounts', 'type', 'data', 'users', 'main_datas'));
        }
    }

    public function transactionHistory()
    {
        return view('admin.reports.transaction_history');
    }

    public function transactionSearch(Request $request)
    {
        if ($request->purpose == "total-charge-with-vat") {
            if ($request->report_type == "statement") {

                $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment'])->where('type', 'income')->sum('amount');
                $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment'])->where('type', 'expense')->sum('amount');

                $data['grand_total'] = $data['income'] - $data['expense'];


                $charges = MerchantAccount::query();
                $charges->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $charges->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $charges->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment']);
                $charges->orderByDesc('id');

                $charges = $charges->paginate(\Config::get('greenx.paginate'));

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();

                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'charges', 'data'));

            } else {

                $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment'])->where('type', 'income')->sum('amount');
                $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment'])->where('type', 'expense')->sum('amount');

                $data['grand_total'] = $data['income'] - $data['expense'];

                $charges = MerchantAccount::query();
                $charges->select('date', 'type', \DB::raw('sum(amount) as amount'));
                $charges->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $charges->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $charges->whereIn('source', ['delivery_charge', 'parcel_return','vat_adjustment']);
                $charges->groupBy(['date', 'type']);
                $charges->orderByDesc('id');

                $summery_charges = $charges->paginate(\Config::get('greenx.paginate'));

                $main_datas = [];
                foreach ($summery_charges as $summery_account) {
                    if (array_key_exists($summery_account->date, $main_datas)) {
                        $main_datas[$summery_account->date]['data2'] = $summery_account;
                    } else {

                        $main_datas[$summery_account->date]['data1'] = $summery_account;
                    }
                }

                return view('admin.reports.transaction_history', compact('summery_charges', 'data', 'main_datas'));
            }

        }
        elseif ($request->purpose == "charge") {
            if ($request->report_type == "statement") {

                $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return'])->where('type', 'income')->sum('amount');
                $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return'])->where('type', 'expense')->sum('amount');


                $data['grand_total'] = $data['income'] - $data['expense'];


                $charges = MerchantAccount::query();
                $charges->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $charges->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $charges->whereIn('source', ['delivery_charge', 'parcel_return']);
                $charges->orderByDesc('id');

                $charges = $charges->paginate(\Config::get('greenx.paginate'));

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();

                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'charges', 'data'));

            } else {

                $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return'])->where('type', 'income')->sum('amount');
                $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->whereIn('source', ['delivery_charge', 'parcel_return'])->where('type', 'expense')->sum('amount');

                $data['grand_total'] = $data['income'] - $data['expense'];

                $charges = MerchantAccount::query();
                $charges->select('date', 'type', \DB::raw('sum(amount) as amount'));
                $charges->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $charges->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $charges->whereIn('source', ['delivery_charge', 'parcel_return']);
                $charges->groupBy(['date', 'type']);
                $charges->orderByDesc('id');

                $summery_charges = $charges->paginate(\Config::get('greenx.paginate'));

                $main_datas = [];
                foreach ($summery_charges as $summery_account) {
                    if (array_key_exists($summery_account->date, $main_datas)) {
                        $main_datas[$summery_account->date]['data2'] = $summery_account;
                    } else {

                        $main_datas[$summery_account->date]['data1'] = $summery_account;
                    }
                }

                return view('admin.reports.transaction_history', compact('summery_charges', 'data', 'main_datas'));
            }

        } elseif ($request->purpose == "vat") {

            if ($request->report_type == "statement") {

                $data['income'] = GovtVat::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->where('type', 'income')->sum('amount');
                $data['expense'] = GovtVat::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->where('type', 'expense')->sum('amount');

                $data['grand_total'] = $data['income'] - $data['expense'];

                $vats = GovtVat::query();
                $vats->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $vats->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $vats->orderByDesc('id');
                $vats = $vats->paginate(\Config::get('greenx.paginate'));
                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();

                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'vats', 'data'));

            } else {

                $data['income'] = GovtVat::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->where('type', 'income')->sum('amount');
                $data['expense'] = GovtVat::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))->where('type', 'expense')->sum('amount');

                $data['grand_total'] = $data['income'] - $data['expense'];

                $vats = GovtVat::query();
                $vats->select('date', 'type', \DB::raw('sum(amount) as amount'));
                $vats->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $vats->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $vats->groupBy(['date', 'type']);
                $vats->orderByDesc('id');
                $summery_vats = $vats->paginate(\Config::get('greenx.paginate'));

                $main_datas = [];
                foreach ($summery_vats as $summery_account) {
                    if (array_key_exists($summery_account->date, $main_datas)) {

                        $main_datas[$summery_account->date]['data2'] = $summery_account;

                    } else {

                        $main_datas[$summery_account->date]['data1'] = $summery_account;

                    }
                }

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();

                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'summery_vats', 'data', 'main_datas'));
            }

        } elseif ($request->purpose == "delivery_man") {

            if ($request->report_type == "statement") {

                if ($request->delivery_man == "") {
                    $data['income'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'income')
                        ->sum('amount');
                    $data['expense'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'expense')
                        ->sum('amount');
                } else {
                    $data['income'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('delivery_man_id', $request->delivery_man)
                        ->where('type', 'income')
                        ->sum('amount');
                    $data['expense'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('delivery_man_id', $request->delivery_man)
                        ->where('type', 'expense')
                        ->sum('amount');
                }
                $data['grand_total'] = $data['income'] - $data['expense'];

                $mer_deli_transactions = DeliveryManAccount::query();
                $mer_deli_transactions->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $mer_deli_transactions->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $mer_deli_transactions->orderByDesc('id');

                if ($request->delivery_man != "") {
                    $mer_deli_transactions->where('delivery_man_id', $request->delivery_man);
                }

                $mer_deli_transactions = $mer_deli_transactions->paginate(\Config::get('greenx.paginate'));


                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();
                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'mer_deli_transactions', 'data'));

            } else {

                if ($request->delivery_man == "") {
                    $data['income'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'income')
                        ->sum('amount');
                    $data['expense'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'expense')
                        ->sum('amount');
                } else {
                    $data['income'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('delivery_man_id', $request->delivery_man)
                        ->where('type', 'income')
                        ->sum('amount');
                    $data['expense'] = DeliveryManAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('delivery_man_id', $request->delivery_man)
                        ->where('type', 'expense')
                        ->sum('amount');
                }
                $data['grand_total'] = $data['income'] - $data['expense'];

                $summery_mer_deli_transactions = DeliveryManAccount::query();
                $summery_mer_deli_transactions->select('date', 'type', \DB::raw('sum(amount) as amount'));
                $summery_mer_deli_transactions->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $summery_mer_deli_transactions->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $summery_mer_deli_transactions->groupBy(['date', 'type']);
                $summery_mer_deli_transactions->orderByDesc('id');

                if ($request->delivery_man != "") {
                    $summery_mer_deli_transactions->where('delivery_man_id', $request->delivery_man);
                }

                $summery_mer_deli_transactions = $summery_mer_deli_transactions->paginate(\Config::get('greenx.paginate'));

                $main_datas = [];
                foreach ($summery_mer_deli_transactions as $summery_account) {
                    if (array_key_exists($summery_account->date, $main_datas)) {

                        $main_datas[$summery_account->date]['data2'] = $summery_account;

                    } else {

                        $main_datas[$summery_account->date]['data1'] = $summery_account;

                    }
                }

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();
                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'summery_mer_deli_transactions', 'data', 'main_datas'));
            }

        } elseif ($request->purpose == "merchant") {
            if ($request->report_type == "statement") {

                if ($request->merchant == "") {
                    $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'income')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                    $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'expense')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                } else {
                    $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('merchant_id', $request->merchant)
                        ->where('type', 'income')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                    $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('merchant_id', $request->merchant)
                        ->where('type', 'expense')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                }
                $data['grand_total'] = $data['income'] - $data['expense'];

                $mer_deli_transactions = MerchantAccount::query();
                $mer_deli_transactions->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $mer_deli_transactions->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $mer_deli_transactions->orderByDesc('id');
                $mer_deli_transactions->where('source', '!=', 'paid_parcels_delivery_reverse');

                if ($request->merchant != "") {
                    $mer_deli_transactions->where('merchant_id', $request->merchant);
                }

                $mer_deli_transactions = $mer_deli_transactions->paginate(\Config::get('greenx.paginate'));

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();
                return view('admin.reports.transaction_history', compact('merchants', 'delivery_men', 'mer_deli_transactions', 'data'));

            } else {
                if ($request->merchant == "") {
                    $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'income')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                    $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('type', 'expense')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                } else {
                    $data['income'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('merchant_id', $request->merchant)
                        ->where('type', 'income')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                    $data['expense'] = MerchantAccount::where('date', '>=', date('Y-m-d', strtotime($request->start_date)))
                        ->where('date', '<=', date('Y-m-d', strtotime($request->end_date)))
                        ->where('merchant_id', $request->merchant)
                        ->where('type', 'expense')
                        ->where('source', '!=', 'paid_parcels_delivery_reverse')
                        ->sum('amount');
                }
                $data['grand_total'] = $data['income'] - $data['expense'];

                $summery_mer_deli_transactions = MerchantAccount::query();
                $summery_mer_deli_transactions->select('date', 'type', \DB::raw('sum(amount) as amount'));
                $summery_mer_deli_transactions->where('date', '>=', date('Y-m-d', strtotime($request->start_date)));
                $summery_mer_deli_transactions->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
                $summery_mer_deli_transactions->groupBy(['date', 'type']);
                $summery_mer_deli_transactions->orderByDesc('id');
                $summery_mer_deli_transactions->where('source', '!=', 'paid_parcels_delivery_reverse');

                if ($request->merchant != "") {
                    $summery_mer_deli_transactions->where('merchant_id', $request->merchant);
                }

                $summery_mer_deli_transactions = $summery_mer_deli_transactions->paginate(\Config::get('greenx.paginate'));

                $main_datas = [];
                foreach ($summery_mer_deli_transactions as $summery_account) {
                    if (array_key_exists($summery_account->date, $main_datas)) {

                        $main_datas[$summery_account->date]['data2'] = $summery_account;

                    } else {

                        $main_datas[$summery_account->date]['data1'] = $summery_account;

                    }
                }

                $merchants = $this->merchants->activeAll();
                $delivery_men = $this->delivery_man->activeAll();

                return view('admin.reports.transaction_history', compact('main_datas', 'merchants', 'delivery_men', 'summery_mer_deli_transactions', 'data'));

            }
        }
    }

    public function totalSummery()
    {
        return view('admin.reports.total_summery');
    }

    public function totalSummerySearch(Request $request)
    {
        $data       = $this->reports->totalSummerySearch($request);
        $profits    = $this->reports->profits($request);

        return view('admin.reports.total_summery', compact('data', 'profits'));
    }

    public function merchantReport()
    {
        return view('admin.reports.merchant');
    }

    public function merchantReportSearch(Request $request)
    {

        $date['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $date['end_date']   = date('Y-m-d', strtotime($request->end_date));

        $data['total_parcels']            = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->count();
        $data['delivered']              = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->whereIn('status',['delivered','delivered-and-verified'])
                                                ->count();
        $data['partially-delivered']     = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->where('is_partially_delivered', true)
                                                ->count();

        $data['returned_to_merchant']   = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->where(function ($query){
                                                    $query->where('is_partially_delivered', false)
                                                        ->where('status', 'returned-to-merchant');
                                                })
                                                ->count();
        $data['cancelled']              = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->where('status', 'cancel')
                                                ->count();
        $data['pending-return']        = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered'])->count();
        $data['deleted']               = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->where('status', 'deleted')
                                                ->count();

        $data['processing']            = $data['total_parcels'] - ($data['delivered'] + $data['partially-delivered'] + $data['returned_to_merchant'] + $data['cancelled'] + $data['deleted']);


        $return_income                 = MerchantAccount::when($request->merchant != '', function ($query) use ($request){
                                            $query->where('merchant_id', $request->merchant);
                                        })->where('date', '>=', $date['start_date'])
                                            ->where('date', '<=', $date['end_date'])
                                        ->where('type', 'income')
                                        ->where(function ($query){
                                            $query->where('source','parcel_return')
                                                ->orWhere(function ($query){
                                                    $query->where('source','vat_adjustment')
                                                        ->whereIn('details',['govt_vat_for_parcel_return','govt_vat_for_parcel_return_reversed']);
                                                });
                                        })
                                        ->sum('amount');
        $return_expense               = MerchantAccount::when($request->merchant != '', function ($query) use ($request){
                                            $query->where('merchant_id', $request->merchant);
                                        })->where('date', '>=', $date['start_date'])
                                            ->where('date', '<=', $date['end_date'])
                                        ->where('type', 'expense')
                                        ->where(function ($query){
                                            $query->where('source','parcel_return')
                                                ->orWhere(function ($query){
                                                    $query->where('source','vat_adjustment')
                                                        ->whereIn('details',['govt_vat_for_parcel_return','govt_vat_for_parcel_return_reversed']);
                                                });
                                        })
                                        ->sum('amount');

        $profits['total_parcel_return_charge']= $return_expense - $return_income;

        $total_charge_vat             = Parcel::when($request->merchant != '', function ($query) use ($request){
                                            $query->where('merchant_id', $request->merchant);
                                        })->where('date', '>=', $date['start_date'])
                                        ->where('date', '<=', $date['end_date'])
                                        ->where(function ($query){
                                            $query->where('is_partially_delivered', true)
                                                ->orWhereIn('status',['delivered','delivered-and-verified']);
                                        })
                                        ->sum('total_delivery_charge');

        $profits['total_charge_vat'] = $total_charge_vat + $profits['total_parcel_return_charge'];

        $profits['total_payable_to_merchant'] = Parcel::when($request->merchant != '', function ($query) use ($request){
                                                        $query->where('merchant_id', $request->merchant);
                                                    })->where('date', '>=', $date['start_date'])
                                                    ->where('date', '<=', $date['end_date'])
                                                    ->where(function ($query){
                                                        $query->where('is_partially_delivered', true)
                                                            ->orWhereIn('status',['delivered','delivered-and-verified']);
                                                    })
                                                    ->sum('price');

        $profits['total_paid_to_merchant'] = MerchantWithdraw::when($request->merchant != '', function ($query) use ($request){
                                                        $query->where('merchant_id', $request->merchant);
                                                    })->where('date', '>=', $date['start_date'])
                                                    ->where('date', '<=', $date['end_date'])
                                                    ->whereIn('status', ['processed', 'pending','approved'])
                                                    ->sum('amount');

        $profits['pending_payments']       = MerchantWithdraw::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->whereIn('status', ['pending','approved'])
                                                ->sum('amount');


        $profits['total_paid_by_merchant'] = CompanyAccount::when($request->merchant != '', function ($query) use ($request){
                                                    $query->where('merchant_id', $request->merchant);
                                                })->where('date', '>=', $date['start_date'])
                                                ->where('date', '<=', $date['end_date'])
                                                ->where('source', 'delivery_charge_receive_from_merchant')
                                                ->where('type', 'income')
                                                ->where('merchant_id', '!=', '')
                                                ->sum('amount');

        $profits['current_payable']        = abs($profits['total_payable_to_merchant']) + $profits['total_paid_by_merchant'] - $profits['total_paid_to_merchant'] - $profits['total_charge_vat'];

        return view('admin.reports.merchant' , compact('data', 'date', 'profits'));
    }


    public function agentSalesSummery(Request $request)
    {
        return view('admin.reports.agent-sales-summery');
    }

    public function agentSalesSummerySearch(Request $request)
    {
        $date['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $date['end_date']   = date('Y-m-d', strtotime($request->end_date));
        $sales_agent        = $request->sales_agent;
        $agent_info = User::select('first_name', 'last_name')->find($sales_agent);

        $data['total_parcels']            = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                            ->where('sales_agent_id', $sales_agent)
                                            ->where('parcels.date', '>=', $date['start_date'])
                                            ->where('parcels.date', '<=', $date['end_date'])
                                            ->count();


        $data['delivered']                = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                            ->where('sales_agent_id', $sales_agent)
                                            ->where('parcels.date', '>=', $date['start_date'])
                                            ->where('parcels.date', '<=', $date['end_date'])
                                            ->whereIn('parcels.status',['delivered','delivered-and-verified'])
                                            ->count();

        $data['partially-delivered']     =  Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                            ->where('sales_agent_id', $sales_agent)
                                            ->where('parcels.date', '>=', $date['start_date'])
                                            ->where('parcels.date', '<=', $date['end_date'])
                                            ->where('parcels.is_partially_delivered', true)
                                            ->count();

        $data['returned_to_merchant']   = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                          ->where('sales_agent_id', $sales_agent)
                                          ->where('parcels.date', '>=', $date['start_date'])
                                          ->where('parcels.date', '<=', $date['end_date'])
                                          ->where(function ($query){
                                                $query->where('parcels.is_partially_delivered', false)
                                                    ->where('parcels.status', 'returned-to-merchant');
                                            })
                                            ->count();
        $data['cancelled']              =  Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                          ->where('sales_agent_id', $sales_agent)
                                          ->where('parcels.date', '>=', $date['start_date'])
                                          ->where('parcels.date', '<=', $date['end_date'])
                                          ->where('parcels.status', 'cancel')
                                          ->count();

        $data['pending-return']        = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                        ->where('sales_agent_id', $sales_agent)
                                        ->where('parcels.date', '>=', $date['start_date'])
                                        ->where('parcels.date', '<=', $date['end_date'])
                                        ->whereIn('parcels.status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered'])
                                        ->count();

        $data['deleted']               = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
                                        ->where('sales_agent_id', $sales_agent)
                                        ->where('parcels.date', '>=', $date['start_date'])
                                        ->where('parcels.date', '<=', $date['end_date'])
                                        ->where('parcels.status', 'deleted')
                                        ->count();




        $merchant_list             = Merchant::join('parcels', 'parcels.merchant_id', '=', 'merchants.id')
        ->select('parcels.merchant_id')
        ->where('sales_agent_id', $sales_agent)
        ->where('parcels.date', '>=', $date['start_date'])
        ->where('parcels.date', '<=', $date['end_date'])
        ->groupBy('parcels.merchant_id')
        ->get();

        $details= array();
        foreach($merchant_list as $key => $merchant)
        {
             $merchant_id = $merchant->merchant_id;
             $total_parcel = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->count();
             $merchant_info = Merchant::select('company')->find($merchant_id);
             $parcel['merchant'] =$merchant_info->company;

             $parcel['total_parcels'] =$total_parcel;

             $parcel['delivered'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->whereIn('status',['delivered','delivered-and-verified'])->count();
             $parcel['partially-delivered'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->where('is_partially_delivered', true)->count();
             $parcel['returned_to_merchant'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->where('is_partially_delivered', false)
                                             ->where('status', 'returned-to-merchant')->count();
             $parcel['cancelled'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->where('status', 'cancel')->count();
             $parcel['pending-return'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered'])->count();
             $parcel['deleted'] = Parcel::where('merchant_id', $merchant_id)->where('date', '>=', $date['start_date'])->where('date', '<=', $date['end_date'])->where('status', 'deleted')->count();

             array_push($details, $parcel);



        }

        if ($request->has('download')):
            $file_name = 'agent_sales_'.date('Y-m-d-s').'.xlsx';
            return Excel::download(new AgentSalesReport($date, $data, $agent_info, $details), $file_name);
        endif;

        return view('admin.reports.agent-sales-summery' , compact('data', 'date', 'details', 'agent_info'));


    }


    public function accountSummary()
    {
        return view('admin.reports.account_daily_summary');
    }

    public function SummaryInfo(Request $request)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $summary_info = [];
        $dates = Parcel::selectraw('date(delivered_date) as date')->whereRaw("date(delivered_date) between  '$start_date' and '$end_date' ")->whereIn('status', ['delivered', 'delivered-and-verified', 'partially-delivered'])->groupBy(DB::raw("date(delivered_date)"))->OrderBy('delivered_date', 'asc')->get();
        foreach ($dates as $single_date):
            $date = $single_date->date;
            $parcelEvent = Parcel::select('id as parcel_id')->whereRaw("date(delivered_date)= '$date'")->whereIn('status', ['delivered','delivered-and-verified', 'partially-delivered'])->get()->toArray();
               $parcel_id_array = array();
                foreach ($parcelEvent as $parcel_id):
                    $id = $parcel_id['parcel_id'];
                    array_push($parcel_id_array, $id);
                endforeach;
                if(count($parcel_id_array) > 0):
                $parcel_id_format = implode(",", $parcel_id_array);
                $summary_info['date'][] = date('m/d/Y', strtotime($date));
                $summary_info['parcel'][] = count($parcelEvent);
                $summary_info['returned'][] = Parcel::where('date', $date)->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->count();
                $summary_info['cash_collection'][]  = $cash_collection = Parcel::whereRaw("id in ($parcel_id_format) ")->sum('price');
                $summary_info['cash_from_merchant'][] = $cash_from_merchant = CompanyAccount::whereRaw("date(created_at) = '$date' ")
                    ->where('type', "income")->where('source', 'delivery_charge_receive_from_merchant')
                    ->sum('amount');
                $summary_info['charge'][] = $charge  =Parcel::whereRaw("id in ($parcel_id_format) ")->sum('charge');
                $summary_info['return_charge'][] = $return_charge  =Parcel::where('date', $date)->where('status', 'returned-to-merchant')->where('is_partially_delivered', false)->sum('charge');
                $summary_info['total_charge'][] = $total_charge = $charge + $return_charge;
                $summary_info['payable'][]  = Parcel::whereRaw("id in ($parcel_id_format) ")->sum('payable');
                $pickup_commission = CompanyAccount::whereRaw("parcel_id in ($parcel_id_format) ")->where('details', "parcel_pickup_commission_to_pickup_man")->sum('amount');
                $delivery_commission = CompanyAccount::whereRaw("parcel_id in ($parcel_id_format) ")->where('details', "parcel_delivery_commission_to_delivery_man")->sum('amount');
                $summary_info['rider_commission'][] = $rider_commission = $pickup_commission + $delivery_commission;
                $summary_info['cash_receive_from_rider'][] = CompanyAccount::whereRaw("date(created_at)='$date'")->where('source', "cash_receive_from_delivery_man")->sum('amount');
                $summary_info['payment_request'][] = MerchantWithdraw::whereRaw("date(created_at)='$date'")->sum('amount');
                $summary_info['payment_processed'][] = MerchantWithdraw::whereRaw("date(updated_at)='$date'")->where('status', 'processed')->sum('amount');
                //$summary_info['expense'][]  = $expense  = CompanyAccount::whereRaw("parcel_id in ($parcel_id_format) ")->where('type', "expense")->sum('amount');
                $summary_info['expense'][]  = $expense  = CompanyAccount::whereRaw("date(created_at)='$date'")->where('type', "expense")->where('create_type', 'user_defined')->sum('amount');
                $summary_info['balance'][] = $cash_collection + $cash_from_merchant;
                $summary_info['profit'][] =  $total_charge - ( $rider_commission +  $expense );
                endif;

        endforeach;

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            "summary_info" => $summary_info,
        ];

        if ($request->has('download')):
            $file_name = 'account_summary_'.date('Y-m-d-s').'.xlsx';
            return Excel::download(new AccountSummaryReport($start_date,  $end_date, $summary_info), $file_name);
        endif;

        return view('admin.reports.account_daily_summary', $data);
    }
}
