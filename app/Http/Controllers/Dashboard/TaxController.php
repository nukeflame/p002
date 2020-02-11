<?php

namespace App\Http\Controllers\Dashboard;

use App\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tax\TaxCollection;
use App\Http\Resources\Tax\Tax as TaxResource;
use App\Http\Requests\Tax\TaxRequest;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tax::orderBy('tax_rate', 'asc')->get();
        return new TaxCollection($data);
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
    public function store(TaxRequest $request)
    {
        $tax = new Tax();
        $tax->upper_limit = $request->upperLimit;
        $tax->lower_limit = $request->lowerLimit;
        $tax->tax_rate = $request->taxRate;
        $tax->no_limit = $request->hasNoLimit;
        $tax->save();

        return new TaxResource($tax);
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
