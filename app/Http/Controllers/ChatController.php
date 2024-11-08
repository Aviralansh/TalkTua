<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        
        // Call Vultr serverless function
        $response = Http::post(env('https://api.vultrinference.com/v1/chat/completions/RAG'), [
            'message' => $message,
            'user_id' => $request->session()->getId()
        ]);
        
        return response()->json([
            'message' => $response->json('message'),
            'timestamp' => now()->format('H:i')
        ]);
    }
}