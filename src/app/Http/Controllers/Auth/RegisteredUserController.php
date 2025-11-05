<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:seeker,employer'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => false, // New users need admin activation
            'resume_slug' => User::generateResumeSlug($request->email), // Auto generate resume slug
        ]);

        // Create role-specific profile
        if ($request->role === 'employer') {
            $user->employer()->create([
                'company_name' => $request->name . "'s Company", // Placeholder
            ]);
        } elseif ($request->role === 'seeker') {
            $user->seeker()->create([
                // Placeholder seeker profile
            ]);
        }

        event(new Registered($user));

        // Don't auto-login if user is not active
        // Redirect to pending activation page
        return redirect()->route('account.pending')
            ->with('email', $user->email)
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu aktivasi dari admin.');
    }
}
