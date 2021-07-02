<?php

namespace App\Http\Controllers;

use App\Models\ChatList;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    function sendMassage(Request $request) {

        if ($request->to_user_id == $request->user()->id){
            return response()->json(['message' => 'chat to yourseft'],406);
        }

        if (!User::find($request->to_user_id)){
            return response()->json(['message' => 'not found this user'],406);
        }

        $message = new Message;
        $message->content = $request->content_massage;
        $message->from_user_id = $request->user()->id;
        $message->to_user_id = $request->to_user_id;
        $message->save();

        $lastMessageAB = ChatList::firstOrNew(
            ['user_id' => $request->user()->id , 'with_user_id' => $request->to_user_id]
        );
        $lastMessageAB->message_id = $message->id;
        $lastMessageAB->save();

        $lastMessageBA = ChatList::firstOrNew(
            ['with_user_id' => $request->user()->id , 'user_id' => $request->to_user_id]
        );
        $lastMessageBA->message_id = $message->id;
        $lastMessageBA->save();

        return response()->json(['message' => 'success']);
    }

    function getMassage(Request $request){
        $you = $request->with_user_id;

        if ($you == $request->user()->id){
            return response()->json(['message' => 'chat to yourseft'],406);
        }

        $me2you = $request->user()->message_send->where('to_user_id',$you);
        $you2me = $request->user()->message_receive->where('from_user_id',$you);

        $youNmeAllChat = $me2you->merge($you2me)->sortBy('created_at')->values();

        return response()->json(['message' => $youNmeAllChat]);
    }
}

