<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DownloadRequest;

class RequestApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The download request instance
     *
     * @var \App\Models\DownloadRequest
     */
    protected $downloadRequest;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\DownloadRequest  $downloadRequest
     * @return void
     */
    public function __construct(DownloadRequest $downloadRequest)
    {
        $this->downloadRequest = $downloadRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $downloadUrl = url("/download/{$this->downloadRequest->token}");

        return (new MailMessage)
            ->subject('Permintaan Download Dokumen Disetujui')
            ->greeting('Halo ' . $this->downloadRequest->user_name)
            ->line('Permintaan Anda untuk mengunduh dokumen berikut telah disetujui:')
            ->line('Dokumen: ' . $this->downloadRequest->document_title)
            ->line('Versi: ' . $this->downloadRequest->document_version)
            ->action('Unduh Dokumen', $downloadUrl)
            ->line('Tautan download ini akan aktif hingga: ' . $this->downloadRequest->token_expires_at->format('d M Y H:i'))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->downloadRequest->id,
            'type' => 'request_approved',
            'document_title' => $this->downloadRequest->document_title,
            'document_id' => $this->downloadRequest->document_id,
            'document_version' => $this->downloadRequest->document_version,
            'expires_at' => $this->downloadRequest->token_expires_at ? $this->downloadRequest->token_expires_at->toIso8601String() : null,
            'download_token' => $this->downloadRequest->token
        ];
    }
}
