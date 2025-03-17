<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DownloadRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class CheckApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Admin users bypass approval checks
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Extract document ID and version from the route parameters
        $documentId = $request->route('id');
        $documentVersion = $request->route('version');

        if (!$documentId || !$documentVersion) {
            return redirect()->route('stk.database')
                ->with('error', 'Permintaan download tidak valid.');
        }

        // Check if the document exists
        $document = Document::where('id', $documentId)
            ->where('version', $documentVersion)
            ->first();

        if (!$document) {
            return redirect()->route('stk.database')
                ->with('error', 'Dokumen tidak ditemukan.');
        }

        // If the document doesn't require approval (e.g., public documents)
        if ($document->access_level === 'public') {
            return $next($request);
        }

        // Check if user has an approved and valid download request
        $downloadRequest = DownloadRequest::where('user_id', $user->id)
            ->where('document_id', $documentId)
            ->where('document_version', $documentVersion)
            ->where('status', 'approved')
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        // If no valid download request exists, redirect to preview page
        if (!$downloadRequest) {
            // If the direct download parameter is set, show error
            if ($request->query('download') === 'true') {
                return redirect()->route('stk.preview', ['id' => $documentId, 'version' => $documentVersion])
                    ->with('error', 'Anda tidak memiliki izin untuk mengunduh dokumen ini. Silakan ajukan permintaan terlebih dahulu.');
            }

            // Otherwise, just continue to preview (not download)
            return $next($request);
        }

        // Check download token if provided
        $token = $request->query('token');
        if ($token && $downloadRequest->download_token !== $token) {
            return redirect()->route('stk.preview', ['id' => $documentId, 'version' => $documentVersion])
                ->with('error', 'Token download tidak valid.');
        }

        // Request is valid, continue
        return $next($request);
    }
}
