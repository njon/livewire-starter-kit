<?php

namespace App\Services;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Create a new notification
     */
    public static function create(string $type, string $title, string $message, array $data = [])
    {
        return AdminNotification::create([
            'owner_id' => Auth::user()->owner_id ?? 1, // Use current user's owner_id or default to 1
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for new order
     */
    public static function newOrder($order)
    {
        return self::create(
            'new_order',
            __('New Order Received'),
            __('New order #:order_id has been placed', ['order_id' => $order->reference]),
            [
                'order_id' => $order->id,
                'order_reference' => $order->reference,
                'amount' => $order->total->value ?? 0,
            ]
        );
    }

    /**
     * Create notification for new product review
     */
    public static function newReview($review, $product)
    {
        return self::create(
            'new_review',
            __('New Product Review'),
            __('New review for ":product_name"', ['product_name' => $product->translateAttribute('name')]),
            [
                'review_id' => $review->id,
                'product_id' => $product->id,
                'product_name' => $product->translateAttribute('name'),
                'rating' => $review->rating,
            ]
        );
    }

    /**
     * Create notification for new product question
     */
    public static function newQuestion($question, $product)
    {
        return self::create(
            'new_question',
            __('New Product Question'),
            __('New question for ":product_name"', ['product_name' => $product->translateAttribute('name')]),
            [
                'question_id' => $question->id,
                'product_id' => $product->id,
                'product_name' => $product->translateAttribute('name'),
            ]
        );
    }

    /**
     * Get unread notifications count for current user
     */
    public static function getUnreadCount()
    {
        $ownerId = Auth::user()->owner_id ?? 1;
        
        return AdminNotification::where('owner_id', $ownerId)
            ->unread()
            ->count();
    }

    /**
     * Get recent notifications for current user
     */
    public static function getRecent($limit = 10)
    {
        $ownerId = Auth::user()->owner_id ?? 1;
        
        return AdminNotification::where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId)
    {
        $ownerId = Auth::user()->owner_id ?? 1;
        
        return AdminNotification::where('owner_id', $ownerId)
            ->where('id', $notificationId)
            ->first()
            ?->markAsRead();
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead()
    {
        $ownerId = Auth::user()->owner_id ?? 1;
        
        return AdminNotification::where('owner_id', $ownerId)
            ->unread()
            ->update(['is_read' => true]);
    }
}