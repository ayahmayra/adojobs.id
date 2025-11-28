<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        
        // Load role-specific profile
        if ($user->isSeeker()) {
            $user->load('seeker');
        } elseif ($user->isEmployer()) {
            $user->load(['employer', 'employer.jobs']);
        }
        
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Load role-specific profile
        if ($user->isSeeker()) {
            $user->load('seeker');
        } elseif ($user->isEmployer()) {
            $user->load('employer');
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle avatar removal
        if ($request->has('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }
        // Handle avatar upload
        elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update seeker-specific information
     */
    public function updateSeeker(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user->isSeeker() || !$user->seeker) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'current_job_title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'job_type_preference' => ['nullable', 'in:full-time,part-time,contract,freelance,internship'],
            'preferred_location' => ['nullable', 'string', 'max:255'],
            'expected_salary_min' => ['nullable', 'numeric', 'min:0'],
            'expected_salary_max' => ['nullable', 'numeric', 'min:0'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'skills' => ['nullable', 'string'],
            'experience' => ['nullable', 'array'],
            'experience.*.job_title' => ['nullable', 'string', 'max:255'],
            'experience.*.company_name' => ['nullable', 'string', 'max:255'],
            'experience.*.start_date' => ['nullable', 'string'],
            'experience.*.end_date' => ['nullable', 'string'],
            'experience.*.is_current' => ['nullable', 'boolean'],
            'experience.*.description' => ['nullable', 'string'],
            'education' => ['nullable', 'array'],
            'education.*.degree' => ['nullable', 'string', 'max:255'],
            'education.*.institution' => ['nullable', 'string', 'max:255'],
            'education.*.field_of_study' => ['nullable', 'string', 'max:255'],
            'education.*.start_date' => ['nullable', 'string'],
            'education.*.end_date' => ['nullable', 'string'],
            'education.*.is_current' => ['nullable', 'boolean'],
            'education.*.description' => ['nullable', 'string'],
        ]);

        // Convert skills string to array
        if (isset($validated['skills'])) {
            $validated['skills'] = array_map('trim', explode(',', $validated['skills']));
        }

        // Clean up experience and education (remove empty entries)
        if (isset($validated['experience'])) {
            $validated['experience'] = array_filter($validated['experience'], function($exp) {
                return !empty($exp['job_title']) || !empty($exp['company_name']);
            });
            $validated['experience'] = array_values($validated['experience']); // Re-index
        }

        if (isset($validated['education'])) {
            $validated['education'] = array_filter($validated['education'], function($edu) {
                return !empty($edu['degree']) || !empty($edu['institution']);
            });
            $validated['education'] = array_values($validated['education']); // Re-index
        }

        $user->seeker->update($validated);

        return Redirect::route('profile.edit')->with('seeker-updated', true);
    }

    /**
     * Update employer-specific information
     */
    public function updateEmployer(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user->isEmployer() || !$user->employer) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company_website' => ['nullable', 'url', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $user->employer->update($validated);

        return Redirect::route('profile.edit')->with('employer-updated', true);
    }

    /**
     * Update employer slug
     */
    public function updateEmployerSlug(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user->isEmployer() || !$user->employer) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:employers,slug,' . $user->employer->id],
        ]);

        // Generate unique slug if needed
        $slug = $user->employer->generateUniqueSlug($validated['slug']);
        
        $user->employer->update(['slug' => $slug]);

        return Redirect::route('profile.show')->with('slug-updated', true);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
