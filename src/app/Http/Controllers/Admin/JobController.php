<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of jobs
     */
    public function index(Request $request)
    {
        $query = Job::query()
            ->with(['employer', 'category'])
            ->withCount('applications');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $jobs = $query->latest()->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the specified job
     */
    public function show(Job $job)
    {
        $job->load(['employer', 'category', 'applications.seeker.user']);
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Remove the specified job
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    /**
     * Toggle job featured status
     */
    public function toggleFeatured(Job $job)
    {
        $job->update(['is_featured' => !$job->is_featured]);

        return redirect()->back()
            ->with('success', 'Status featured lowongan berhasil diubah.');
    }

    /**
     * Update job status
     */
    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:draft,published,closed,filled'
        ]);

        $job->update([
            'status' => $request->status,
            'published_at' => $request->status === 'published' && !$job->published_at ? now() : $job->published_at
        ]);

        return redirect()->back()
            ->with('success', 'Status lowongan berhasil diubah.');
    }
}

