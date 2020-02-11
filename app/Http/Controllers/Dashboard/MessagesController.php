<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Auth;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\Messages\MessageCollection;
use App\Http\Resources\Messages\Message as MessageResource;
use App\Events\Messages\MessageSent;
use App\Chat;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id())->get();
        $array_chatsIds = [];
        foreach ($chats as $key => $chat) {
            $array_chatsIds[] = $chat->id;
        }

        $messages = Message::whereIn('chat_id', $array_chatsIds)
            ->with(['chat.sender', 'chat.receiver', 'sender'])
            ->latest()->get();
        $newMessages = collect(new MessageCollection($messages))->groupBy('chatId');
        $allmessages = [];
        foreach ($newMessages as $key => $groupMessage) {
            $allmessages[] = $groupMessage[0];
        }

        return new MessageCollection($messages);

        // $messages = Auth::user()->messages()
        // ->where(function ($query) {
        //     $query->bySender(request()->input('senderId'))
        //             ->byReceiver(Auth::id());
        // })
        //     ->orWhere(function ($query) {
        //         $query->bySender(Auth::id())
        //             ->byReceiver(request()->input('senderId'));
        //     })
        //     ->get();

        // return new MessageCollection($messages);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function online()
    {
        $users =  User::orderBy('name')->where('id', '!=', Auth::id())->get();
        return new UserCollection($users);
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'message' => $request->message,
            'sender_id' => $request->senderId,
        ];

        $message = Message::create($data);
        
        return  new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::where('sender_id', $id)->orWhere('receiver_id', $id)->first();

        $messages = Message::where('chat_id', $chat->id)
            ->with(['chat.sender', 'chat.receiver', 'sender'])
            ->get();

        return new MessageCollection($messages);
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
