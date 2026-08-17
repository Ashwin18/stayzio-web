<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;

class CommissionHelper
{
    /**
     * Get commission amount for a booking based on hotel_daily_inventories.
     * Falls back to 0 if no inventory record exists.
     *
     * @param int    $hotelId
     * @param string $checkInDate  (Y-m-d)
     * @param mixed  $hour         BookingHour->hour value e.g. 3, 6, "Full Day", 24
     * @param float  $grandTotal
     * @param int    $vendorId     (0 = admin hotel)
     * @return float commission amount in currency
     */
    public static function calculate(int $hotelId, string $checkInDate, $hour, float $grandTotal, int $vendorId): float
    {
        // Admin's own hotel: admin keeps 100%
        if ($vendorId == 0) {
            return $grandTotal;
        }

        // Look up the inventory record for this hotel + date
        $inv = DB::table('hotel_daily_inventories')
            ->where('hotel_id', $hotelId)
            ->where('booking_date', $checkInDate)
            ->first();

        if (!$inv) {
            // No inventory record — use default commission from basic_settings
            $default = DB::table('basic_settings')
                ->where('uniqid', 12345)
                ->value('hotel_tax_amount') ?? 0;
            $pct = (float) $default;
        } else {
            // Pick the right commission column based on booking duration
            $h = (int) $hour;
            if ($h <= 3) {
                $pct = (float) ($inv->commission_3hrs ?? 0);
            } elseif ($h <= 6) {
                $pct = (float) ($inv->commission_6hrs ?? 0);
            } elseif ($h <= 12) {
                $pct = (float) ($inv->commission_12hrs ?? $inv->commission_6hrs ?? 0);
            } else {
                // Full Day (24hrs or "Full Day")
                $pct = (float) ($inv->commission_fullday ?? 0);
            }
        }

        return round($grandTotal * ($pct / 100), 2);
    }
}
