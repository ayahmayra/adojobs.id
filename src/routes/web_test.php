<?php

// Temporary test route
Route::get('/test-messages-data', function () {
    $user = \App\Models\User::where('email', 'seeker2@jobmaker.local')->first();
    Auth::login($user);
    
    $conversations = \App\Models\Conversation::with(['seeker.user', 'employer', 'job', 'lastMessage'])
        ->active()
        ->forSeeker($user->seeker->id)
        ->orderBy('last_message_at', 'desc')
        ->get();
    
    return response()->json([
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'conversations_count' => $conversations->count(),
        'conversations' => $conversations->map(function($conv) {
            return [
                'id' => $conv->id,
                'subject' => $conv->subject,
                'other_participant' => $conv->other_participant,
                'unread_count' => $conv->unread_count,
                'last_message_at' => $conv->last_message_at,
            ];
        }),
    ]);
});

