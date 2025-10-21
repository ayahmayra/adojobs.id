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
        
        return view('resume.show', compact('user'));
    }
}
