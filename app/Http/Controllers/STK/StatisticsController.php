<?php

namespace App\Http\Controllers\STK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    /**
     * Get counts for all request statuses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCounts()
    {
        // Use a short cache to prevent DB hits on frequent refreshes (5 minutes)
        $stats = Cache::remember('download_request_stats', 300, function () {
            return [
                'pending' => DownloadRequest::where('status', 'pending')->count(),
                'approved' => DownloadRequest::where('status', 'approved')->count(),
                'rejected' => DownloadRequest::where('status', 'rejected')->count(),
                'total' => DownloadRequest::count(),
                'last_day' => DownloadRequest::where('created_at', '>=', now()->subDay())->count(),
                'last_week' => DownloadRequest::where('created_at', '>=', now()->subWeek())->count()
            ];
        });

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get detailed statistics with date-based breakdown
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailedStats(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get stats by day for the selected period
        $dailyStats = DownloadRequest::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
        )
        ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

        // Get stats by usage type
        $usageStats = DownloadRequest::select(
            'usage_type',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
        )
        ->groupBy('usage_type')
        ->get();

        // Get stats by document (most requested)
        $documentStats = DownloadRequest::select(
            'document_id',
            'document_title',
            DB::raw('COUNT(*) as total_requests'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(download_count) as total_downloads')
        )
        ->groupBy('document_id', 'document_title')
        ->orderBy('total_requests', 'desc')
        ->limit(10)
        ->get();

        // Get download activity over time
        $downloadActivity = DownloadRequest::select(
            DB::raw('DATE(last_downloaded_at) as date'),
            DB::raw('COUNT(*) as downloads')
        )
        ->whereNotNull('last_downloaded_at')
        ->whereBetween(DB::raw('DATE(last_downloaded_at)'), [$startDate, $endDate])
        ->groupBy(DB::raw('DATE(last_downloaded_at)'))
        ->orderBy('date')
        ->get();

        return response()->json([
            'success' => true,
            'daily' => $dailyStats,
            'usage_types' => $usageStats,
            'documents' => $documentStats,
            'downloads' => $downloadActivity
        ]);
    }

    /**
     * Get specific status count (for real-time updates)
     *
     * @param string $status Status to count (pending, approved, rejected, all)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusCount($status)
    {
        $query = DownloadRequest::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $count = $query->count();

        return response()->json([
            'success' => true,
            'status' => $status,
            'count' => $count
        ]);
    }

    /**
     * Get user statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStats()
    {
        // Get top requesters
        $topRequesters = DownloadRequest::select(
            'user_id',
            'user_name',
            'user_email',
            DB::raw('COUNT(*) as total_requests'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(download_count) as total_downloads')
        )
        ->groupBy('user_id', 'user_name', 'user_email')
        ->orderBy('total_requests', 'desc')
        ->limit(10)
        ->get();

        return response()->json([
            'success' => true,
            'top_requesters' => $topRequesters
        ]);
    }
}
