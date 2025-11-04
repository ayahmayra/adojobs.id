<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates the main admin user for AdoJobs.id platform.
     * This seeder MUST run first before all other seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating admin user...');

        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@adojobs.id')->first();
        
        if ($existingAdmin) {
            $this->command->warn('⚠ Admin user already exists! Updating...');
            
            $existingAdmin->update([
                'name' => 'Admin AdoJobs',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('✓ Admin user updated successfully!');
        } else {
            // Create new admin user
            $admin = User::create([
                'name' => 'Admin AdoJobs',
                'email' => 'admin@adojobs.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'phone' => '+62 812-3456-7890',
                'address' => 'Bengkalis, Riau, Indonesia',
            ]);

            $this->command->info('✓ Admin user created successfully!');
        }

        // Display admin credentials
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('  Admin Credentials');
        $this->command->info('═══════════════════════════════════════');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Email', 'admin@adojobs.id'],
                ['Password', 'password123'],
                ['Role', 'admin'],
                ['Status', 'Active'],
            ]
        );
        $this->command->info('═══════════════════════════════════════');
        $this->command->warn('⚠ IMPORTANT: Change password after first login!');
        $this->command->info('');
    }
}
