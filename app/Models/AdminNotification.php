<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnerScope;

class AdminNotification extends Model
{
    use HasFactory, OwnerScope;

    protected $fillable = [
        'owner_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get recent notifications (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Get translated title based on current language
     */
    public function getTranslatedTitleAttribute()
    {
        return __($this->title);
    }

    /**
     * Get translated message based on current language with data interpolation
     */
    public function getTranslatedMessageAttribute()
    {
        $messageData = [];
        
        // Extract relevant data for message interpolation
        if ($this->data) {
            if (isset($this->data['order_reference'])) {
                $messageData['order_id'] = $this->data['order_reference'];
            }
            
            // Handle dynamic product name translation
            if (isset($this->data['product_name_data'])) {
                $currentLocale = app()->getLocale();
                $messageData['product_name'] = $this->data['product_name_data'][$currentLocale] ?? 
                                               $this->data['product_name_data']['en'] ?? 
                                               'Unknown Product';
            }
            // Fallback for old format
            elseif (isset($this->data['product_name'])) {
                $messageData['product_name'] = $this->data['product_name'];
            }
        }
        
        return __($this->message, $messageData);
    }
}