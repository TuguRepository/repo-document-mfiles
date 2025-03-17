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

        // Attach file if path exists
        if ($this->filePath && file_exists($this->filePath)) {
            $mail->attach($this->filePath, [
                'as' => $this->fileName ?? basename($this->filePath),
                'mime' => mime_content_type($this->filePath)
            ]);
        }

        return $mail;
    }
}
