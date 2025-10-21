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

        return view('seekers.index', compact('seekers'));
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

        return view('seekers.show', compact('seeker'));
    }
}

