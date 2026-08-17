<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorHourlyInventoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter and resolve only assets belonging to the authenticated Vendor user session context
        $vendorId = auth('vendor')->id(); 
       
        $data['hotels'] = DB::table('hotels')
            ->join('hotel_contents', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->where('hotel_contents.language_id', \App\Models\Language::where('is_default', 1)->value('id') ?? 1)
            ->where('hotels.vendor_id', $vendorId) // Secure query mapping verification check
            ->select('hotels.id', 'hotel_contents.title')
            ->get();

        $data['selected_hotel_id'] = $request->input('hotel_id', $data['hotels']->first()?->id);
        
        // 2. Map Operational Dates Window Window Frame Range
        $data['from_date'] = $request->input('from_date', now()->format('Y-m-d'));
        $data['to_date'] = $request->input('to_date', now()->addDays(14)->format('Y-m-d'));

        $start = Carbon::parse($data['from_date']);
        $end = Carbon::parse($data['to_date']);
        
        $datePeriod = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $datePeriod[] = $date->format('Y-m-d');
        }
        $data['datePeriod'] = $datePeriod;

        // 3. Gather inventory data records for display
        if ($data['selected_hotel_id']) {
            $data['inventories'] = DB::table('hotel_daily_inventories')
                ->where('hotel_id', $data['selected_hotel_id'])
                ->whereBetween('booking_date', [$data['from_date'], $data['to_date']])
                ->get()
                ->keyBy('booking_date');
        } else {
            $data['inventories'] = collect();
        }

        // Return the secure layout file view scope context variable block mapping data elements array
        return view('vendors.hotel.inventory.hourly', $data);
    }

    public function updateInline(Request $request)
    {
        $hotelId = $request->input('hotel_id');
        $rows = $request->input('inventory', []);

        // Secure operation verification validation pattern 
        // Ensure hotel belongs to this vendor before making data base changes
        $vendorId = auth('vendor')->id();
        
        $isOwned = DB::table('hotels')->where('id', $hotelId)->where('vendor_id', $vendorId)->exists();
        if (!$isOwned) {
            return redirect()->back()->with('error', 'Unauthorized Action Matrix Processing Operation Denied.');
        }

        foreach ($rows as $date => $fields) {
            DB::table('hotel_daily_inventories')->updateOrInsert(
                ['hotel_id' => $hotelId, 'booking_date' => $date],
                [
                    'rate_3hrs'       => $fields['rate_3hrs'] ?? null,
                    'rate_6hrs'       => $fields['rate_6hrs'] ?? null,
                    'rate_fullday'    => $fields['rate_fullday'] ?? null,
                    'rate_dayuse'     => $fields['rate_dayuse'] ?? null,
                    'total_rooms'     => $fields['total_rooms'] ?? 8,
                    'status'          => $fields['status'] ?? 'Available',
                    'manual_status_3hrs'    => $fields['manual_status_3hrs'] ?: null,
                    'manual_status_6hrs'    => $fields['manual_status_6hrs'] ?: null,
                    'manual_status_fullday' => $fields['manual_status_fullday'] ?: null,
                    'timing_3hrs'     => $fields['timing_3hrs'] ?? '12 AM-11 PM',
                    'timing_6hrs'     => $fields['timing_6hrs'] ?? '12 AM-11 PM',
                    'refund_policy'   => $fields['refund_policy'] ?? 'flexible',
                    'updated_at'      => now()
                    // Commissions are omitted so previous Admin values are retained unchanged
                ]
            );
        }

        return redirect()->back()->with('success', 'Hourly Matrix updated accurately.');
    }
}