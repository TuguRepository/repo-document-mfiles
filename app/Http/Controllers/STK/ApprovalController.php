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
    use Illuminate\Support\Facades\DB; // Tambahkan import DB yang benar
    use App\Notifications\RequestApproved as RequestApprovedNotification;
    use App\Notifications\RequestRejected as RequestRejectedNotification;
    use App\Mail\RequestApproved; // Pastikan namespace ini benar
    use App\Mail\RequestRejected; // Pastikan namespace ini benar
    use App\Models\User;
    use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Facades\Schema;


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
        set_time_limit(120);
        try {
            // Validasi input
            $validated = $request->validate([
                'request_id' => 'required|integer|exists:download_requests,id',
                'note' => 'nullable|string',
                'send_email' => 'nullable|boolean',
                'send_file' => 'nullable|boolean',
                'limit_time' => 'nullable|boolean'
            ]);

            $payload = UserContext::getPayload();

            // Ambil user dari payload JWT atau Auth
            $userName = $payload['name'] ? $payload['name'] : 'Admin';
            $userEmail = $payload['email'] ?? $payload['email'] ?? null;
            $userId = $payload['partner'] ?? $payload['partner'] ?? null;

            // Gunakan transaction untuk atomic update
            DB::beginTransaction();

            try {
                $downloadRequest = DownloadRequest::findOrFail($request->request_id);

                if ($downloadRequest->status !== 'pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Permintaan sudah diproses sebelumnya'
                    ], 400);
                }

                // Update data
                    $downloadRequest->status = 'approved';
                    $downloadRequest->approved_by = $userId;
                    $downloadRequest->approved_at = now();
                    $downloadRequest->admin_note = $request->note;

                    // Set token expiration jika limit_time dicentang
                    if ($request->limit_time) {
                        $downloadRequest->token_expires_at = now()->addDay();
                    }

                    $downloadRequest->save();

                    // Gunakan model DownloadRequest langsung untuk notifikasi
                    $user = User::find($downloadRequest->user_id ?? 1);

                    // Kirim notifikasi dengan model DownloadRequest
                    if ($user) {
                        // try {
                        //     $user->notify(new \App\Notifications\RequestApproved($downloadRequest));
                        // } catch (\Exception $e) {
                        //     Log::error('Gagal mengirim notifikasi: ' . $e->getMessage(), [
                        //         'exception' => $e,
                        //         'user_id' => $user->id
                        //     ]);
                        //     // Lanjutkan meskipun notifikasi gagal
                        // }

                        // Jika perlu kirim email juga
                        if ($request->send_email || $request->send_file) {
                            try {
                                // Create data array with all necessary keys
                                $data = [
                                    'note' => $downloadRequest->admin_note ?? '',
                                    'request' => $downloadRequest,
                                    'user' => $user,
                                    'download_url' => route('download.file', ['token' => $downloadRequest->token ?? 'no-token']),
                                    'approved_at' => $downloadRequest->approved_at,
                                    'approved_by' => $userName
                                ];

                                // Pastikan email penerima menggunakan email pengguna
                                $recipientEmail = $payload['email'];

                                // Coba kirim email dengan feedback status
                                $mailResult = Mail::to($recipientEmail)
                                ->send(new RequestApproved($data));

                                // Log email sukses
                                Log::info('Email persetujuan telah dikirim', [
                                    'user_email' => $recipientEmail,
                                    'request_id' => $downloadRequest->id,
                                    'mail_driver' => config('mail.default'),
                                    'mail_result' => $mailResult ? 'success' : 'unknown'
                                ]);
                            } catch (\Exception $e) {
                                // Log detail kesalahan email
                                Log::error('Gagal mengirim email: ' . $e->getMessage(), [
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString(),
                                    'mail_driver' => config('mail.default'),
                                    'user_email' => $recipientEmail ?? 'unknown',
                                    'request_id' => $downloadRequest->id
                                ]);

                                // Coba fallback ke log mailer
                                try {
                                    Log::info('Mencoba fallback ke log mailer');
                                    Mail::mailer('log')
                                        ->to($recipientEmail)
                                        ->send(new RequestApproved($data));

                                    Log::info('Email berhasil ditulis ke log');
                                } catch (\Exception $e2) {
                                    Log::error('Fallback ke log mailer juga gagal: ' . $e2->getMessage());
                                }
                            }
                        }
                    }

                    // Catat log
                    Log::info('Download request approved', [
                        'request_id' => $downloadRequest->id,
                        'approved_by' => $userId,
                        'approved_at' => $downloadRequest->approved_at
                    ]);

                // Catat aktivitas
                try {
                    if (class_exists('App\Models\Activities')) {
                        $activity = new \App\Models\Activities();
                        $activity->user_id = $userId;
                        $activity->user_name = $userName;
                        $activity->type = 'approve';
                        $activity->download_request_id = $downloadRequest->id;
                        $activity->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Error saat menyimpan aktivitas: ' . $e->getMessage());
                    // Lanjutkan meskipun gagal menyimpan aktivitas
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan berhasil disetujui'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error approving request: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permintaan',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
        /**
     * Reject a request
     */
    public function reject(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'request_id' => 'required|integer|exists:download_requests,id',
                'rejection_reason' => 'required|string',
                'note' => 'nullable|string',
                'alternative_doc' => 'nullable|integer|exists:documents,id',
            ]);

            // Ambil user dari payload JWT atau Auth
            $userPayload = $request->get('user_payload', []);
            $userName = $userPayload['name'] ?? Auth::user()->name ?? 'Admin';
            $userEmail = $userPayload['email'] ?? Auth::user()->email ?? null;
            $userId = $userPayload['partner'] ?? Auth::id() ?? null;

            // Gunakan transaction untuk atomic update
            DB::beginTransaction();

            try {
                $downloadRequest = DownloadRequest::findOrFail($request->request_id);

                if ($downloadRequest->status !== 'pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Permintaan sudah diproses sebelumnya'
                    ], 400);
                }

                // Update data
                $downloadRequest->status = 'rejected';
                $downloadRequest->rejected_by = $userId;
                $downloadRequest->rejected_at = now();
                $downloadRequest->rejection_reason = $request->rejection_reason;

                if ($request->has('note')) {
                    $downloadRequest->admin_note = $request->note;
                }

                if ($request->has('alternative_doc')) {
                    $downloadRequest->alternative_document_id = $request->alternative_doc;
                }

                $downloadRequest->save();

                // Catat aktivitas
                try {
                    if (class_exists('App\Models\Activities')) {
                        $activity = new Activities();
                        $activity->user_id = $userId;
                        $activity->user_name = $userName;
                        $activity->type = 'reject';
                        $activity->download_request_id = $downloadRequest->id;
                        $activity->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Error saat menyimpan aktivitas: ' . $e->getMessage());
                    // Lanjutkan meskipun gagal menyimpan aktivitas
                }

                // Nonaktifkan pengiriman email untuk sementara
                // Akan diaktifkan kembali setelah konfigurasi mail selesai

                /*
                // Kirim email notifikasi penolakan
                if ($downloadRequest->user_email) {
                    try {
                        // Logika pengiriman email...
                    } catch (\Exception $e) {
                        Log::error('Error mengirim email penolakan: ' . $e->getMessage());
                    }
                }
                */

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan berhasil ditolak'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error rejecting request: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak permintaan',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    }
