<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $table    = 'admin_notifications';
    protected $guarded  = [];
    protected $casts    = ['is_read' => 'boolean'];

    // Type constants
    const TYPE_VENDOR_REGISTERED  = 'vendor_registered';
    const TYPE_HOTEL_SUBMITTED    = 'hotel_submitted';
    const TYPE_BOOKING_CONFIRMED  = 'booking_confirmed';
    const TYPE_BOOKING_CANCELLED  = 'booking_cancelled';
    const TYPE_FEATURE_REQUEST    = 'feature_request';
    const TYPE_SUPPORT_TICKET     = 'support_ticket';
    const TYPE_PAYMENT_FAILED     = 'payment_failed';
    const TYPE_LOW_INVENTORY      = 'low_inventory';

    // Notification configs: icon + color per type
    public static function config(string $type): array
    {
        return [
            self::TYPE_VENDOR_REGISTERED  => ['icon' => 'ti-building-store', 'color' => 'blue'],
            self::TYPE_HOTEL_SUBMITTED    => ['icon' => 'ti-building-plus',  'color' => 'amber'],
            self::TYPE_BOOKING_CONFIRMED  => ['icon' => 'ti-calendar-check', 'color' => 'green'],
            self::TYPE_BOOKING_CANCELLED  => ['icon' => 'ti-calendar-x',    'color' => 'red'],
            self::TYPE_FEATURE_REQUEST    => ['icon' => 'ti-crown',          'color' => 'purple'],
            self::TYPE_SUPPORT_TICKET     => ['icon' => 'ti-help-circle',    'color' => 'blue'],
            self::TYPE_PAYMENT_FAILED     => ['icon' => 'ti-alert-triangle', 'color' => 'red'],
            self::TYPE_LOW_INVENTORY      => ['icon' => 'ti-alert-circle',   'color' => 'amber'],
        ][$type] ?? ['icon' => 'ti-bell', 'color' => 'blue'];
    }

    /**
     * Fire a notification — call this from any controller
     */
    public static function fire(string $type, string $title, string $message, string $actionUrl = null, int $referenceId = null): self
    {
        $cfg = self::config($type);
        return self::create([
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'action_url'   => $actionUrl,
            'icon'         => $cfg['icon'],
            'color'        => $cfg['color'],
            'reference_id' => $referenceId,
            'is_read'      => 0,
        ]);
    }

    public static function unreadCount(): int
    {
        return self::where('is_read', 0)->count();
    }

    public static function latest10(): \Illuminate\Database\Eloquent\Collection
    {
        return self::orderByDesc('created_at')->limit(10)->get();
    }

    public function markRead(): void
    {
        $this->update(['is_read' => 1]);
    }

    public static function markAllRead(): void
    {
        self::where('is_read', 0)->update(['is_read' => 1]);
    }
}
