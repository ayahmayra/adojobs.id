<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seeker;
use App\Models\Employer;
use App\Models\Job;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing seekers, employers, and jobs
        $seekers = Seeker::with('user')->get();
        $employers = Employer::get();
        $jobs = Job::where('status', 'published')->get();

        if ($seekers->isEmpty() || $employers->isEmpty()) {
            $this->command->warn('No seekers or employers found. Please run UserSeeder first.');
            return;
        }

        // Create conversations
        $conversationsCreated = 0;
        $messagesCreated = 0;

        foreach ($seekers->take(5) as $seeker) {
            foreach ($employers->take(2) as $employer) {
                // Get a random job from this employer if available
                $job = $jobs->where('employer_id', $employer->id)->first();
                
                if (!$job) {
                    continue;
                }

                // Create conversation
                $conversation = Conversation::create([
                    'seeker_id' => $seeker->id,
                    'employer_id' => $employer->id,
                    'job_id' => $job->id,
                    'subject' => 'Re: ' . $job->title,
                    'last_message_at' => now()->subDays(rand(0, 7)),
                    'seeker_unread_count' => rand(0, 3),
                    'employer_unread_count' => rand(0, 2),
                    'is_archived' => false,
                ]);

                $conversationsCreated++;

                // Create some messages in this conversation
                $messageCount = rand(3, 8);
                
                for ($i = 0; $i < $messageCount; $i++) {
                    $isFromSeeker = $i % 2 === 0;
                    $senderId = $isFromSeeker ? $seeker->user_id : $employer->user_id;
                    $senderType = $isFromSeeker ? 'seeker' : 'employer';
                    
                    $sampleMessages = [
                        'Halo, saya tertarik dengan posisi ini. Bisakah Anda memberikan informasi lebih lanjut?',
                        'Terima kasih atas minat Anda. Kami akan menghubungi Anda segera.',
                        'Kapan waktu yang tepat untuk interview?',
                        'Kami bisa jadwalkan interview minggu depan. Apakah Anda tersedia?',
                        'Saya tersedia hari Senin atau Rabu.',
                        'Baik, saya akan mengatur jadwal interview untuk hari Rabu jam 10 pagi.',
                        'Terima kasih banyak!',
                        'Sama-sama. Sampai jumpa di interview.',
                    ];
                    
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_id' => $senderId,
                        'sender_type' => $senderType,
                        'message' => $sampleMessages[min($i, count($sampleMessages) - 1)],
                        'read_at' => $i < $messageCount - 2 ? now()->subDays(rand(0, 5)) : null,
                        'created_at' => now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                    ]);
                    
                    $messagesCreated++;
                }
            }
        }

        $this->command->info("Created {$conversationsCreated} conversations with {$messagesCreated} messages.");
    }
}

