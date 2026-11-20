<?php

namespace App\Jobs;

use App\Mail\InviteAdminMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAdminInvitationJob implements ShouldQueue
{
    use Queueable;

    public User $user;
    public string $passwordPlain;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $passwordPlain)
    {
        $this->user = $user;
        $this->passwordPlain = $passwordPlain;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(
            new InviteAdminMail(
                $this->user->name,
                $this->user->username ?? $this->user->email,
                $this->passwordPlain
            )
        );
    }
}
