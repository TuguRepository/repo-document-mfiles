<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DownloadRequest;

class RequestRejected extends Notification implements ShouldQueue
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
        $message = (new MailMessage)
            ->subject('Permintaan Download Dokumen Ditolak')
            ->greeting('Halo ' . $this->downloadRequest->user_name)
            ->line('Permintaan Anda untuk mengunduh dokumen berikut telah ditolak:')
            ->line('Dokumen: ' . $this->downloadRequest->document_title)
            ->line('Versi: ' . $this->downloadRequest->document_version);

        // Add rejection reason if available
        if ($this->downloadRequest->rejection_reason) {
            $message->line('Alasan Penolakan: ' . $this->downloadRequest->rejection_reason);
        }

        $message->action('Lihat Detail Permintaan', url("/download-requests/{$this->downloadRequest->id}"))
                ->line('Jika Anda memiliki pertanyaan, silakan hubungi administrator.');

        return $message;
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
            'type' => 'request_rejected',
            'document_title' => $this->downloadRequest->document_title,
            'document_id' => $this->downloadRequest->document_id,
            'document_version' => $this->downloadRequest->document_version,
            'rejection_reason' => $this->downloadRequest->rejection_reason
        ];
    }
}
