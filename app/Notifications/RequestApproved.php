<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\DownloadRequest;

class RequestApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $downloadRequest;

    /**
     * Create a new notification instance.
     *
     * @param DownloadRequest $downloadRequest
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
        // Ubah ini menjadi ['database'] saja untuk sementara
        // untuk menghindari pengiriman email langsung melalui notifikasi
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
{
    try {
        $url = route('download.file', ['token' => $this->downloadRequest->token ?? 'no-token']);

        return (new MailMessage)
            ->subject('Permintaan Dokumen Anda Telah Disetujui')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Permintaan unduhan dokumen Anda telah disetujui.')
            ->line('ID Permintaan: ' . $this->downloadRequest->id)
            ->line('Tanggal Persetujuan: ' . $this->downloadRequest->approved_at)
            ->action('Unduh Dokumen', $url)
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    } catch (\Exception $e) {
        \Log::error('Error creating mail notification: ' . $e->getMessage());

        // Return a simplified mail message in case of error
        return (new MailMessage)
            ->subject('Permintaan Dokumen Disetujui')
            ->line('Permintaan Anda telah disetujui.');
    }
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
            'download_request_id' => $this->downloadRequest->id,
            'status' => 'approved',
            'approved_at' => $this->downloadRequest->approved_at,
        ];
    }
}
