<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ChatController.php
    public function fetchMessages($id)
    {
        // Fetch messages for the given user ID
        $messages = Message::where('receiver_id', $id)
                            ->orWhere('sender_id', $id)
                            ->get();
        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request, $id)
    {
        $message = new Message();
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $id;
        $message->message = $request->input('message');
        $message->save();
    
        return response()->json(['success' => true]);

    }
}
