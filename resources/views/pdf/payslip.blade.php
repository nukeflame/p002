<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <link rel="stylesheet" href="css/print_static.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
  </head>
  <style>
  .table td, .table th {
      padding: 0.5em;
    }
  </style>
  <body>
    <div id="body">
      <div id="section_header"></div>

      <div id="content">
        <div class="page" style="font-size: 7pt">
          <div style="text-align: right" >
                <img width="60" height="60" src="images/dompdf_simple.png" />
                <h1 style="margin-top: 2px; margin-bottom: 2px;">Neema Ya Mungu Investments LTD</h1>
                <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">E-Mail: <span style="font-size: 9pt;">info@neemayamunguinvestmentsltd.com </span></h1>
                {{-- <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">Web: <span style="font-size: 9pt;">www.neemayamunguinvestmentsltd.com </span></h1> --}}
                <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">P.O. Box: <span style="font-size: 9pt;">57929 - 00200 Mombasa - Kenya</span></h1>
                <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">Mobile: <span style="font-size: 9pt;">07015961545, 0712852763</span></h1>
               {{-- <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">Street Address: <span style="font-size: 9pt;"> Mombasa, Kenya </span></h3> --}}
                <h3 style="font-size: 10pt; margin-top: 2px; margin-bottom: 2px;">Tel: <span style="font-size: 9pt;">(020) 6552477 / 6552448 </span></h1>
          </div>

          <table style="width: 100%;" class="header">
            <tr>
              <td><h1 style="text-align: left">PAYSLIP</h1></td>
            </tr>
          </table>

          <table style="width: 100%; font-size: 10pt; margin-top: 8px; margin-bottom: 1em; padding-bottom: 1em;">
            <tr>
              <td>Employee Name: <strong>{{ $payroll->user_name }}</strong></td>
              <td>Receipt No: <strong>PS/{{ $payroll->payroll_no }}</strong></td>
            </tr>

            <tr>
                <td>E-mail: <strong>{{ $payroll->email }}</strong></td>
                <td>Position: <strong>{{ $payroll->position }}</strong></td>
            </tr>

            <tr>
              <td>Employee Id: <strong>{{ $payroll->employee_no }}</strong></td>
              <td>Pay Period: <strong>{{ $payroll->start_month }} - {{ $payroll->end_month }}</strong></td>
            </tr>

            <tr>
                <td>Days In: <strong>{{ $payroll->days_in }}</strong></td>
                <td>Working Days: <strong>{{ $payroll->working_days }}</strong></td>
            </tr>
          </table>

          <table class="table table-striped" style="font-size: 8pt;">
            <thead>
               <tr>
                   <th scope="col">Year to Date Figures</th>
                   <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Pay</td>
                    <td>Ksh 14,800.00</td>
                </tr>

                <tr>
                    <td>Allowance</td>
                    <td>Ksh 0.00</td>
                </tr>
                <tr>
                    <td>Advance</td>
                    <td>Ksh 14,800.00</td>
                </tr>
                <tr>
                    <td>Taxable Income</td>
                    <td>Ksh 14,800.00</td>
                </tr>
                <tr>
                    <td>Insurance relief</td>
                    <td>Ksh 0.00</td>
                </tr>
                <tr>
                    <td>Personal relief</td>
                    <td>Ksh 1,408.00</td>
                </tr>
                <tr>
                    <td>National Social Security Fund (NSSF)</td>
                    <td>Ksh 200.00</td>
                </tr>
                <tr>
                    <td>National Health Insurance Fund (NHIF)</td>
                    <td>Ksh 600.00</td>
                </tr>
                <tr>
                    <td>Net Pay</td>
                    <td>Ksh 14,002.90</td>
                </tr>
            </tbody>
          </table>

          {{-- <div>
              <br><br>
              <i>Received By:  .................................................................. Sign .....................................................................</i>
          </div>
          <div>
              <br><br>
              <i>Authorised Signature ....................................................................................................................................</i>
          </div> --}}
          <div>
              <br><br>
              <i>Prepared By: {{ $payroll->auth_name }}, Branch {{ $payroll->auth_position }}</i>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>
