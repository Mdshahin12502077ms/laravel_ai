<?php

namespace App\Http\Controllers;

use App\Ai\Agents\CvAgent;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvController extends Controller
{
    public function index()
    {
        return view('cv-generator');
    }

    public function chat(Request $request)
    {
        $message = $request->message;
        $conversationId = $request->conversation_id;
        
        $agent = new CvAgent();

        if ($conversationId) {
            $response = $agent
                ->continue($conversationId, as: Auth::user() ?: session()->getId())
                ->prompt($message);
        } else {
            $response = $agent
                ->forUser(Auth::user() ?: session()->getId())
                ->prompt($message);
        }

        return response()->json([
            'reply' => (string) $response,
            'conversation_id' => $response->conversationId
        ]);
    }

    public function show(Resume $resume)
    {
        // Simple authorization check (optional depending on privacy needs)
        if ($resume->user_id && $resume->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('cv-show', ['resume' => $resume]);
    }
}
