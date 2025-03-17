<?php

namespace App\Http\Controllers\STK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadRequest; // Make sure this model exists

class ApprovalController extends Controller
{
    /**
     * Display the approval dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get pending requests
        $pendingRequests = DownloadRequest::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(7, ['*'], 'pending_page');

        // Get approved requests
        $approvedRequests = DownloadRequest::where('status', 'approved')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(5, ['*'], 'approved_page');

        // Get rejected requests
        $rejectedRequests = DownloadRequest::where('status', 'rejected')
            ->orderBy('reviewed_at', 'desc')
            ->paginate(5, ['*'], 'rejected_page');

        return view('stk.approvals.index', [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests
        ]);
    }

    /**
     * Approve a download request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveRequest(Request $request, $id)
    {
        $downloadRequest = DownloadRequest::findOrFail($id);
        $downloadRequest->status = 'approved';
        $downloadRequest->reviewer_id = auth()->id();
        $downloadRequest->reviewed_at = now();
        $downloadRequest->review_notes = $request->input('notes');
        $downloadRequest->save();

        return redirect()->route('stk.approvals.index')
            ->with('success', 'Request approved successfully.');
    }

    /**
     * Reject a download request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectRequest(Request $request, $id)
    {
        $downloadRequest = DownloadRequest::findOrFail($id);
        $downloadRequest->status = 'rejected';
        $downloadRequest->reviewer_id = auth()->id();
        $downloadRequest->reviewed_at = now();
        $downloadRequest->review_notes = $request->input('notes');
        $downloadRequest->save();

        return redirect()->route('stk.approvals.index')
            ->with('success', 'Request rejected successfully.');
    }
}
