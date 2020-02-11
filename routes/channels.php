<?php

use App\Broadcasting\LeavesChannel;
use App\Broadcasting\AdvancePayChannel;
use App\Broadcasting\DashboardChannel;
use App\Broadcasting\UserChannel;
use App\Broadcasting\AttendanceChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{id}', UserChannel::class);
Broadcast::channel('dashboard', AdvancePayChannel::class);
Broadcast::channel('leaves', LeavesChannel::class);
Broadcast::channel('payments', AdvancePayChannel::class);
Broadcast::channel('attendance', AttendanceChannel::class);

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    // if ($user->canJoinRoom($roomId)) {
    return ['id' => $user->id, 'name' => $user->name];
    // }
});
