<?php

namespace App\Http\Controllers\Dashboard;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\NHIF;
use App\Payroll;
use App\Tax;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Mail;
use App\Mail\SendPayslip;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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























        $month = Carbon::now()->month;

        $payroll = DB::table('attendances')
            ->select('attendances.id', 'user_id', 'name', 'id_no', 'nhif_no', 'kra_no', 'working_days', 'created_payroll', DB::raw('count(attendances.user_id) AS days_in'))
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->whereMonth('attendances.created_at', '=', $month)
            ->groupBy('attendances.user_id', 'attendances.working_days', 'attendances.id', 'attendances.created_payroll')
            ->get();

        // $payroll = User::withCount('attendance', 'payrolls')->whereHas('attendance', function ($query) {
        //     $query->whereMonth('created_at', '=', $this->month);

        // })->get();

        return response()->json($payroll);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payroll = Payroll::with('users')->get();
        return response()->json($payroll);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('id', $request->userId)->first();

        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {
            $date = $year . '/' . $month . '/' . $i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            if ($day_name == 'Sun') {
                $workdays[] = $i;
            }
        }

        $sundays = count($workdays);
        $working_days = Carbon::now()->daysInMonth - $sundays;

        if ($request->daysIn > $working_days) {
            return response()->json(['msg' => 'Days in have Exceed number of working days!']);
        }

        // basic salary
        $income = $request->wageRate * $request->daysIn;
        // tax relief
        $relief = 1408;

        //NHIF contribution
        $nhif = NHIF::all();
        $nhif_rate = '';

        foreach ($nhif as $field) {
            $min = $field->min_charges;
            $max = $field->max_charges;

            if (in_array($income, range($min, $max), true)) {
                $nhif_rate = $field->rate_amount;
            }
        }

        // Nssf contribution not taxed
        if ($income < $user->nssf_contribution) {
            $tax_before_relief = 0;
        } else {
            // taxable pay
            $taxable_pay = $income - $user->nssf_contribution;

            // tax range
            $taxes = Tax::all();
            $tax_rate = '';
            $from_band = '';
            $to_band = '';
            $tax_id = '';

            foreach ($taxes as $tax) {
                $min = $tax->from;
                $max = $tax->to;

                if (in_array($taxable_pay, range($min, $max), true)) {
                    $tax_id = $tax->id;
                    $tax_rate = $tax->rate;

                    $from_band = $tax->from;
                    $to_band = $tax->to;
                }
            }

            // naming the tax band
            $taxed_range = Tax::whereBetween('id', [1, ($tax_id - 1)])->get();
            $bands = [];

            foreach ($taxed_range as $data) {
                $bands["tax_band{$data->id}"] = $data->to;
                $tax_rates["tax_rate{$data->id}"] = $data->rate;
            }

            // tax charges rate
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
        }

        // tax after relief
        if ($tax_before_relief > 0) {
            $paye = $tax_before_relief - $relief;
        } else {
            $paye = 0;
        }

        // net pay
        $net_salary = $taxable_pay - ($paye + $nhif_rate);

        $user_payroll_info = [
            'gross_pay' => $income,
            'taxable_pay' => round($taxable_pay, 2),
            'insurance_relief' => 0.00,
            'personal_relief' => $relief,
            'nhif_contr' => $nhif_rate,
            'nssf_contr' => round($user->nssf_contribution, 2),
            'paye' => round($paye, 2),
            'net_salary' => round($net_salary, 2),
            'user_id' => $user->id,
            'days_in' => $request->daysIn,
            'user_email' => $user->email,
            'user_name' => $user->name,
            'employee_no' => $user->employee_no,
            'id_no' => $user->id_no,
        ];

        if (!$request->has('paySlip')) {
            return response()->json($user_payroll_info);
        } else {
            // attendance created payroll
            $created = Attendance::findOrFail($request->id);
            $created->created_payroll = Carbon::now();
            $created->save();

            $fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
            $payroll_no = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 5) . mt_rand(0, 100));

            // save to db
            $payroll = new Payroll();
            $payroll->gross_salary = $user_payroll_info['gross_pay'];
            $payroll->taxable_pay = $user_payroll_info['taxable_pay'];
            $payroll->personal_relief = $user_payroll_info['personal_relief'];
            $payroll->insurance_relief = $user_payroll_info['insurance_relief'];
            $payroll->nhif = $user_payroll_info['nhif_contr'];
            $payroll->nssf = $user_payroll_info['nssf_contr'];
            $payroll->paye = $user_payroll_info['paye'];
            $payroll->net_salary = $user_payroll_info['net_salary'];
            $payroll->user_id = $user_payroll_info['user_id'];
            $payroll->days_in = $user_payroll_info['days_in'];
            $payroll->email = $user_payroll_info['user_email'];
            $payroll->payroll_no = $payroll_no;
            $payroll->status = 'Sent';
            $payroll->user_name = $user_payroll_info['user_name'];
            $payroll->name = 'Salary Slip of ' . $user_payroll_info['user_name'] . ' for ' . $endMonth;
            $payroll->save();

            // payslip info
            $payroll->employee_no = $user_payroll_info['employee_no'];
            $payroll->user_name = $user_payroll_info['user_name'];
            $payroll->start_month = $fromDate;
            $payroll->end_month = $endMonth;
            $payroll->working_days = $working_days;
            $payroll->auth_name = Auth::user()->name;

            //pdf
            $pdf = PDF::loadView('pdf.payslip', ['payroll' => $payroll]);
            // $pdf->setEncryption('1');
            $pdf->setPaper('A4', 'portrait');
            $pdf->save('pdf/' . $payroll->name . '.pdf');

            // Mail::to('1kenpeters1@gmail.com')->send(new SendPayslip($pdf));
            // SendEmailJob::dispatch($pdf)
            //     ->delay(now()->addSeconds(5));

            // Mail::send('emails.payslips', $data, function ($message) use ($data, $pdf) {

            //     $message->to('1kenpeters1@gmail.com')->subject('PAYSLIP');
            //     $message->from('1kenpeters1@gmail.com', 'NEEMA YA MUNGU INVESTMENTS LIMITED');
            //     $message->attachData($pdf->output(), 'payslip.pdf');

            // });

            // Mail::to($payroll->email,'PAYSLIP')
            //     ->cc('1kenpeters1@gmail.com')
            //     // ->bcc($evenMoreUsers)
            //     ->send(new SendPayslip($this->pdf));

            return response()->json($payroll);
        }
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
