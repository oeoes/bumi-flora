<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $employee = DB::table('model_has_roles')
                    ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where(['roles.name' => 'cashier', 'users.role' => 'user'])
                    ->select('users.name', 'users.id')->get();
        
        $transactions = DB::table('transactions')
                        ->groupBy('transaction_time')
                        ->select('transaction_time')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        $omset = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->selectRaw('sum(items.price * transactions.qty) as outcome')
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        return view('pages.dashboard', ['employee' => $employee, 'transactions' => $transactions, 'omset' => $omset]);
    }

    public function demand () {
        $demand = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->select('items.name', 'transactions.item_id')
                ->selectRaw('sum(transactions.qty) as quantity')
                ->groupBy('items.name', 'transactions.qty', 'transactions.item_id')
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();

        return response()->json(['status' => true, 'message' => 'product demand', 'data' => $demand]);
    }

    public function cashier_performance () {
        $transactions = DB::table('transactions')
                        ->groupBy('transaction_time')
                        ->select('transaction_time')
                        ->where('transactions.user_id', request('cashier'))
                        ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        $omset = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->selectRaw('sum(items.price * transactions.qty) as outcome')
                ->where('transactions.user_id', request('cashier'))
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();
        return response()->json(['status' => true, 'message' => 'cashier performance', 'data' => [$transactions, $omset]]);
    }

    public function accumulation () {
        $transactions = DB::table('transactions')
                        ->groupBy('transaction_time')
                        ->select('transaction_time')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        $omset = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->selectRaw('sum(items.price * transactions.qty) as outcome')
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->get();
        
        return response()->json(['status' => true, 'message' => 'Over all', 'data' => [$transactions, $omset]]);
    }
}
