<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of feedbacks
     */
    public function index(Request $request)
    {
        $query = Feedback::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $feedbacks = $query->latest()->paginate(20);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Display a specific feedback
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('user');
        return view('admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Update feedback status
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $feedback->update($validated);

        return redirect()->back()
            ->with('success', 'Status feedback berhasil diperbarui.');
    }
}
