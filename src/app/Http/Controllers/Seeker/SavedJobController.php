<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    /**
     * Display saved jobs
     */
    public function index()
    {
        $seeker = auth()->user()->seeker;

        if (!$seeker) {
            return redirect()->route('seeker.dashboard')
                ->with('error', 'Please complete your profile first.');
        }

        $savedJobs = $seeker->savedJobs()
            ->with(['job.employer', 'job.category'])
            ->latest()
            ->paginate(20);

        return view('seeker.saved-jobs', compact('savedJobs'));
    }

    /**
     * Toggle save/unsave job (AJAX)
     */
    public function toggle(Job $job)
    {
        $seeker = auth()->user()->seeker;

        if (!$seeker) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete your profile first.'
            ], 400);
        }

        $savedJob = $seeker->savedJobs()->where('job_id', $job->id)->first();

        if ($savedJob) {
            // Unsave
            $savedJob->delete();
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Job removed from saved list.'
            ]);
        } else {
            // Save
            $seeker->savedJobs()->create([
                'job_id' => $job->id,
            ]);
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Job saved successfully!'
            ]);
        }
    }

    /**
     * Remove saved job
     */
    public function destroy(SavedJob $savedJob)
    {
        // Authorization check
        if ($savedJob->seeker_id !== auth()->user()->seeker->id) {
            abort(403);
        }

        $savedJob->delete();

        return redirect()->back()->with('success', 'Job removed from saved list.');
    }
}

