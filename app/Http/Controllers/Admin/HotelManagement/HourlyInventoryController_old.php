<?php

namespace App\Http\Controllers\Admin\HotelManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HourlyInventoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Resolve Available Asset Options
        $data['hotels'] = DB::table('hotels')
            ->join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->where('hotel_contents.language_id', \App\Models\Language::where('is_default', 1)->value('id') ?? 1)
            ->select('hotels.id', 'hotel_contents.title')
            ->get();

        $data['selected_hotel_id'] = $request->input('hotel_id', $data['hotels']->first()?->id);
        
        // 2. Map Operational Dates Window (Defaulting to screen's reference window range if omitted)
        $data['from_date'] = $request->input('from_date', now()->format('Y-m-d'));
        $data['to_date'] = $request->input('to_date', now()->addDays(14)->format('Y-m-d'));

        $start = Carbon::parse($data['from_date']);
        $end = Carbon::parse($data['to_date']);
        
        // Generate daily loop structure markers
        $datePeriod = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $datePeriod[] = $date->format('Y-m-d');
        }
        $data['datePeriod'] = $datePeriod;

        // 3. Gather existing datasets metrics records mapped against database
        if ($data['selected_hotel_id']) {
            $data['inventories'] = DB::table('hotel_daily_inventories')
                ->where('hotel_id', $data['selected_hotel_id'])
                ->whereBetween('booking_date', [$data['from_date'], $data['to_date']])
                ->get()
                ->keyBy('booking_date');
        } else {
            $data['inventories'] = collect();
        }

        return view('admin.hotel-management.inventory.hourly', $data);
    }

    public function updateInline(Request $request)
    {
        $hotelId = $request->input('hotel_id');
        $rows = $request->input('inventory', []);

        foreach ($rows as $date => $fields) {
            DB::table('hotel_daily_inventories')->updateOrInsert(
                ['hotel_id' => $hotelId, 'booking_date' => $date],
                [
                    'rate_3hrs'       => $fields['rate_3hrs'] ?? null,
                    'rate_6hrs'       => $fields['rate_6hrs'] ?? null,
                    'rate_12hrs'      => $fields['rate_12hrs'] ?? null,
                    'rate_fullday'    => $fields['rate_fullday'] ?? null,
                    'rate_dayuse'     => $fields['rate_dayuse'] ?? null,
                    'total_rooms'     => $fields['total_rooms'] ?? 8,
                    'status'          => $fields['status'] ?? 'Available',
                    'timing_3hrs'     => $fields['timing_3hrs'] ?? '12 AM-11 PM',
                    'timing_6hrs'     => $fields['timing_6hrs'] ?? '12 AM-11 PM',
                    'timing_12hrs'    => $fields['timing_12hrs'] ?? '12 AM-11 PM',
                    'commission_3hrs' => $fields['commission_3hrs'] ?? 18.00,
                    'commission_6hrs' => $fields['commission_6hrs'] ?? 18.00,
                    'commission_12hrs'=> $fields['commission_12hrs'] ?? 18.00,
                    'commission_fullday'=> $fields['commission_fullday'] ?? 18.00,
                    'refund_policy'   => in_array($fields['refund_policy'] ?? '', ['flexible','non-refundable']) ? $fields['refund_policy'] : 'flexible',
                    'updated_at'      => now()
                ]
            );
        }

        return redirect()->back()->with('success', 'Hourly Matrix updated accurately.');
    }
}