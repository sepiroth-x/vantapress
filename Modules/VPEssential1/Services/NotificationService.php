<?php

namespace Modules\VPEssential1\Services;

use Modules\VPEssential1\Models\Notification;
use Modules\VPEssential1\Models\SocialSetting;

class NotificationService
{
    /**
     * Create a notification
     */
    public static function create(array $data): ?Notification
    {
        if (!SocialSetting::isFeatureEnabled('notifications')) {
            return null;
        }
        
        // Set default notifiable values if not provided
        if (!isset($data['notifiable_type'])) {
            $data['notifiable_type'] = 'App\\Models\\User';
        }
        if (!isset($data['notifiable_id'])) {
            $data['notifiable_id'] = $data['from_user_id'] ?? 0;
        }
        
        return Notification::create($data);
    }
    
    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId): void
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }
    
    /**
     * Mark all user notifications as read
     */
    public static function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
    
    /**
     * Get unread notifications count
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    /**
     * Get recent notifications
     */
    public static function getRecent(int $userId, int $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->with(['fromUser', 'notifiable'])
            ->latest()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Delete old notifications
     */
    public static function deleteOld(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }
}
