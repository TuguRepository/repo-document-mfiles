<?php

namespace App\Http\Controllers\STK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadRequest;
use App\Models\Document;
use App\Models\Activities; // PENTING: Menggunakan nama model yang benar (dengan 's')
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Facades\UserContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail; // Tambahkan ini untuk fungsi email

class ApprovalController extends Controller
{
    /**
     * Display the approval requests page
     */
    public function index()
    {
        // Dapatkan data untuk dokumen
        $documents = Document::all()->keyBy('id')->toArray();

        $payload = UserContext::getPayload();

        if (!isset($payload['partner'])) {
            $payload['partner'] = '9900000002';
            $payload['name'] = 'Admin';
        }

        $bp = $payload['partner'];

        $urlEndpoint = env('TKYC_URL', 'http://localhost:3004')."/api/image/{$bp}";

        $response = Http::get($urlEndpoint);

        if ($response->successful() && isset($response->json()['data']['image_data'])) {
            $imageData = $response->json()['data']['image_data'];
        } else {
            $imageData = null;
        }

        return view('stk.approvals.index', [
            'documents' => $documents,
            'namaUser' => $payload['name'],
            'jobTitle' => $payload['job_title'],
            'imageData' => $imageData,
        ]);
    }

    /**
     * Get requests based on status
     */
    public function getRequests(Request $request)
    {
        try {
            $status = $request->input('status', 'pending');
            $perPage = $request->input('per_page', 5);

            // Gunakan model yang benar untuk download requests
            $query = DownloadRequest::where('status', $status);

            // Handle filter lainnya jika ada

            $requests = $query->paginate($perPage);

            return response()->json($requests);
        } catch (\Exception $e) {
            \Log::error('Error in getRequests: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memuat data permintaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCounts()
    {
        $pendingCount = DownloadRequest::where('status', 'pending')->count();
        $approvedCount = DownloadRequest::where('status', 'approved')->count();
        $rejectedCount = DownloadRequest::where('status', 'rejected')->count();

        Log::info('Download request counts', [
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount
        ]);

        return response()->json([
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount
        ]);
    }

    /**
     * Get recent activities
     */
    public function getActivities()
    {
        try {
            // Dapatkan data aktivitas dari tabel download_requests
            $requests = DownloadRequest::select(
                    'id',
                    'user_name',
                    'document_title',
                    'document_number',
                    'status',
                    'created_at',
                    'updated_at',
                    'approved_at',
                    'rejected_at',
                    'user_id'
                )
                ->latest('updated_at')
                ->take(10)
                ->get();

            $activities = [];

            foreach($requests as $request) {
                $type = 'request';
                $timestamp = $request->created_at;

                if ($request->status === 'approved') {
                    $type = 'approve';
                    $timestamp = $request->approved_at ?? $request->updated_at;
                } else if ($request->status === 'rejected') {
                    $type = 'reject';
                    $timestamp = $request->rejected_at ?? $request->updated_at;
                }

                $activities[] = [
                    'id' => $request->id,
                    'type' => $type,
                    'created_at' => $timestamp,
                    'download_request' => [
                        'id' => $request->id,
                        'user_name' => $request->user_name,
                        'document_title' => $request->document_title,
                        'document_number' => $request->document_number
                    ]
                ];
            }

            return response()->json($activities);
        } catch (\Exception $e) {
            \Log::error('Error in getActivities: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memuat aktivitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a request
     */
    public function approve(Request $request)
    {
        $validator = $request->validate([
            'request_id' => 'required',
            'note' => 'nullable|string',
            'send_email' => 'boolean',
            'send_file' => 'boolean', // Tambahkan validasi untuk opsi baru
            'limit_time' => 'boolean'
        ]);

        try {
            $downloadRequest = DownloadRequest::findOrFail($request->request_id);

            // Update request
            $downloadRequest->status = 'approved';
            $downloadRequest->approved_by = Auth::id();
            $downloadRequest->approved_at = now();
            $downloadRequest->admin_note = $request->note;

            // Set expiry if limit_time is true
            if ($request->limit_time) {
                $downloadRequest->token_expires_at = now()->addDay();
            }

            $downloadRequest->save();

            // Create activity log - PENTING: gunakan Activities (dengan 's'), bukan Activity
            $activity = new Activities();
            $activity->user_id = Auth::id();
            $activity->user_name = Auth::user()->name;
            $activity->type = 'approve';
            $activity->download_request_id = $downloadRequest->id;
            $activity->save();

            // Send email notification if requested
            if ($request->send_email) {
                // Tambahkan fitur mengirim file dalam email
                // Kode ini adalah placeholder, ganti dengan implementasi yang sesuai
                try {
                    // Get the document
                    if (isset($downloadRequest->document_id)) {
                        $document = Document::find($downloadRequest->document_id);

                        // Set up email data
                        $emailData = [
                            'user' => Auth::user(),
                            'request' => $downloadRequest,
                            'note' => $request->note,
                            'expiration' => $request->limit_time ? now()->addDay() : null
                        ];

                        // Check if we should attach the file
                        if ($request->send_file && $document && isset($document->file_path)) {
                            $filePath = storage_path('app/' . $document->file_path);
                            if (file_exists($filePath)) {
                                // Mail::to($downloadRequest->user_email)
                                //     ->send(new \App\Mail\RequestApproved($emailData, $filePath, $document->original_name));

                                // Log that file is attached
                                Log::info('Email sent with file attachment', ['request_id' => $downloadRequest->id]);
                            } else {
                                // Mail::to($downloadRequest->user_email)
                                //     ->send(new \App\Mail\RequestApproved($emailData));

                                Log::warning('File not found, sending email without attachment', [
                                    'file_path' => $document->file_path
                                ]);
                            }
                        } else {
                            // Mail::to($downloadRequest->user_email)
                            //     ->send(new \App\Mail\RequestApproved($emailData));

                            Log::info('Email sent without file attachment', ['request_id' => $downloadRequest->id]);
                        }
                    } else {
                        Log::warning('Document ID not found in download request', [
                            'request_id' => $downloadRequest->id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending email: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving request: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permintaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a request
     */
    public function reject(Request $request)
    {
        $validator = $request->validate([
            'request_id' => 'required',
            'rejection_reason' => 'required|string',
            'note' => 'required|string',
            'alternative_doc' => 'nullable|string'
        ]);

        try {
            $downloadRequest = DownloadRequest::findOrFail($request->request_id);

            // Update request
            $downloadRequest->status = 'rejected';
            $downloadRequest->rejected_by = Auth::id();
            $downloadRequest->rejected_at = now();
            $downloadRequest->rejection_reason = $request->rejection_reason;
            $downloadRequest->admin_note = $request->note;

            if ($request->has('alternative_doc')) {
                $downloadRequest->alternative_document_id = $request->alternative_doc;
            }

            $downloadRequest->save();

            // Create activity log - PENTING: gunakan Activities (dengan 's'), bukan Activity
            $activity = new Activities();
            $activity->user_id = Auth::id();
            $activity->user_name = Auth::user()->name;
            $activity->type = 'reject';
            $activity->download_request_id = $downloadRequest->id;
            $activity->save();

            // Email notification would go here
            // Mail::to($downloadRequest->user_email)->send(new RequestRejected($downloadRequest));

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting request: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak permintaan'
            ], 500);
        }
    }

    /**
     * Display document preview
     */
    public function previewDocument($id)
    {
        try {
            // Log untuk debugging
            \Log::info("Previewing document with ID: $id");

            // Gunakan data sederhana untuk sementara
            return response()->json([
                'id' => $id,
                'title' => 'Dokumen Preview',
                'code' => 'DOC-' . date('Ymd'),
                'category' => 'Dokumen'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error previewing document: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memuat preview dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
