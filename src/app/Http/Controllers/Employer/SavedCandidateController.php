<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Seeker;
use App\Models\SavedCandidate;
use Illuminate\Http\Request;

class SavedCandidateController extends Controller
{
    /**
     * Display saved candidates (seekers)
     */
    public function index()
    {
        $employer = auth()->user()->employer;

        if (!$employer) {
            return redirect()->route('employer.dashboard')
                ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
        }

        $savedCandidates = $employer->savedCandidates()
            ->with(['seeker.user'])
            ->latest()
            ->paginate(20);

        return view('employer.saved-candidates', compact('savedCandidates'));
    }

    /**
     * Toggle save/unsave candidate (seeker) (AJAX)
     */
    public function toggle(Seeker $seeker)
    {
        $employer = auth()->user()->employer;

        if (!$employer) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan lengkapi profil perusahaan terlebih dahulu.'
            ], 400);
        }

        // Check if seeker exists and is active
        if (!$seeker->user || !$seeker->user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan atau tidak aktif.'
            ], 404);
        }

        $savedCandidate = $employer->savedCandidates()
            ->where('seeker_id', $seeker->id)
            ->first();

        if ($savedCandidate) {
            // Unsave
            $savedCandidate->delete();
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Kandidat dihapus dari favorit.'
            ]);
        } else {
            // Save
            $employer->savedCandidates()->create([
                'seeker_id' => $seeker->id,
            ]);
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Kandidat ditambahkan ke favorit!'
            ]);
        }
    }

    /**
     * Remove saved candidate
     */
    public function destroy(SavedCandidate $savedCandidate)
    {
        // Authorization check
        if ($savedCandidate->employer_id !== auth()->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $savedCandidate->delete();

        return redirect()->route('employer.saved-candidates.index')
            ->with('success', 'Kandidat dihapus dari favorit.');
    }
}
