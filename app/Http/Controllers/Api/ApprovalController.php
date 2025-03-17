<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadRequest;
use App\Models\Document;
use App\Models\Activity;
use App\Models\User;
use App\Mail\RequestApproved;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    /**
     * Approve a download request with option to send file as email attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request)
    {
        try {
            // Log input data for debugging
            Log::info('Approval request input', $request->all());

            // Validate the request
            $validator = $request->validate([
                'request_id' => 'required',
                'note' => 'nullable|string',
                'send_email' => 'nullable|in:0,1',
                'send_file' => 'nullable|in:0,1',
                'limit_time' => 'nullable|in:0,1'
            ]);

            // Find the request
            $downloadRequest = DownloadRequest::findOrFail($request->request_id);
            Log::info('Download request found', ['id' => $downloadRequest->id, 'status' => $downloadRequest->status]);

            // Jika sudah disetujui sebelumnya
            if ($downloadRequest->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan ini sudah disetujui sebelumnya.'
                ], 400);
            }

            // Update request
            $downloadRequest->status = 'approved';
            $downloadRequest->approved_by = Auth::id();
            $downloadRequest->approved_at = now();
            $downloadRequest->admin_note = $request->note;

            // Set expiry if limit_time is true
            if ($request->limit_time == '1') {
                $downloadRequest->token_expires_at = now()->addDay();
            }

            $downloadRequest->save();
            Log::info('Download request approved', ['id' => $downloadRequest->id]);

            // Create activity log if Activity model exists
            try {
                if (class_exists('App\Models\Activity')) {
                    $activity = new Activity();
                    $activity->user_id = Auth::id();
                    $activity->user_name = Auth::user()->name;
                    $activity->type = 'approve';
                    $activity->download_request_id = $downloadRequest->id;
                    $activity->save();
                    Log::info('Activity log created');
                }
            } catch (\Exception $e) {
                Log::warning('Could not create activity log: ' . $e->getMessage());
                // Continue execution even if activity logging fails
            }

            // Send email notification if requested
            if ($request->send_email == '1') {
                try {
                    // Get the requester information
                    $requester = User::findOrFail($downloadRequest->user_id);
                    Log::info('Requester found', ['id' => $requester->id, 'email' => $requester->email]);

                    // Get document information
                    $document = null;
                    $filePath = null;

                    // Check if document_id exists in the download request
                    if (isset($downloadRequest->document_id)) {
                        $document = Document::find($downloadRequest->document_id);

                        if ($document && isset($document->file_path) && !empty($document->file_path)) {
                            $filePath = storage_path('app/' . $document->file_path);
                            Log::info('Document found', ['id' => $document->id, 'file_path' => $document->file_path]);
                        } else {
                            Log::warning('Document or file path not found', [
                                'document_id' => $downloadRequest->document_id ?? 'null',
                                'document' => $document ? 'found' : 'not found',
                                'file_path' => $document && isset($document->file_path) ? $document->file_path : 'null'
                            ]);
                        }
                    } else {
                        Log::warning('No document_id in download request');
                    }

                    // Prepare email data
                    $emailData = [
                        'user' => $requester,
                        'request' => $downloadRequest,
                        'note' => $request->note,
                        'expiration' => $request->limit_time == '1' ? now()->addDay() : null
                    ];

                    // Check if we should attach the file
                    if ($request->send_file == '1' && $filePath && file_exists($filePath)) {
                        Log::info('Sending email with attachment', ['file' => $filePath]);
                        // Send email with attachment
                        Mail::to($requester->email)
                            ->send(new RequestApproved($emailData, $filePath, $document->original_name ?? basename($filePath)));
                    } else {
                        Log::info('Sending email without attachment');
                        // Send email without attachment
                        Mail::to($requester->email)
                            ->send(new RequestApproved($emailData));
                    }

                    Log::info('Email sent successfully');
                } catch (\Exception $e) {
                    // Log email error but continue with approval
                    Log::error('Failed to send approval email: ' . $e->getMessage(), [
                        'exception' => get_class($e),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disetujui' .
                    ($request->send_email == '1' ? ' dan notifikasi telah dikirim ke pemohon' : '') .
                    ($request->send_email == '1' && $request->send_file == '1' ? ' beserta file yang diminta' : '')
            ]);
        } catch (\Exception $e) {
            // Detailed logging for troubleshooting
            Log::error('Error approving request: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permintaan: ' . $e->getMessage(),
                'error_type' => get_class($e)
            ], 500);
        }
    }
}
