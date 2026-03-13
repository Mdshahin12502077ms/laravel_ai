<?php

namespace App\Http\Controllers;

use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Transcription;

class TranscriptController extends Controller
{
    public function index()
    {
        return view('transcribe');
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,webm,ogg,m4a,flac|max:25600',
            'language' => 'nullable|string|max:5',
        ]);

        $file = $request->file('audio');

        // Store to temp location so we can pass a real file path to OpenAI
        $tempPath = $file->store('temp-audio', 'local');
        $fullPath = storage_path('app/private/' . $tempPath);

        try {
            $response = Transcription::fromPath($fullPath, $file->getClientMimeType())
                ->language($request->input('language', 'en'))
                ->timeout(120)
                ->generate();

            $transcript = Transcript::create([
                'user_id' => Auth::id(),
                'original_filename' => $file->getClientOriginalName(),
                'language' => $request->input('language', 'en'),
                'text' => $response->text,
            ]);

            return redirect()->route('transcript.show', $transcript)->with('success', 'Audio transcribed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['audio' => 'Transcription failed: ' . $e->getMessage()]);
        } finally {
            // Clean up temp file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    public function show(Transcript $transcript)
    {
        return view('transcript-show', ['transcript' => $transcript]);
    }
}

