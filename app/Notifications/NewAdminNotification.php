<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $newAdmin;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $newAdmin)
    {
        $this->newAdmin = $newAdmin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemberitahuan: Akun Admin Baru Ditambahkan')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Akun administrator baru telah berhasil dibuat di sistem Literasi Tamsel.')
            ->line('Nama Admin: ' . $this->newAdmin->name)
            ->line('Email: ' . $this->newAdmin->email)
            ->line('Username: ' . ($this->newAdmin->username ?? '-'))
            ->action('Lihat Daftar Admin', route('admin.users.index'))
            ->line('Notifikasi ini dikirimkan secara otomatis oleh sistem.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Admin Baru Ditambahkan',
            'message' => 'Admin ' . $this->newAdmin->name . ' (' . $this->newAdmin->email . ') telah berhasil ditambahkan.',
            'admin_id' => $this->newAdmin->id,
            'admin_name' => $this->newAdmin->name,
            'admin_email' => $this->newAdmin->email,
        ];
    }
}
