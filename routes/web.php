<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\TranscriptController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/chatbot', [ChatBotController::class, 'index'])->name('chatbot');
Route::post('/chat/send', [ChatBotController::class, 'send']);

Route::get('/cv-generator', [CvController::class, 'index'])->name('cv-generator');
Route::post('/cv/chat', [CvController::class, 'chat']);
Route::get('/cv/{resume}', [CvController::class, 'show'])->name('cv.show');

Route::get('/transcribe', [TranscriptController::class, 'index'])->name('transcribe');
Route::post('/transcribe', [TranscriptController::class, 'store'])->name('transcribe.store');
Route::get('/transcript/{transcript}', [TranscriptController::class, 'show'])->name('transcript.show');

require __DIR__.'/auth.php';
