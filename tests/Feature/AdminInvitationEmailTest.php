<?php

namespace Tests\Feature;

use App\Jobs\SendAdminInvitationJob;
use App\Mail\InviteAdminMail;
use App\Models\User;
use App\Notifications\NewAdminNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminInvitationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_admin_and_dispatches_invitation_job(): void
    {
        Queue::fake();
        Notification::fake();

        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
            'privileges' => ['dashboard', 'publications'],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'username' => 'budisantoso',
        ]);

        Queue::assertPushed(SendAdminInvitationJob::class, function ($job) {
            return $job->user->email === 'budi@example.com' && $job->passwordPlain === 'secret123';
        });

        Notification::assertSentTo($superAdmin, NewAdminNotification::class, function ($notification) {
            return $notification->newAdmin->email === 'budi@example.com';
        });
    }

    public function test_job_sends_invite_admin_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'role' => 'admin',
        ]);

        $job = new SendAdminInvitationJob($user, 'secret123');
        $job->handle();

        Mail::assertSent(InviteAdminMail::class, function ($mail) {
            return $mail->hasTo('budi@example.com') &&
                   $mail->nama === 'Budi Santoso' &&
                   $mail->passwordPlain === 'secret123';
        });
    }
}
