<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Brand;
use App\Model\MasterData\Unit;
use App\Model\MasterData\Category;
use App\Model\MasterData\PaymentMethod;
use Illuminate\Support\Facades\DB;

class SecondaryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        $units = Unit::all();
        $categories = Category::all();
        $payment_methods = PaymentMethod::all();
        $payment_types = DB::table('payment_types')->join('payment_methods', 'payment_methods.id', '=', 'payment_types.payment_method_id')->select('payment_types.*', 'payment_methods.id as payment_method_id', 'payment_methods.method_name')->get();

        return view('pages.data-pendukung.data-pendukung', ['brands' => $brands, 'units' => $units, 'categories' => $categories, 'payment_methods' => $payment_methods, 'payment_types' => $payment_types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
