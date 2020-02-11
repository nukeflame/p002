<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payrol\PayrollParRequest;
use App\Staff;
use App\PayrollParameter;
use App\Http\Resources\Parameter\ParameterCollection;
use App\Http\Resources\Parameter\Parameter as ParameterResource;
use App\Http\Resources\Staff\Staff as StaffResource;

class PayrollParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $p = PayrollParameter::latest()->get();
        return new ParameterCollection($p);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayrollParRequest $request)
    {
        $payrolpar = new PayrollParameter();
        $payrolpar->name = $request->name;
        $payrolpar->description = $request->description;
        $payrolpar->category_id = $request->categoryId;
        $payrolpar->default_value = $request->defValue;
        $payrolpar->useDefault = $request->isDefVal;
        $payrolpar->isDefault = $request->isDef;
        $payrolpar->isRequired = $request->isRequired;
        $payrolpar->save();

        return new ParameterResource($payrolpar);
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
    public function store_staff_parameter(Request $request)
    {
        $staffId = $request->staffId;
        $pId = $request->parameter;
        $staff = Staff::findOrFail($staffId);
        $staff->parameter()->attach($pId);
        return response()->json($staff);
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
        if ($request->has('isStaff')) {
            $staff = Staff::findOrFail($id);
            $pId = (int) $request->parameter;
            $pIsActive = (int) $request->has('isActive') ? $request->isActive : 0;
            $pIsAmount = $request->amount;

            if (count($staff->parameter) > 0) {
                foreach ($staff->parameter  as $p) {
                    $par_id = $p->pivot->parameter_id;
                    if ($pId === $par_id) {
                        $staff->parameter()->detach($par_id);
                    }
                }
            }
            $staff->parameter()->attach($pId, [
                'amount' => $pIsAmount,
                'is_active' => $pIsActive
            ]);
            
            $c_staff = Staff::findOrFail($staff->id);
            $staff_res =  new StaffResource($c_staff);
            return new ParameterCollection($staff_res->parameter);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r = json_decode($id, true);
        $staff = Staff::findOrFail($r['staffId']);
        $staff->parameter()->detach($r['parId']);
        $par = PayrollParameter::find($r['parId']);

        return new ParameterResource($par);
    }
}
