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
use App\Http\Resources\Payroll\Payroll as ResourcePayroll;
use App\Overtime;

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
        // active period
        $period = Period::findOrFail($request->periodId);
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
        //each parameter data
        foreach ($staff as $employee) {
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
            //calculate deductions and benefits
            if (count($parameter) > 0) {
                foreach ($parameter as $p) {
                    //basic salary
                    if ($p->category_id == 1) {
                        if ($p->useDefault) {
                            $basic_salary =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $basic_salary =  $amount;
                                } else {
                                    $basic_salary =  $p->default_value;
                                }
                            } else {
                                $basic_salary =  $p->default_value;
                            }
                        }
                    }
                    //tax relief
                    if ($p->category_id == 6) {
                        if ($p->useDefault) {
                            $personal_relief =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $personal_relief =  $amount;
                                } else {
                                    $personal_relief =  $p->default_value;
                                }
                            } else {
                                $personal_relief =  $p->default_value;
                            }
                        }
                    }
                    //benefit
                    if ($p->category_id === 2) {
                        if ($p->useDefault) {
                            $benefit =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $benefit =  $amount;
                                } else {
                                    $benefit =  $p->default_value;
                                }
                            } else {
                                $benefit =  $p->default_value;
                            }
                        }
                    }
                    // allowance
                    if ($p->category_id === 3) {
                        if ($p->useDefault) {
                            $allowance =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $allowance =  $amount;
                                } else {
                                    $allowance =  $p->default_value;
                                }
                            } else {
                                $allowance =  $p->default_value;
                            }
                        }
                    }
                    //pre tax deduction
                    if ($p->category_id == 4) {
                        if ($p->useDefault) {
                            $pre_tax_deduction =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $pre_tax_deduction =  $amount;
                                } else {
                                    $pre_tax_deduction =  $p->default_value;
                                }
                            } else {
                                $pre_tax_deduction =  $p->default_value;
                            }
                        }
                    }
                    //post tax deduction
                    if ($p->category_id === 5) {
                        if ($p->useDefault) {
                            $post_tax_deduction =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $post_tax_deduction =  $amount;
                                } else {
                                    $post_tax_deduction =  $p->default_value;
                                }
                            } else {
                                $post_tax_deduction =  $p->default_value;
                            }
                        }
                    }
                    //payroll tax
                    if ($p->category_id === 7) {
                        if ($p->useDefault) {
                            $amount =  $p->default_value;
                        } else {
                            $amount = $p->pivot->amount;
                            if ($amount !== null) {
                                $isActive = $p->pivot->is_active;
                                if ($isActive) {
                                    $amount =  $amount;
                                } else {
                                    $amount =  $p->default_value;
                                }
                            } else {
                                $amount =  $p->default_value;
                            }
                        }
                    }
                }
            }
            //==== calc off days
            //calc overtime
            $begining_date = Carbon::parse($period->begining_date)->format('Y-m-d');
            $ending_date = Carbon::parse($period->ending_date)->format('Y-m-d');
            $overtime_range = Overtime::where('staff_id', $request->staffId)->whereBetween('date', [$begining_date, $ending_date])->get();
            $overtime = $overtime_range->sum('amount');
            // check if has benefits, overtime, allowances and add to basic salary
            $salary = $benefit + $basic_salary + $allowance + $overtime;
            // pre tax deductions
            $taxable_pay = (int) $salary - $pre_tax_deduction;

            // calculate tax range
            $taxes = Tax::where('tax_rate', '!=', '0')->get();
            $tax_rate = 0;
            $upper_band = 0;
            $lower_band = 0;
            $tax_id = 0;
            $tax_is_limit = false;
            //tax range
            foreach ($taxes as $tax) {
                $min = $tax->lower_limit;
                $max = $tax->upper_limit;
                if (in_array($salary, range($min, $max), true)) {
                    $tax_id = $tax->id;
                    $tax_rate = $tax->tax_rate;
                    $upper_band = $tax->upper_limit;
                    $lower_band = $tax->lower_limit;
                    $tax_is_limit = true;
                    break;
                } else {
                    $tax_is_limit = false;
                    $tax_id = $tax->id;
                    $tax_rate = $tax->tax_rate;
                    $upper_band = $tax->upper_limit;
                    $lower_band = $tax->lower_limit;
                }
            }
            // naming the tax bands
            $taxed_range = Tax::whereBetween('id', [1, ($tax_id)])->get();
            $bands = [];
            foreach ($taxed_range as $data) {
                $bands["tax_band{$data->id}"] = $data->upper_limit;
                $tax_rates["tax_rate{$data->id}"] = $data->tax_rate;
            }
            //calculate tax charges rate
            $tax_before_relief = 0;
            
            if ($tax_is_limit) {
                if (count($bands) == 1) {
                    $tax_before_relief =  ($taxable_pay - $bands['tax_band1']) * ($tax_rate / 100) +
                         ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                } elseif (count($bands) == 2) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band2']) * ($tax_rate / 100) +
                         ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                         ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                } elseif (count($bands) == 3) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band3']) * ($tax_rate / 100) +
                         ($tax_rates['tax_rate3'] / 100) * ($bands['tax_band3'] - $bands['tax_band2']) +
                         ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                         ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                } elseif (count($bands) == 4) {
                    $tax_before_relief = ($taxable_pay - $bands['tax_band4']) * ($tax_rate / 100) +
                            ($tax_rates['tax_rate4'] / 100) * ($bands['tax_band4'] - $bands['tax_band3']) +
                            ($tax_rates['tax_rate3'] / 100) * ($bands['tax_band3'] - $bands['tax_band2']) +
                            ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                            ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
                }
            } else {
                //above limit tax band
                $tax_before_relief = ($taxable_pay - $bands['tax_band5']) * ($tax_rate / 100) +
                ($tax_rates['tax_rate4'] / 100) * ($bands['tax_band4'] - $bands['tax_band3']) +
                            ($tax_rates['tax_rate3'] / 100) * ($bands['tax_band3'] - $bands['tax_band2']) +
                            ($tax_rates['tax_rate2'] / 100) * ($bands['tax_band2'] - $bands['tax_band1']) +
                            ($tax_rates['tax_rate1'] / 100) * $bands['tax_band1'];
            }
            
            // tax after relief (paye)
            $paye = 0;
            if ($tax_before_relief > 11180) {
                $paye = $tax_before_relief - $personal_relief;
            }
            //
            //total_deductions
            $total_deductions = $post_tax_deduction + $pre_tax_deduction + $paye;
            // chargeable tax
            $chargeable_income  = $taxable_pay - $paye;
            // post tax deductions
            $net_pay = $chargeable_income - $post_tax_deduction;
            
            //employee data
            $user_payroll_info = [
             'tax_before_relief' => round($tax_before_relief, 4),
             'basic_pay' => $basic_salary,
             'gross_salary' => $salary,
             'taxable_pay' => round($taxable_pay, 4),
             'personal_relief' => round($personal_relief, 4),
             'total_deductions' => round($total_deductions, 4),
             'paye' => round($paye, 4),
             'net_salary' => round($net_pay, 4),
             'overtime' =>  $overtime
            //  'staff_id' => $val['staffId'],
            //  'days_in' =>  $val['workingDays'],
            //  'user_email' => $val['email'],
            //  'surname' => $val['surname'],
            //  'other_names' => $val['otherNames'],
            //  'employee_no' => $val['staffNo'],
            //  'id_no' => $val['idNo'],
            //  'fullnames' => $val['fullnames'],
            //  'department' => $val['department'],
            //  'end_period' => $val['end_period'],
             
         ];

            array_push($results, $user_payroll_info);
            // save payroll to db
            // $payroll_no = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 5) . mt_rand(0, 100));
            // $payroll = Payroll::firstOrNew(['staff_id' => $user_payroll_info['staff_id'], 'period_id' => $period->id]);
            // $payroll->gross_salary = $user_payroll_info['gross_pay'];
            // $payroll->tax_before_relief = $user_payroll_info['tax_before_relief'];
            // $payroll->taxable_pay = $user_payroll_info['taxable_pay'];
            // $payroll->personal_relief = $user_payroll_info['personal_relief'];
            // $payroll->department = $user_payroll_info['department'];
            // $payroll->total_deductions = $user_payroll_info['total_deductions'];
            // $payroll->paye = $user_payroll_info['paye'];
            // $payroll->net_salary = $user_payroll_info['net_salary'];
            // $payroll->days_in = $user_payroll_info['days_in'];
            // $payroll->email = $user_payroll_info['user_email'];
            // $payroll->payroll_no = $payroll_no;
            // $payroll->status = 1;//pending
            // $payroll->period_date = $user_payroll_info['end_period'];
            // $payroll->surname = $user_payroll_info['surname'];
            // $payroll->other_names = $user_payroll_info['other_names'];
            // $payroll->name_slip = 'Salary Slip of ' . $user_payroll_info['fullnames'] . ' for ' . $user_payroll_info['end_period'];
            // $payroll->save();

            // array_unshift($results, new ResourcePayroll($payroll));
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
