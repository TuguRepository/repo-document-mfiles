<?php

namespace App\Listeners;

use App\Events\NewDownloadRequest;
use App\Models\User;
use App\Notifications\NewDownloadRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewDownloadRequestListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewDownloadRequest  $event
     * @return void
     */
    public function handle(NewDownloadRequest $event)
    {
        // Get the download request
        $downloadRequest = $event->downloadRequest;

        // Get all admin users who should be notified
        $admins = User::role('admin')
            ->where('email_notifications', true) // Only notify admins who want email notifications
            ->get();

        // Notify each admin
        foreach ($admins as $admin) {
            $admin->notify(new NewDownloadRequestNotification($downloadRequest));
        }
    }
}
