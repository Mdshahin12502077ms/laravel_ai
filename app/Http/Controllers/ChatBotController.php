<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatBotAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

   public function send(Request $request)
{
    $message = $request->message;
    $conversationId = $request->conversation_id;

    
    $agent = new ChatBotAgent();

    if ($conversationId) {

        $response = $agent
            ->continue($conversationId, as: Auth::user())
            ->prompt($message);

    } else {

        $response = $agent
            ->forUser(Auth::user())
            ->prompt($message);
    }

    return response()->json([
        'reply' => (string) $response,
        'conversation_id' => $response->conversationId
    ]);
}
}
