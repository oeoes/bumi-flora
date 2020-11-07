<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\PaymentMethod;
use App\Model\MasterData\PaymentType;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store_payment_method (Request $request) {
        PaymentMethod::create(['method_name' => $request->method_name]);

        session()->flash('message', 'Yeay! Peyment method berhasil ditambahkan.');
        return back();
    }

    public function store_payment_type (Request $request) {
        PaymentType::create([
            'payment_method_id' => $request->payment_method_id,
            'type_name' => $request->type_name,
        ]);

        session()->flash('message', 'Yeay! Payment type berhasil ditambahkan.');
        return back();
    }

    public function update_payment_method (Request $request, $payment_method_id) {
        $pm = PaymentMethod::find($payment_method_id);
        $pm->update(['method_name' => $request->method_name]);

        session()->flash('message', 'Yeay! Payment method berhasil diperbarui.');
        return back();
    }

    public function update_payment_type (Request $request, $payment_type_id) {
        $pt = PaymentType::find($payment_type_id);
        $pt->update([
            'payment_method_id' => $request->payment_method_id,
            'type_name' => $request->type_name,
        ]);
        
        session()->flash('message', 'Yeay! Payment type berhasil diperbarui.');
        return back();
    }

    public function get_payment_method ($payment_method_id) {
        return response()->json(['status' => true, 'message' => 'payment method' , 'data' => PaymentMethod::find($payment_method_id)]);
    }

    public function get_payment_detail ($payment_method_id) {
        $payment = DB::table('payment_types')
                ->join('payment_methods', 'payment_methods.id', '=', 'payment_types.payment_method_id')
                ->where('payment_types.payment_method_id', $payment_method_id)
                ->select('payment_types.id as payment_type_id', 'payment_types.type_name', 'payment_methods.id as payment_method_id', 'payment_methods.method_name')->get();

        return response()->json(['status' => true, 'message' => 'payment detail' , 'data' => $payment]);
    }
}
