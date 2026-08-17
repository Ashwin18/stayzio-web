<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorProperty;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertySubmissionController extends Controller
{
    /**
     * List all property submissions
     */
    public function index()
    {
        $properties = VendorProperty::orderByRaw("FIELD(status,'pending','approved','draft','live','rejected')")
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function ($p) {
                $p->vendor_name = DB::table('vendors')->where('id', $p->vendor_id)->value('username') ?? 'Unknown';
                $p->vendor_email = DB::table('vendors')->where('id', $p->vendor_id)->value('email') ?? '';
            });

        $counts = [
            'total'    => $properties->count(),
            'pending'  => $properties->where('status', 'pending')->count(),
            'live'     => $properties->where('status', 'live')->count(),
            'rejected' => $properties->where('status', 'rejected')->count(),
        ];

        return view('admin.property-submissions.index', compact('properties', 'counts'));
    }

    /**
     * View a single property submission for review
     */
    public function show($id)
    {
        $property = VendorProperty::findOrFail($id);
        $property->vendor_name = DB::table('vendors')->where('id', $property->vendor_id)->value('username') ?? 'Unknown';
        $property->vendor_email = DB::table('vendors')->where('id', $property->vendor_id)->value('email') ?? '';

        return view('admin.property-submissions.show', compact('property'));
    }

    /**
     * Approve property — admin adds images, description, location, then approves
     */
    public function approve(Request $request, $id)
    {
        $property = VendorProperty::findOrFail($id);

        $request->validate([
            'description' => 'required|string|min:20',
            'latitude'    => 'nullable|string',
            'longitude'   => 'nullable|string',
            'stars'       => 'nullable|integer|min:1|max:5',
            'commission'  => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle image uploads
        // Handle main image upload
        $mainImage = null;
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $mainImage = uniqid('hotel_main_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/hotel'), $mainImage);
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $name = uniqid('hotel_gal_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/img/hotel'), $name);
                $galleryImages[] = $name;
            }
        }

        $property->update([
            'hotel_images' => json_encode([
                'main' => $mainImage,
                'gallery' => $galleryImages
            ])
        ]);

        // Create hotel + room records
        $hotelId = $property->createHotelAndRoom([
            'description' => $request->description,
            'latitude'    => $request->latitude ?? '13.0827',
            'longitude'   => $request->longitude ?? '80.2707',
            'stars'       => $request->stars ?? 3,
        ]);

        // Set hotel logo from main image
        $imgData = json_decode($property->hotel_images ?? '{}', true);
        $logoFile = $imgData['main'] ?? null;
        if (!$logoFile && !empty($imgData['gallery'])) $logoFile = $imgData['gallery'][0];
        if ($logoFile) {
            DB::table('hotels')->where('id', $hotelId)->update([
                'logo' => $logoFile,
                'updated_at' => now(),
            ]);
        }

        // Create initial inventory (30 days)
        $commission = $request->commission ?? 10;
        for ($d = 0; $d < 30; $d++) {
            $date = now()->addDays($d)->format('Y-m-d');
            DB::table('hotel_daily_inventories')->insert([
                'hotel_id'        => $hotelId,
                'booking_date'    => $date,
                'rate_3hrs'       => $property->price_3hrs,
                'rate_6hrs'       => $property->price_6hrs,
                'rate_fullday'    => $property->price_fullday,
                'total_rooms'     => $property->total_rooms,
                'rooms_sold'      => 0,
                'bookings_count'  => 0,
                'status'          => 'Available',
                'commission_3hrs'    => $commission,
                'commission_6hrs'    => $commission,
                'commission_fullday' => $commission,
                'refund_policy'   => 'flexible',
                'timing_3hrs'     => '06:00-22:00',
                'timing_6hrs'     => '06:00-22:00',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // Clear view cache
        foreach (glob(storage_path('framework/views/*.php')) ?: [] as $f) @unlink($f);

        // Notify admin
        AdminNotification::fire(
            'property',
            'Property Approved & Live',
            '"' . $property->hotel_name . '" is now live on the platform.',
            url('admin/property-submissions/' . $property->id),
            $property->id
        );

        return redirect()->route('admin.property_submissions.index')
            ->with('success', '✅ Property approved and live! Hotel #' . $hotelId . ' created with 30 days of inventory.');
    }

    /**
     * Reject property
     */
    public function reject(Request $request, $id)
    {
        $property = VendorProperty::findOrFail($id);
        $property->update([
            'status'          => VendorProperty::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        AdminNotification::fire(
            'property',
            'Property Rejected',
            '"' . $property->hotel_name . '" was rejected. Reason: ' . ($request->rejection_reason ?? 'Not specified'),
            url('admin/property-submissions/' . $property->id),
            $property->id
        );

        return redirect()->route('admin.property_submissions.index')
            ->with('success', '✅ Property rejected. Vendor will be notified.');
    }
}
