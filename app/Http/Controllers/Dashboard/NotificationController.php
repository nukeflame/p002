<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notf =  Auth::user()->notifications;
        return response()->json($notf);
    }


    public function show($id)
    {
        // Auth::user()->unreadNotifications->markAsRead();
        return response()->json($id);
    }

    public function mark_unread()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['marked' => true]);
    }

    public function mark_unread_user($id)
    {
        $user = User::findOrFail($id);
        $notf = $user->unreadNotifications->markAsRead();

        return response()->json(['marked' => true]);
    }
}
