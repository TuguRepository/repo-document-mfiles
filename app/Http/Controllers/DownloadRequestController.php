<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Facades\UserContext;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Document; // Tambahkan ini

class DownloadRequestController extends Controller
{
    /**
     * Store a new download request
     */
    public function store(Request $request)
    {
        // Log untuk debugging
        Log::info('Download request received', $request->all());

        // Validasi request
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
            'reason' => 'required',
            'reference_number' => 'required'
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buat record download request baru
            $downloadRequest = new DownloadRequest();

            $payload = UserContext::getPayload();

            // Wajib ada
            $downloadRequest->document_id = $request->document_id;
            $downloadRequest->user_id = Auth::id() ?? 1; // Default ke ID 1 jika tidak login
            $downloadRequest->user_name = $payload['name'] ? $payload['name'] : 'Guest User';
            $downloadRequest->user_email = $payload['email'] ? $payload['email'] : 'Guest@tugu.com';

            // Opsional dengan default
            $downloadRequest->document_version = $request->document_version ?? 'latest';

            // Format alasan permintaan
            $reason = $request->reason;
            if ($reason === 'other' && $request->has('other_reason')) {
                // Gabungkan other_reason ke alasan utama
                $reason .= ' - ' . $request->other_reason;
            }
            $downloadRequest->reason = $reason;

            // Optional fields yang ada di skema
            if ($request->has('document_title')) {
                $downloadRequest->document_title = $request->document_title;
            }

            if ($request->has('document_number')) {
                $downloadRequest->document_number = $request->document_number;
            }

            if ($request->has('notes')) {
                $downloadRequest->notes = $request->notes;
            }

            $downloadRequest->reference_number = $request->reference_number;
            $downloadRequest->status = 'pending';

            // IP address ada di skema
            $downloadRequest->ip_address = $request->ip();

            // Jika usage_type dikirim dan kolom ada di skema
            if ($request->has('usage_type')) {
                $downloadRequest->usage_type = $request->usage_type;
            }

            // PENTING: Tambahkan token (required field)
            $downloadRequest->token = $request->token ?? Str::random(32);

            Log::info('Saving download request with data', $downloadRequest->toArray());

            $downloadRequest->save();

            Log::info('Download request saved', ['id' => $downloadRequest->id]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan download berhasil disimpan',
                'data' => [
                    'id' => $downloadRequest->id,
                    'reference_number' => $downloadRequest->reference_number,
                    'token' => $downloadRequest->token
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving download request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handles the download request based on the provided token.
     *
     * @param string $token The unique token associated with the download request.
     * @return BinaryFileResponse|Response
     */
    public function download(string $token): Response
    {
        // Find the DownloadRequest by token
        $downloadRequest = DownloadRequest::where('token', $token)->firstOrFail();

        // Check if the download request has been approved
        if (!$downloadRequest->approved_at) {
            abort(403, 'Download request not approved.'); // Forbidden
        }

        // Determine the file path and name based on the DownloadRequest
        $filePath = null;
        $fileName = null;

        // Option 1: If the DownloadRequest is linked to a Document model
        if ($downloadRequest->document_id) {
            $document = Document::findOrFail($downloadRequest->document_id);
            $filePath = $document->file_path;
            $fileName = $document->file_name ?? basename($filePath); // Use file_name from Document if available
        } else {
            // Option 2: If the file path is stored directly in the DownloadRequest
            $filePath = $downloadRequest->file_path;
            $fileName = $downloadRequest->file_name ?? basename($filePath); // Use file_name from DownloadRequest if available
        }

        // Check if the file path is available
        if (!$filePath) {
            abort(500, 'File path not found.'); // Internal Server Error
        }

        // Check if the file exists in storage
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.'); // Not Found
        }

        // Increment download count (optional)
        $downloadRequest->increment('download_count');

        // Generate the streamed download response
        return Storage::download($filePath, $fileName);
    }
}
