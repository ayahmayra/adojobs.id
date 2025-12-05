<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query()->with(['seeker', 'employer']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,employer,seeker',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $user->load(['seeker', 'employer']);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,employer,seeker',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar removal
        if ($request->has('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = null;
        }
        // Handle avatar upload
        elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Get related data counts for logging
        $relatedData = $user->getRelatedDataCounts();
        $userName = $user->name;
        $userEmail = $user->email;
        
        // Delete user (cascade will handle related data via database constraints)
        $user->delete();
        
        // Build success message with details
        $message = "User '{$userName}' ({$userEmail}) has been deleted successfully.";
        
        if (!empty($relatedData)) {
            $details = [];
            foreach ($relatedData as $type => $count) {
                $label = str_replace('_', ' ', $type);
                $details[] = "{$count} {$label}";
            }
            $message .= " Related data deleted: " . implode(', ', $details) . ".";
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Get related data for a user (AJAX endpoint)
     */
    public function getRelatedData(User $user)
    {
        return response()->json([
            'has_related_data' => $user->hasRelatedData(),
            'related_data' => $user->getRelatedDataCounts(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return redirect()->back()
            ->with('success', 'User status updated successfully.');
    }

    /**
     * Toggle user tester status
     */
    public function toggleTester(User $user)
    {
        $newStatus = !$user->is_tester;
        
        // If removing tester status, also clear the welcomed_at timestamp
        $updateData = ['is_tester' => $newStatus];
        if (!$newStatus) {
            $updateData['tester_welcomed_at'] = null;
        }
        
        $user->update($updateData);

        $message = $newStatus 
            ? "User '{$user->name}' telah ditambahkan sebagai Tester."
            : "User '{$user->name}' telah dihapus dari Tester.";

        return redirect()->back()->with('success', $message);
    }
}

