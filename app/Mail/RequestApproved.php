<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The request data.
     *
     * @var array
     */
    public $data;

    /**
     * File path for attachment.
     *
     * @var string|null
     */
    protected $filePath;

    /**
     * Original file name.
     *
     * @var string|null
     */
    protected $fileName;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string|null $filePath
     * @param string|null $fileName
     * @return void
     */
    public function __construct(array $data, $filePath = null, $fileName = null)
    {
        // Make sure we always have a 'user' key in the data
        if (isset($data['request']) && !isset($data['user'])) {
            // If user not set but we have a request, try to get the user from the request
            if (isset($data['request']->user_id)) {
                $data['user'] = \App\Models\User::find($data['request']->user_id);
            } else {
                // Fallback to a default value to prevent view errors
                $data['user'] = null;
            }
        }

        $this->data = $data;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('Permintaan Dokumen Anda Telah Disetujui')
                    ->view('emails.request-approved');

        // Attach file if path exists and is a file
        if ($this->filePath && file_exists($this->filePath) && is_file($this->filePath)) {
            try {
                $mail->attach($this->filePath, [
                    'as' => $this->fileName ?? basename($this->filePath),
                    'mime' => mime_content_type($this->filePath) ?: 'application/octet-stream'
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to attach file to email: ' . $e->getMessage());
                // Continue without attachment if there's an error
            }
        }

        return $mail;
    }
}
