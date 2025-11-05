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
        $user = User::where('resume_slug', $slug)
            ->where('role', 'seeker')
            ->where('is_active', true)
            ->with('seeker')
            ->firstOrFail();
        
        // Check if user has seeker profile
        if (!$user->seeker) {
            abort(404, 'Resume not found');
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
