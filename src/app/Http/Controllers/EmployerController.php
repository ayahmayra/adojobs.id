<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    /**
     * Display a listing of employers/recruiters.
     */
    public function index(Request $request)
    {
        $query = Employer::with('user')
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            });

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('company_description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        $employers = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('employers.index', compact('employers'));
    }

    /**
     * Display the specified employer profile.
     */
    public function show(Employer $employer)
    {
        $employer->load(['user', 'jobs' => function($query) {
            $query->where('status', 'published')
                  ->where('application_deadline', '>=', now())
                  ->latest('published_at')
                  ->take(5);
        }]);

        return view('employers.show', compact('employer'));
    }
}

