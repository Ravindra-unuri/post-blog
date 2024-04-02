<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // Create a new message
        $message = new Message();
        $message->sender_id = auth()->user()->id;
        $message->receiver_id = $validatedData['receiver_id'];
        $message->message = $validatedData['message'];
        $message->save();

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function getMessages(Request $request)
    {
        // Retrieve messages between the authenticated user and another user
        $messages = Message::where(function ($query) use ($request) {
            $query->where('sender_id', $request->input('sender_id'))
                ->orWhere('receiver_id', auth()->user()->id);
        })->get();

        return response()->json(['messages' => $messages]);
    }
}
