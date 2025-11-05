<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckResumeSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resume:check-slugs {--fix : Fix invalid resume slugs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and optionally fix resume slugs for seekers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking resume slugs...');
        $this->newLine();

        // Get all users with resume_slug
        $usersWithSlug = User::whereNotNull('resume_slug')
            ->where('resume_slug', '!=', '')
            ->get();

        $this->info("Found {$usersWithSlug->count()} users with resume slugs");
        $this->newLine();

        $issues = [];
        $fixed = 0;

        foreach ($usersWithSlug as $user) {
            $issuesForUser = [];

            // Check if user is seeker
            if (!$user->isSeeker()) {
                $issuesForUser[] = "User is not a seeker (role: {$user->role})";
            }

            // Check if user is active
            if (!$user->is_active) {
                $issuesForUser[] = "User is not active";
            }

            // Check if user has seeker profile
            if (!$user->seeker) {
                $issuesForUser[] = "User has no seeker profile";
            }

            if (!empty($issuesForUser)) {
                $issues[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'slug' => $user->resume_slug,
                    'issues' => $issuesForUser,
                ];
            }
        }

        if (empty($issues)) {
            $this->info('✅ All resume slugs are valid!');
            return 0;
        }

        $this->warn("Found " . count($issues) . " users with invalid resume slugs:");
        $this->newLine();

        // Display issues
        $headers = ['User ID', 'Name', 'Email', 'Slug', 'Issues'];
        $rows = [];
        foreach ($issues as $issue) {
            $rows[] = [
                $issue['user_id'],
                $issue['name'],
                $issue['email'],
                $issue['slug'],
                implode(', ', $issue['issues']),
            ];
        }
        $this->table($headers, $rows);

        // Fix option
        if ($this->option('fix')) {
            $this->newLine();
            if ($this->confirm('Do you want to clear invalid resume slugs?', false)) {
                foreach ($issues as $issue) {
                    $user = User::find($issue['user_id']);
                    if ($user) {
                        $oldSlug = $user->resume_slug;
                        $user->resume_slug = null;
                        $user->save();
                        $this->line("  ✓ Cleared slug '{$oldSlug}' for user {$user->name} (ID: {$user->id})");
                        $fixed++;
                    }
                }
                $this->newLine();
                $this->info("✅ Fixed {$fixed} resume slugs");
            }
        } else {
            $this->newLine();
            $this->info('Run with --fix option to clear invalid resume slugs');
        }

        return 0;
    }
}
