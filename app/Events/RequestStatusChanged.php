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

class RequestStatusChanged implements ShouldBroadcast
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

        // Prepare data for broadcasting
        $this->broadcastData = [
            'id' => $downloadRequest->id,
            'status' => $downloadRequest->status,
            'document_title' => $downloadRequest->document_title,
            'user_name' => $downloadRequest->user_name,
            'reviewed_by' => $downloadRequest->reviewed_by,
            'reviewed_at' => $downloadRequest->reviewed_at ? $downloadRequest->reviewed_at->toIso8601String() : null,
            'rejection_reason' => $downloadRequest->rejection_reason
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to admin channel and user-specific channel
        return [
            new PrivateChannel('stk.approvals'),
            new PrivateChannel('user.' . $this->downloadRequest->user_id)
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'download.request.status.changed';
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
