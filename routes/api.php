<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->group(function () {
    Route::get('/user', 'Dashboard\UserController@auth');

    // Route::resource('/users', 'Dashboard\UserController', ['except' => 'store']);
    Route::post('/users/post', 'Dashboard\UserController@store');
    Route::get('/users', 'Dashboard\UserController@index');

    Route::get('/notifications', 'Dashboard\NotificationController@index');
    Route::get('/user/mark-unread', 'Dashboard\NotificationController@mark_unread');
    Route::get('/user/mark-unread/{id}', 'Dashboard\NotificationController@mark_unread_user');
    Route::get('/notification/show/{id}', 'Dashboard\NotificationController@show');
    //roles
    Route::get('/roles', 'Dashboard\RoleController@index');
    Route::post('/roles', 'Dashboard\RoleController@store');
    Route::put('/roles/{role}', 'Dashboard\RoleController@update');
    //permissions
    Route::get('/permissions', 'Dashboard\PermissionController@index');
    Route::post('/permissions', 'Dashboard\PermissionController@store');
    // payroll
    Route::get('/payroll', 'Dashboard\PayslipController@index');
    Route::get('/payroll/{payroll}', 'Dashboard\PayslipController@show');
    Route::post('/payroll', 'Dashboard\PayslipController@store');
    // taxes
    Route::get('/taxes', 'Dashboard\TaxController@index');
    Route::post('/taxes/store', 'Dashboard\TaxController@store');
    //leaves
    Route::get('/leaves', 'Dashboard\LeaveController@index');
    Route::post('/leaves', 'Dashboard\LeaveController@store');
    Route::delete('/leaves/{leave}', 'Dashboard\LeaveController@destroy');
    //leaves type
    Route::get('/leaves_type', 'Dashboard\LeaveTypeController@index');
    //advance pay
    Route::get('/advances', 'Dashboard\AdvancePayController@index');
    Route::get('/advances/{advance}', 'Dashboard\AdvancePayController@show');
    Route::post('/advances', 'Dashboard\AdvancePayController@store');
    //overtime
    Route::get('/overtime', 'Dashboard\OvertimeController@index');
    Route::post('/overtime', 'Dashboard\OvertimeController@store');
    Route::put('/overtime/{overtime}', 'Dashboard\OvertimeController@update');
    Route::delete('/overtime/{overtime}', 'Dashboard\OvertimeController@destroy');
    //employees
    Route::get('/employees', 'Dashboard\EmployeesController@index');
    Route::post('/employees/store', 'Dashboard\EmployeesController@store');
    Route::get('/employees/{employee}', 'Dashboard\EmployeesController@show');
    //checkins
    Route::get('/checkins', 'Dashboard\CheckinsController@index');
    Route::get('/checkins/{checkin}', 'Dashboard\CheckinsController@show');
    Route::delete('/checkins/{checkin}', 'Dashboard\CheckinsController@destroy');
    //consultants
    Route::get('/consultants', 'Dashboard\ConsultantController@index');
    Route::get('/consultants/{consultant}', 'Dashboard\ConsultantController@show');
    Route::post('/consultants', 'Dashboard\ConsultantController@store');
    Route::delete('/consultants/{consultant}', 'Dashboard\ConsultantController@destroy');
    //clients
    Route::get('/clients', 'Dashboard\ClientController@index');
    Route::get('/clients/{client}', 'Dashboard\ClientController@show');
    Route::post('/clients', 'Dashboard\ClientController@store');
    Route::delete('/clients/{client}', 'Dashboard\ClientController@destroy');
    //
    Route::resource('/attendance', 'Dashboard\AttendanceController', ['except' => 'store']);
    Route::post('/attendance/post', 'Dashboard\AttendanceController@store');

    Route::resource('/branches', 'Dashboard\BranchController', ['except' => 'store']);
    Route::post('/branches/post', 'Dashboard\BranchController@store');

    Route::get('send', 'Dashboard\MessageController@send');
    

    Route::get('/periods', 'Dashboard\PayPeriodController@index');
    Route::post('/periods/store', 'Dashboard\PayPeriodController@store');
    //payroll parameters
    Route::get('/payroll_pars', 'Dashboard\PayrollParameterController@index');
    Route::post('/payroll_pars', 'Dashboard\PayrollParameterController@store');
    Route::put('/payroll_pars/{par}', 'Dashboard\PayrollParameterController@update');
    Route::delete('/payroll_pars/{par}', 'Dashboard\PayrollParameterController@destroy');
    //messages
    Route::get('/users-online', 'Dashboard\MessagesController@online');
    Route::get('/messages', 'Dashboard\MessagesController@index');
    Route::get('/messages/{message}', 'Dashboard\MessagesController@show');
    Route::post('/messages', 'Dashboard\MessagesController@store');
    Route::post('/messages/send', 'Dashboard\MessagesController@store');
    //payroll reports
    Route::get('/payroll_reports', 'Dashboard\PayrollReportController@index');
    Route::post('/payroll_reports', 'Dashboard\PayrollReportController@store');
    //settings
    Route::get('/general_settings', 'Dashboard\SettingsController@general');
    Route::put('/general_settings/{set}', 'Dashboard\SettingsController@gemeral_update');
});

// Route::get('test-broadcast', function () {
//     $notf = User::find(1);
//     broadcast(new \App\Events\ExampleEvent($notf));
//     echo 'Sent';
// });Route::post('/register', 'Auth\RegisterController@create');

Route::post('/payrollcode', 'Dashboard\ClientController@show');
Route::post('/login', 'Auth\LoginController@index');


Route::resource('/mail', 'MailController');
