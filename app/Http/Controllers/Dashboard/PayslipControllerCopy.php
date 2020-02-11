<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Staff;
use App\Period;
use App\Jobs\SendEmailJob;
use App\PayrollParameter;
use App\Tax;
use App\User;
use Auth;
use Carbon\Carbon;
use App\ParCategories;
use PDF;
use App\Payroll;
use App\Mail\SendPayslip;
use App\Http\Resources\Payroll\PayrollCollection;

class PayslipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $p = Payroll::latest()->get();
        return new PayrollCollection($p);
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
        $staff = Staff::whereIn('id', $request->staffId)->with('periods')->get();
        
        $results = [];
        $staff_data = [];

        
        foreach ($staff as $employee) {
            // active period
            $period = [];
            foreach ($employee->periods as $p) {
                if ($p->is_current === 1) {
                    $period = $p;
                }
            }
            
            if (!empty($period)) {
                //working days
                $sun_work = [];
                $day_names = [];
                $type = CAL_GREGORIAN;
                $month = $period->pay_month;
                $year = $period->pay_year;
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                //loop through all days
                for ($i = 1; $i <= $day_count; $i++) {
                    $date = $year . '/' . $month . '/' . $i; //format date
                        $get_name = date('l', strtotime($date)); //get week day
                        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
                //   check number of days
                    if ($day_name == 'Sun') {
                        $sun_work[] = $i;
                    }
                }
                // days in current period month
                $days_m =  Carbon::parse($period->begining_date)->daysInMonth;
                $sundays = count($sun_work);
                $working_days = $days_m - $sundays;

                //parameter data
                $data = [
                    'parameter' => $employee->parameter,
                    'workingDays' => $working_days,
                    'staffId' => $employee->id,
                    'email' => $employee->email,
                    'surname' => $employee->surname,
                    'otherNames' => $employee->otherNames,
                    'staffNo' => $employee->staffNo,
                    'idNo' => $employee->idNo,
                    'fullnames' => $employee->surname . ' '. $employee->otherNames,
                    'department' => $employee->department->name,
                    'end_period' => $period->ending_date,
                ];
        
                array_push($staff_data, $data);
            }
        }

        //calculating paye
        foreach ($staff_data as $val) {
            $parameter = $val['parameter'];

            $basic_salary = 0;
            $personal_relief = 0;
            $benefit = 0;
            $allowance = 0;
            $pre_tax_deduction = 0;
            $allowance = 0;
            $post_tax_deduction = 0;
            if (count($parameter) > 0) {
                foreach ($parameter as $p) {
                    //basic salary
                    if ($p->category_id == 1) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $basic_salary =  $p->default_value;
                        } else {
                            $basic_salary =  $amount;
                        }
                    }
                    //tax relief
                    if ($p->category_id == 6) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $personal_relief = $p->default_value;
                        } else {
                            $personal_relief = $amount;
                        }
                    }
                    //benefit
                    if ($p->category_id === 2) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $benefit =  $p->default_value;
                        } else {
                            $benefit =  $amount;
                        }
                    }
                    // allowance
                    if ($p->category_id === 3) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $allowance =  $p->default_value;
                        } else {
                            $allowance =  $amount;
                        }
                    }
                    //pre tax deduction
                    if ($p->category_id == 4) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $pre_tax_deduction =  $p->default_value;
                        } else {
                            $pre_tax_deduction =  $amount;
                        }
                    }
                    //post tax deduction
                    if ($p->category_id === 5) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $post_tax_deduction =  $p->default_value;
                        } else {
                            $post_tax_deduction =  $amount;
                        }
                    }
                    //payroll tax
                    if ($p->category_id === 7) {
                        $amount = $p->pivot->amount;
                        if ($amount === null) {
                            $payroll_tax =  $p->default_value;
                        } else {
                            $payroll_tax =  $amount;
                        }
                    }
                }
            }
            //====
            // check if has benefits and add to basic salary
            $salary = $benefit + $basic_salary + $allowance;
            // pre tax deductions
            $taxable_pay = (int) $salary - $pre_tax_deduction;
            
            // tax range
            $taxes = Tax::all();
            $tax_rate = 0;
            $upper_band = 0;
            $lower_band = 0;
            $tax_id = 0;

            foreach ($taxes as $tax) {
                $min = $tax->lower_limit;
                $max = $tax->upper_limit;

                if (in_array($taxable_pay, range($min, $max), true)) {
                    $tax_id = $tax->id;
                    $tax_rate = $tax->tax_rate;
                    $upper_band = $tax->upper_limit;
                    $lower_band = $tax->lower_limit;
                }
            }
            
            // naming the tax band
            $taxed_range = Tax::whereBetween('id', [1, ($tax_id - 1)])->get();
            $bands = [];

            foreach ($taxed_range as $data) {
                $bands["tax_band{$data->id}"] = $data->upper_limit;
                $tax_rates["tax_rate{$data->id}"] = $data->tax_rate;
            }
            
            // tax charges rate
            $tax_before_relief = 0;
            if (count($bands) == 1) {
                if ($taxable_pay > $bands['tax_band1']) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band1']) * ($tax_rate / 100) +
                        ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                }
            } elseif (count($bands) == 2) {
                if ($taxable_pay > $bands['tax_band2']) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band2']) * ($tax_rate / 100) +
                        ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                        ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                }
            } elseif (count($bands) == 3) {
                if ($taxable_pay > $bands['tax_band3']) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band3']) * ($tax_rate / 100) +
                        ($tax_rates['tax_rate3'] / 100) * ($bands['tax_band3'] - $bands['tax_band2']) +
                        ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                        ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                }
            } elseif (count($bands) == 4) {
                if ($taxable_pay > $bands['tax_band4']) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band4']) * ($tax_rate / 100) +
                        ($tax_rates['tax_rate4'] / 100) * ($bands['tax_band4'] - $bands['tax_band3']) +
                        ($tax_rates['tax_rate3'] / 100) * ($bands['tax_band3'] - $bands['tax_band2']) +
                        ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                        ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                }
            } else {
                $tax_before_relief = 0;
            }

            // tax after relief
            if ($tax_before_relief > 0) {
                $paye = $tax_before_relief - $personal_relief;
            } else {
                $paye = 0;
            }
            //total_deductions
            $total_deductions = $post_tax_deduction + $pre_tax_deduction + $paye;
            // chargeable tax
            $chargeable_income  = $taxable_pay - $paye;
            // post tax deductions
            $net_pay = $chargeable_income - $post_tax_deduction;

            $user_payroll_info = [
                'gross_pay' => round($basic_salary, 2),
                'taxable_pay' => round($tax_before_relief, 2),
                'personal_relief' => round($personal_relief, 2),
                'total_deductions' => round($total_deductions, 2),
                'paye' => round($paye, 2),
                'net_salary' => round($net_pay, 2),
                'staff_id' => $val['staffId'],
                'days_in' =>  $val['workingDays'],
                'user_email' => $val['email'],
                'surname' => $val['surname'],
                'other_names' => $val['otherNames'],
                'employee_no' => $val['staffNo'],
                'id_no' => $val['idNo'],
                'fullnames' => $val['fullnames'],
                'department' => $val['department'],
                'end_period' => $val['end_period'],
                
            ];

            // echo($basic_salary);
            $payroll_no = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 5) . mt_rand(0, 100));

            // save to db
            $pr = Payroll::where(['staff_id' => $user_payroll_info['staff_id'] ,'period_date' => $user_payroll_info['end_period']])->get();

            if (count($pr) == 0) {
                $payroll = new Payroll();
                $payroll->gross_salary = $user_payroll_info['gross_pay'];
                $payroll->taxable_pay = $user_payroll_info['taxable_pay'];
                $payroll->personal_relief = $user_payroll_info['personal_relief'];
                $payroll->department = $user_payroll_info['department'];
                $payroll->total_deductions = $user_payroll_info['total_deductions'];
                $payroll->paye = $user_payroll_info['paye'];
                $payroll->net_salary = $user_payroll_info['net_salary'];
                $payroll->staff_id = $user_payroll_info['staff_id'];
                $payroll->days_in = $user_payroll_info['days_in'];
                $payroll->email = $user_payroll_info['user_email'];
                $payroll->payroll_no = $payroll_no;
                $payroll->status = 'Pending';
                $payroll->period_date = $user_payroll_info['end_period'];
                $payroll->surname = $user_payroll_info['surname'];
                $payroll->other_names = $user_payroll_info['other_names'];
                $payroll->name_slip = 'Salary Slip of ' . $user_payroll_info['fullnames'] . ' for ' . $user_payroll_info['end_period'];
                $payroll->save();

                array_push($results, $payroll);
            } else {
                return response()->json('Payslip(s) already exist!', 404);
            }
        }

        return response()->json($results);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!empty($id)) {
            $p = Payroll::where('period_id', $id)->get();
            return new PayrollCollection($p);
        }
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
