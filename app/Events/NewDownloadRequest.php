<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DownloadRequest;

class NewDownloadRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The download request instance.
     *
     * @var \App\Models\DownloadRequest
     */
    public $downloadRequest;

    /**
     * Broadcast data
     *
     * @var array
     */
    public $broadcastData;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\DownloadRequest  $downloadRequest
     * @return void
     */
    public function __construct(DownloadRequest $downloadRequest)
    {
        $this->downloadRequest = $downloadRequest;

        // Prepare data for broadcasting (smaller payload)
        $this->broadcastData = [
            'id' => $downloadRequest->id,
            'user' => [
                'id' => $downloadRequest->user_id,
                'name' => $downloadRequest->user_name,
                'email' => $downloadRequest->user_email
            ],
            'document' => [
                'id' => $downloadRequest->document_id,
                'title' => $downloadRequest->document_title,
                'number' => $downloadRequest->document_number ?? 'N/A',
                'version' => $downloadRequest->document_version,
            ],
            'reason' => $downloadRequest->reason,
            'usage_type' => $downloadRequest->usage_type,
            'created_at' => $downloadRequest->created_at->toIso8601String(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('stk.approvals');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'download.request.new';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->broadcastData;
    }
}
