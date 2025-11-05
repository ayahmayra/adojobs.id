<?php

namespace App\Http\Controllers;

use App\Models\Seeker;
use Illuminate\Http\Request;

class SeekerController extends Controller
{
    /**
     * Display a listing of seekers/candidates.
     */
    public function index(Request $request)
    {
        $query = Seeker::with('user')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('current_job_title', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%");
            })->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by skills
        if ($request->has('skill') && $request->skill) {
            $query->where('skills', 'like', "%{$request->skill}%");
        }

        // Filter by location
        if ($request->has('location') && $request->location) {
            $query->where(function($q) use ($request) {
                $q->where('city', 'like', "%{$request->location}%")
                  ->orWhere('preferred_location', 'like', "%{$request->location}%");
            });
        }

        $seekers = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get favorite seeker IDs for current employer (if logged in as employer)
        $favoriteSeekerIds = [];
        if (auth()->check() && auth()->user()->isEmployer() && auth()->user()->employer) {
            $favoriteSeekerIds = auth()->user()->employer->savedCandidates()
                ->pluck('seeker_id')
                ->toArray();
        }

        return view('seekers.index', compact('seekers', 'favoriteSeekerIds'));
    }

    /**
     * Display the specified seeker profile.
     */
    public function show(Seeker $seeker)
    {
        $seeker->load(['user', 'applications' => function($query) {
            $query->with('job')
                  ->latest()
                  ->take(5);
        }]);

        // Check if employer has favorited this seeker
        $isFavorite = false;
        if (auth()->check() && auth()->user()->isEmployer() && auth()->user()->employer) {
            $isFavorite = auth()->user()->employer->savedCandidates()
                ->where('seeker_id', $seeker->id)
                ->exists();
        }

        return view('seekers.show', compact('seeker', 'isFavorite'));
    }
}

