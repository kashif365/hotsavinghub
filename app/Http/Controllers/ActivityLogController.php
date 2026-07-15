<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        // Get unique users with their activity counts and latest activity
        $query = ActivityLog::with('user')
            ->selectRaw('
                user_id,
                COUNT(*) as total_activities,
                MAX(created_at) as last_activity,
                MIN(created_at) as first_activity,
                COUNT(CASE WHEN action = "view" THEN 1 END) as page_visits,
                COUNT(CASE WHEN action = "click" THEN 1 END) as link_clicks,
                COUNT(CASE WHEN action = "create" THEN 1 END) as created_items,
                COUNT(CASE WHEN action = "update" THEN 1 END) as updated_items,
                COUNT(CASE WHEN action = "delete" THEN 1 END) as deleted_items
            ')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('last_activity', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->havingRaw('DATE(last_activity) >= ?', [$request->date_from]);
        }

        if ($request->filled('date_to')) {
            $query->havingRaw('DATE(last_activity) <= ?', [$request->date_to]);
        }

        $users = $query->paginate(20);

        // Get filter options
        $allUsers = DB::table('users')->select('id', 'name', 'email')->get();
        $actions = ActivityLog::distinct('action')->pluck('action');
        $modelTypes = ActivityLog::distinct('model_type')->whereNotNull('model_type')->pluck('model_type');

        // Get statistics
        $statistics = ActivityLogService::getStatistics(30);

        return view('admin.activity-logs.index', compact('users', 'allUsers', 'actions', 'modelTypes', 'statistics'));
    }

    /**
     * Show specific activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Show all activities for a specific user
     */
    public function userActivities(User $user, Request $request)
    {
        $query = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50);

        // Get filter options
        $actions = ActivityLog::where('user_id', $user->id)->distinct('action')->pluck('action');

        // Get user statistics
        $userStats = [
            'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
            'page_visits' => ActivityLog::where('user_id', $user->id)->where('action', 'view')->count(),
            'link_clicks' => ActivityLog::where('user_id', $user->id)->where('action', 'click')->count(),
            'last_activity' => ActivityLog::where('user_id', $user->id)->latest()->first(),
            'most_visited_pages' => $this->getMostVisitedPages($user->id),
            'most_clicked_links' => $this->getMostClickedLinks($user->id),
        ];

        return view('admin.activity-logs.user', compact('logs', 'user', 'actions', 'userStats'));
    }

    /**
     * Get most visited pages for a user
     */
    private function getMostVisitedPages($userId)
    {
        $logs = ActivityLog::where('user_id', $userId)
            ->where('action', 'view')
            ->whereNotNull('new_values')
            ->get();

        $pages = [];
        foreach ($logs as $log) {
            $newValues = $log->new_values;
            if (isset($newValues['page_title']) && !empty($newValues['page_title'])) {
                $pageTitle = $newValues['page_title'];
                if (!isset($pages[$pageTitle])) {
                    $pages[$pageTitle] = 0;
                }
                $pages[$pageTitle]++;
            }
        }

        arsort($pages);
        $result = [];
        $count = 0;
        foreach ($pages as $pageTitle => $visits) {
            if ($count >= 5) break;
            $result[] = (object)['page_title' => $pageTitle, 'count' => $visits];
            $count++;
        }

        return collect($result);
    }

    /**
     * Get most clicked links for a user
     */
    private function getMostClickedLinks($userId)
    {
        $logs = ActivityLog::where('user_id', $userId)
            ->where('action', 'click')
            ->whereNotNull('new_values')
            ->get();

        $links = [];
        foreach ($logs as $log) {
            $newValues = $log->new_values;
            if (isset($newValues['text']) && !empty($newValues['text'])) {
                $linkText = $newValues['text'];
                if (!isset($links[$linkText])) {
                    $links[$linkText] = 0;
                }
                $links[$linkText]++;
            }
        }

        arsort($links);
        $result = [];
        $count = 0;
        foreach ($links as $linkText => $clicks) {
            if ($count >= 5) break;
            $result[] = (object)['link_text' => $linkText, 'count' => $clicks];
            $count++;
        }

        return collect($result);
    }

    /**
     * Get activity logs for dashboard
     */
    public function dashboard()
    {
        $recentLogs = ActivityLogService::getRecentActivities(10);
        $statistics = ActivityLogService::getStatistics(7);

        return response()->json([
            'recent_logs' => $recentLogs,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        // Log the export action
        ActivityLogService::logExport('Activity Logs', $request);

        // Generate CSV
        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'Description',
                'IP Address',
                'User Agent',
                'URL',
                'Method',
                'Created At'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_name,
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->url,
                    $log->method,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete old activity logs
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 90);
        
        $deletedCount = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        // Log the cleanup action
        ActivityLogService::log('cleanup', "Cleaned up {$deletedCount} old activity logs (older than {$days} days)", null, null, ['deleted_count' => $deletedCount], $request);
        
        return redirect()->back()->with('success', "Successfully deleted {$deletedCount} old activity logs.");
    }

    /**
     * Track link clicks
     */
    public function trackLinkClick(Request $request)
    {
        try {
            $linkData = $request->all();
            
            // Create description
            $description = "Clicked link: {$linkData['text']} -> {$linkData['url']}";
            
            // Log the link click
            ActivityLogService::log('click', $description, null, null, $linkData, $request);
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            \Log::error('Link click tracking failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}