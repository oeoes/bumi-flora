<?php

namespace App\Http\Controllers;

use App\Model\Activity\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public static function asset_item($dept)
    {
        return DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->where(['stocks.dept' => $dept, 'items.deleted_at' => NULL])
            ->select(DB::raw('sum(stocks.amount) as total_item'), DB::raw('sum(stocks.amount * items.main_cost) as asset'))
            ->get();
    }
    public function index()
    {
        $gudang = self::asset_item('gudang');
        $utama = self::asset_item('utama');
        $ecommerce = self::asset_item('ecommerce');

        $employee = DB::table('model_has_roles')
            ->join('users', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where(['roles.name' => 'cashier', 'users.role' => 'user'])
            ->select('users.name', 'users.id')->get();

        $transactions = DB::table('transactions')
            ->join('users', 'users.id', 'transactions.user_id')
            ->groupBy('transactions.transaction_time')
            ->select('transactions.transaction_time')->where('transactions.deleted_at', NULL)->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();
        $omset = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->selectRaw('sum(items.price * transactions.qty) as outcome')
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        return view('pages.dashboard', ['employee' => $employee, 'transactions' => $transactions, 'omset' => $omset, 'gudang' => $gudang, 'utama' => $utama, 'ecommerce' => $ecommerce]);
    }

    public function demand()
    {
        $demand = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->select('items.name', 'transactions.item_id')
            ->selectRaw('sum(transactions.qty) as quantity')
            ->groupBy('items.name', 'transactions.qty', 'transactions.item_id')
            ->orderBy('transactions.qty')
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        return response()->json(['status' => true, 'message' => 'product demand', 'data' => $demand]);
    }

    public function cashier_performance()
    {
        $transactions = DB::table('transactions')
            ->groupBy('transaction_number')
            ->select('transaction_number')
            ->where('user_id', request('cashier'))
            ->where(['dept' => 'utama', 'deleted_at' => NULL])->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        $omset = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->groupBy('transactions.transaction_number', 'items.id')
            ->select(DB::raw('sum(transactions.qty) * items.price as price'), 'transactions.transaction_number', 'transactions.tax', 'transactions.additional_fee', DB::raw('(sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer)) as discount'))
            ->where(['transactions.user_id' => request('cashier'), 'transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        $omset_trx = [];
        foreach ($omset as $om) {
            !in_array($om->transaction_number, $omset_trx) ? array_push($omset_trx, $om->transaction_number) : '';
        }

        return response()->json(['status' => true, 'message' => 'cashier performance', 'data' => [$transactions, $omset, $omset_trx]]);
    }

    public function accumulation()
    {
        $transactions = DB::table('transactions')
            ->groupBy('transaction_number')
            ->select('transaction_number')
            ->where(['transactions.deleted_at' => NULL])->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();

        // $omset = DB::table('items')
        //     ->join('transactions', 'items.id', '=', 'transactions.item_id')
        //     ->join('units', 'units.id', '=', 'items.unit_id')
        //     ->join('categories', 'categories.id', '=', 'items.category_id')
        //     ->select('items.id', DB::raw('((sum(transactions.qty) * items.price) - (sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer))) as outcome'))
        //     ->groupBy('transactions.item_id')
        //     ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL])
        //     ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        $omset = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->groupBy('transactions.transaction_number', 'items.id')
            ->select(DB::raw('sum(transactions.qty) * items.price as price'), 'transactions.transaction_number', 'transactions.tax', 'transactions.additional_fee', DB::raw('(sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer)) as discount'))
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        $omset_trx = [];
        foreach ($omset as $om) {
            !in_array($om->transaction_number, $omset_trx) ? array_push($omset_trx, $om->transaction_number) : '';
        }

        return response()->json(['status' => true, 'message' => 'Over all', 'data' => [$transactions, $omset, $omset_trx]]);
    }
}
