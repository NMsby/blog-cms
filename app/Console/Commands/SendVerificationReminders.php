<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\VerificationReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendVerificationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:verification-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email verification reminders to unverified users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull(`email_verified_at`)
            ->where('created_at', '<=', Carbon::now()->subDays(2))
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->get();

        foreach ($users as $user) {
            $user->notify(new VerificationReminderNotification());
        }

        $this->info("Sent {$users->count()} verification reminders.");
    }
}
