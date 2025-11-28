<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display the public resume page
     */
    public function show(string $slug)
    {
        // First, try to find user by slug (without strict filters)
        $user = User::where('resume_slug', $slug)
            ->with('seeker')
            ->first();
        
        // If user not found, log and return 404
        if (!$user) {
            \Log::warning('Resume slug not found', [
                'slug' => $slug,
                'request_url' => request()->fullUrl(),
            ]);
            abort(404, 'Resume tidak ditemukan. Slug tidak valid.');
        }
        
        // Check if user is a seeker
        if (!$user->isSeeker()) {
            \Log::warning('Resume access denied - user is not seeker', [
                'slug' => $slug,
                'user_id' => $user->id,
                'user_role' => $user->role,
            ]);
            abort(404, 'Resume tidak ditemukan. User bukan pencari kerja.');
        }
        
        // Check if user is active
        if (!$user->is_active) {
            \Log::warning('Resume access denied - user is not active', [
                'slug' => $slug,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]);
            abort(404, 'Resume tidak ditemukan. Profil tidak aktif.');
        }
        
        // Check if user has seeker profile
        if (!$user->seeker) {
            \Log::warning('Resume access denied - user has no seeker profile', [
                'slug' => $slug,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]);
            abort(404, 'Resume tidak ditemukan. Profil pencari kerja tidak lengkap.');
        }
        
        // Check if employer has favorited this seeker
        $isFavorite = false;
        if (auth()->check() && auth()->user()->isEmployer() && auth()->user()->employer) {
            $isFavorite = auth()->user()->employer->savedCandidates()
                ->where('seeker_id', $user->seeker->id)
                ->exists();
        }
        
        return view('resume.show', compact('user', 'isFavorite'));
    }
}
