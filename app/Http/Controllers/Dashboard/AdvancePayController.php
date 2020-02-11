<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Payments\AdvancePayCollection;
use App\Http\Resources\Payments\AdvancePay as ResourceAdvancePay;
use App\AdvancePay;
use App\Staff;
use App\Events\Payments\Advance\CreateAdvanceEvent;

class AdvancePayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $chats = Chat::where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id())->get();
        // $array_chatsIds = [];
        // foreach ($chats as $key => $chat) {
        //     $array_chatsIds[] = $chat->id;
        // }

        // $sum_issued = $advances->sum('date_issued');
        // $array_chatsIds = [];
        // foreach ($advances as $key => $adv) {
        //     $array_chatsIds[] = $adv->date_issued;
        // }

        // $messages = Message::whereIn('chat_id', $array_chatsIds)
        //     ->with(['chat.sender', 'chat.receiver', 'sender'])
        //    ->latest()->get();
        // $newMessages = collect(new MessageCollection($messages))->groupBy('chatId');
        // $allmessages = [];
        // foreach ($newMessages as $key => $groupAdvances) {
        //     $allmessages[] = $groupMAdvances0];
        // }

        $advs = AdvancePay::orderBy('date_issued', 'desc')->get();
        $advances = collect(new AdvancePayCollection($advs))->groupBy('dateIssued');
        $all_advances = [];
        $month_advances = [];
        foreach ($advances as $key => $groupAdvances) {
            $month_advances[] = $key;
        }

        // $sd = explode('-', $month_advances);

        return response()->json($month_advances);


        // return new AdvancePayCollection($advances);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $staff = Staff::findOrFail($request->employeeId);

        $advance = new AdvancePay();
        $advance->surname = $staff->surname;
        $advance->otherNames = $staff->otherNames;
        $advance->date_issued = $request->dateIssued;
        $advance->installments = $request->repayIn;
        $advance->unpaid_balance = 'Not Re-Paid';
        $advance->amount_borrowed = $request->amountBorrowed;
        $advance->status = $request->isStatus;
        $advance->staff_id = $staff->id;
        $advance->save();

        $resource = new ResourceAdvancePay($advance);
        event(new CreateAdvanceEvent($resource));

        return $resource;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $advances = AdvancePay::where(['id' => $id, 'date_issued', $d->date_issued])->with('staff')->orderBy('created_at', 'desc')->get();
        return new AdvancePayCollection($advances);
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
