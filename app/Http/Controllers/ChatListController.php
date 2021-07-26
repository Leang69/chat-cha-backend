<?php

namespace App\Http\Controllers;

use App\Models\ChatList;
use Illuminate\Http\Request;

class ChatListController extends Controller
{
    //
    public function lassMassage(Request $request){
        $messageHistory = ChatList::where('user_id',$request->user()->id)->get();

        $messages = $messageHistory->map(function ($item, $key){
            $message = $item->lassMassage;
            return [
                "content" => $message->content,
                "sender" => $message->senderInfo->only(['id', 'username']),
                "receiver" => $message->receiverInfo->only(['id', 'username'])
            ];
        });

        return response()->json(['message' => $messages]);
    }
}
