<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): ActivityLog {
        $request = $request ?? request();
        
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Log model creation
     */
    public static function logCreate(Model $model, ?Request $request = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = "Created new {$modelName}: {$model->getKey()}";
        
        return self::log('create', $description, $model, null, $model->toArray(), $request);
    }

    /**
     * Log model update
     */
    public static function logUpdate(Model $model, array $oldValues, array $newValues, ?Request $request = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = "Updated {$modelName}: {$model->getKey()}";
        
        return self::log('update', $description, $model, $oldValues, $newValues, $request);
    }

    /**
     * Log model deletion
     */
    public static function logDelete(Model $model, ?Request $request = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = "Deleted {$modelName}: {$model->getKey()}";
        
        return self::log('delete', $description, $model, $model->toArray(), null, $request);
    }

    /**
     * Log user login
     */
    public static function logLogin(?Request $request = null): ActivityLog
    {
        $user = Auth::user();
        $description = "User logged in: {$user->name} ({$user->email})";
        
        return self::log('login', $description, null, null, null, $request);
    }

    /**
     * Log user logout
     */
    public static function logLogout(?Request $request = null): ActivityLog
    {
        $user = Auth::user();
        $description = "User logged out: {$user->name} ({$user->email})";
        
        return self::log('logout', $description, null, null, null, $request);
    }

    /**
     * Log view action
     */
    public static function logView(Model $model, ?Request $request = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = "Viewed {$modelName}: {$model->getKey()}";
        
        return self::log('view', $description, $model, null, null, $request);
    }

    /**
     * Log export action
     */
    public static function logExport(string $type, ?Request $request = null): ActivityLog
    {
        $description = "Exported {$type} data";
        
        return self::log('export', $description, null, null, null, $request);
    }

    /**
     * Log import action
     */
    public static function logImport(string $type, int $count, ?Request $request = null): ActivityLog
    {
        $description = "Imported {$count} {$type} records";
        
        return self::log('import', $description, null, null, ['count' => $count], $request);
    }

    /**
     * Log bulk action
     */
    public static function logBulkAction(string $action, string $modelType, int $count, ?Request $request = null): ActivityLog
    {
        $description = "Bulk {$action} {$count} {$modelType} records";
        
        return self::log($action, $description, null, null, ['count' => $count], $request);
    }

    /**
     * Get recent activities
     */
    public static function getRecentActivities(int $limit = 50)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by user
     */
    public static function getActivitiesByUser(int $userId, int $limit = 50)
    {
        return ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by model
     */
    public static function getActivitiesByModel(string $modelType, ?int $modelId = null, int $limit = 50)
    {
        $query = ActivityLog::where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity statistics
     */
    public static function getStatistics(int $days = 30)
    {
        $startDate = now()->subDays($days);
        
        return [
            'total_activities' => ActivityLog::where('created_at', '>=', $startDate)->count(),
            'unique_users' => ActivityLog::where('created_at', '>=', $startDate)->distinct('user_id')->count(),
            'most_active_user' => ActivityLog::where('created_at', '>=', $startDate)
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->first(),
            'activities_by_action' => ActivityLog::where('created_at', '>=', $startDate)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get(),
            'activities_by_day' => ActivityLog::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get(),
        ];
    }
}
