<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Illuminate\Http\Request;
use App\Client;
use App\Staff;
use Auth;
use App\Http\Resources\User\User as UserResource;

// use App\Http\Requests\Login\LoginRequest;

class LoginController extends Controller
{
    public function index(Request $r)
    {
        $email = $r->username;
        $password = $r->pwd;
        $message = [];

        $user = User::where('email', $r->username)->first();
        
        if (!empty($user) && Hash::check($password, $user->password)) {
            $client = Staff::where(['client_id' => $r->clientId,'user_id' => $user->id])->get();
            
            if (!count($client) > 0) {
                $message['notfound'] = ['Employee not found, Contact Administrator for info.'];
                return response()->json($message, 404); // employee not found
            } else {
                if ($user->is_active == 0) {
                    $message['blocked'] = ['Account access BLOCKED, Contact Administrator for info.'];
                    return response()->json($message, 406); // account access blocked
                } elseif ($user->is_active == 1) {
                    $message['pending'] = ['Account access PENDING, Contact Administrator for info.'];
                    return response()->json($message, 409); // account access pending
                } elseif ($user->is_active == 2) {
                    // issue token
                    $access = $user->createToken('Laravel Password Grant Client');
                    $expiration = time($access->token->expires_at);
                    $token = $access->accessToken;
                    if ($user->acc_level === 0) {
                        return response()->json(['acc_level' => 0, 'expiration' => $expiration, 'token' => $token], 200);
                    } elseif ($user->acc_level === 1) {
                        return response()->json(['acc_level' => 1, 'expiration' => $expiration, 'token' => $token], 200);
                    } elseif ($user->acc_level === 2) {
                        return response()->json(['acc_level' => 2, 'expiration' => $expiration, 'token' => $token], 200);
                    }
                }
            }
        } else {
            $message['notfound'] = ['Invalid Email or Password, try again.'];
            return response()->json($message, 404); // account not found
        }
    }
}
