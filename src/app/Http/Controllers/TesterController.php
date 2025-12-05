<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TesterController extends Controller
{
    /**
     * Show tester welcome page (first login)
     */
    public function welcome()
    {
        // Check if user is actually a tester
        if (!auth()->user()->isTester()) {
            abort(403, 'Access denied.');
        }

        return view('tester.welcome');
    }

    /**
     * Mark user as welcomed and redirect to dashboard
     */
    public function markWelcomed()
    {
        $user = auth()->user();
        
        if (!$user->isTester()) {
            abort(403, 'Access denied.');
        }

        $user->markTesterWelcomed();

        // Redirect to appropriate dashboard based on role
        if ($user->isEmployer()) {
            return redirect()->route('employer.dashboard')
                ->with('success', 'Selamat datang sebagai Tester AdoJobs!');
        } elseif ($user->isSeeker()) {
            return redirect()->route('seeker.dashboard')
                ->with('success', 'Selamat datang sebagai Tester AdoJobs!');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Show feedback form
     */
    public function feedbackForm()
    {
        if (!auth()->user()->isTester()) {
            abort(403, 'Only testers can access this page.');
        }

        return view('tester.feedback');
    }

    /**
     * Submit feedback
     */
    public function submitFeedback(Request $request)
    {
        if (!auth()->user()->isTester()) {
            abort(403, 'Only testers can submit feedback.');
        }

        $validated = $request->validate([
            'category' => 'required|in:' . implode(',', array_keys(Feedback::CATEGORIES)),
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $data = [
            'user_id' => auth()->id(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => Feedback::STATUS_NEW,
        ];

        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('feedbacks', 'public');
            $data['screenshot'] = $path;
        }

        Feedback::create($data);

        return redirect()->route('tester.feedback')
            ->with('success', 'Terima kasih! Feedback Anda telah berhasil dikirim.');
    }
}
