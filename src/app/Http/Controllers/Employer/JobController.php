<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of employer's jobs
     */
    public function index()
    {
        $employer = auth()->user()->employer;
        $jobs = $employer->jobs()
            ->with(['category'])
            ->withCount('applications')
            ->latest()
            ->paginate(20);

        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('employer.jobs.create', compact('categories'));
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'job_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'work_mode' => 'required|in:on-site,remote,hybrid',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'required|in:hourly,daily,monthly,yearly',
            'salary_negotiable' => 'boolean',
            'experience_level' => 'nullable|string|max:255',
            'experience_years_min' => 'nullable|integer|min:0',
            'experience_years_max' => 'nullable|integer|min:0',
            'education_level' => 'nullable|string|max:255',
            'required_skills' => 'nullable|array',
            'preferred_skills' => 'nullable|array',
            'vacancies' => 'required|integer|min:1',
            'application_deadline' => 'required|date|after:today',
            'status' => 'required|in:draft,published',
        ]);

        $validated['employer_id'] = auth()->user()->employer->id;
        $validated['country'] = 'Indonesia';
        $validated['salary_currency'] = 'IDR';
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Job::create($validated);

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified job (Employer management view)
     */
    public function show(Job $job)
    {
        // Check if job belongs to current employer
        if ($job->employer_id !== auth()->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $job->load(['category', 'applications.seeker.user']);
        $job->loadCount('applications');
        
        // Get recent applications (last 5)
        $recentApplications = $job->applications()
            ->with('seeker.user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get application counts by status
        $shortlistedCount = $job->applications()->where('status', 'shortlisted')->count();
        $hiredCount = $job->applications()->where('status', 'hired')->count();
        
        return view('employer.jobs.show', compact(
            'job', 
            'recentApplications',
            'shortlistedCount',
            'hiredCount'
        ));
    }

    /**
     * Show the form for editing the specified job
     */
    public function edit(Job $job)
    {
        // Check if job belongs to current employer
        if ($job->employer_id !== auth()->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = Category::active()->ordered()->get();
        
        return view('employer.jobs.edit', compact('job', 'categories'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, Job $job)
    {
        // Check if job belongs to current employer
        if ($job->employer_id !== auth()->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'job_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'work_mode' => 'required|in:on-site,remote,hybrid',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'required|in:hourly,daily,monthly,yearly',
            'salary_negotiable' => 'boolean',
            'experience_level' => 'nullable|string|max:255',
            'experience_years_min' => 'nullable|integer|min:0',
            'experience_years_max' => 'nullable|integer|min:0',
            'education_level' => 'nullable|string|max:255',
            'required_skills' => 'nullable|array',
            'preferred_skills' => 'nullable|array',
            'vacancies' => 'required|integer|min:1',
            'application_deadline' => 'required|date',
            'status' => 'required|in:draft,published,closed',
        ]);

        if ($validated['status'] === 'published' && !$job->published_at) {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        return redirect()->route('employer.jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job
     */
    public function destroy(Job $job)
    {
        // Check if job belongs to current employer
        if ($job->employer_id !== auth()->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }
}

