<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Fix Resume Slugs Script ===\n\n";

// 1. Check all users without resume_slug
$usersWithoutSlug = User::whereNull('resume_slug')->get();
echo "Users without resume_slug: {$usersWithoutSlug->count()}\n";

if ($usersWithoutSlug->count() > 0) {
    echo "Generating resume slugs for users without them...\n";
    foreach ($usersWithoutSlug as $user) {
        $user->resume_slug = User::generateResumeSlug($user->email);
        $user->save();
        echo "- {$user->name} ({$user->email}) → {$user->resume_slug}\n";
    }
}

// 2. Check for duplicate resume_slugs
$duplicates = User::select('resume_slug')
    ->whereNotNull('resume_slug')
    ->groupBy('resume_slug')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicates->count() > 0) {
    echo "\nFound duplicate resume_slugs:\n";
    foreach ($duplicates as $dup) {
        $users = User::where('resume_slug', $dup->resume_slug)->get();
        echo "- Slug '{$dup->resume_slug}' used by: ";
        foreach ($users as $user) {
            echo "{$user->name} ({$user->email}) ";
        }
        echo "\n";
    }
}

// 3. Check specific slug
$targetSlug = 'dewi-kartika-resume';
$targetUser = User::where('resume_slug', $targetSlug)->first();

if ($targetUser) {
    echo "\n✅ Found user with slug '{$targetSlug}':\n";
    echo "- Name: {$targetUser->name}\n";
    echo "- Email: {$targetUser->email}\n";
    echo "- Role: {$targetUser->role}\n";
    echo "- Active: " . ($targetUser->is_active ? 'Yes' : 'No') . "\n";
    echo "- Has Seeker: " . ($targetUser->seeker ? 'Yes' : 'No') . "\n";
    
    if (!$targetUser->isSeeker()) {
        echo "❌ User is not a seeker!\n";
    } elseif (!$targetUser->is_active) {
        echo "❌ User is not active!\n";
    } elseif (!$targetUser->seeker) {
        echo "❌ User has no seeker profile!\n";
    } else {
        echo "✅ User should be accessible via resume URL\n";
    }
} else {
    echo "\n❌ No user found with slug '{$targetSlug}'\n";
    
    // Look for similar names
    $similarUsers = User::where('name', 'like', '%dewi%')
        ->orWhere('name', 'like', '%kartika%')
        ->get();
    
    if ($similarUsers->count() > 0) {
        echo "Found users with similar names:\n";
        foreach ($similarUsers as $user) {
            echo "- {$user->name} ({$user->email}) - slug: {$user->resume_slug}\n";
        }
    }
}

echo "\n=== Script completed ===\n";
