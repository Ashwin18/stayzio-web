<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorProperty;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPropertyController extends Controller
{
    /**
     * My Properties list
     */
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();
        $properties = VendorProperty::forVendor($vendorId)
            ->orderByRaw("FIELD(status,'live','pending','approved','draft','rejected') ASC")
            ->get();

        return view('vendors.property.index', compact('properties'));
    }

    /**
     * Show property registration form
     */
    public function create()
    {
        $perks = \Illuminate\Support\Facades\DB::table('hotel_perks')->where('status', 1)->orderBy('title')->get();
        $restrictions = \Illuminate\Support\Facades\DB::table('hotel_restrictions')->where('status', 1)->orderBy('title')->get();
        $defaultLangId = \Illuminate\Support\Facades\DB::table('languages')->where('is_default', 1)->value('id') ?? 20;
        $roomCategories = \Illuminate\Support\Facades\DB::table('room_categories')
            ->where('language_id', $defaultLangId)->where('status', 1)
            ->orderBy('serial_number')->get();
        return view('vendors.property.create', compact('perks', 'restrictions', 'roomCategories'));
    }

    /**
     * Store new property submission
     */
    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $validated = $request->validate([
            'hotel_name'        => 'required|string|max:255',
            'address'           => 'required|string|max:1000',
            'city'              => 'required|string|max:100',
            'pincode'           => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            'mobile_number'     => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'reception_number'  => 'nullable|digits_between:10,12',
            'property_type'     => 'required|in:leased,owned',

            'room_type'         => 'required|string|max:100',
            'price_3hrs'        => 'required|numeric|min:0',
            'price_6hrs'        => 'required|numeric|min:0',
            'price_fullday'     => 'required|numeric|min:0',
            'total_rooms'       => 'required|integer|min:1',

            'perks'             => 'nullable|array',
            'perks.*'           => 'integer',
            'restrictions'      => 'nullable|array',
            'restrictions.*'    => 'integer',

            'allow_same_city_couples'  => 'required|in:yes,no',
            'allow_outstation_couples' => 'required|in:yes,no',
            'allow_smoking_drinking'   => 'required|in:yes,no',
            'food_facility'            => 'required|in:yes,no',
            'cancellation_policy_acceptance' => 'required|in:yes',

            'owner_name'        => 'required|string|max:255',
            'owner_contact'     => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'owner_email'       => 'required|email|max:255',

            'gstin'               => ['required', 'string', 'max:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][A-Z0-9]Z[A-Z0-9]$/i'],
            'gst_certificate'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name'           => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:20',
            'ifsc_code'           => ['required', 'string', 'size:11', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'],
            'branch_name'         => 'nullable|string|max:255',
            'cancelled_cheque'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $vendorId = $vendor->id;

        $uploadDir = public_path('assets/img/vendor-properties');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $gstFile = $request->file('gst_certificate');
        $gstPath = 'vendor-properties/' . uniqid('gst_', true) . '.' . strtolower($gstFile->getClientOriginalExtension());
        $gstFile->move($uploadDir, basename($gstPath));

        $chequeFile = $request->file('cancelled_cheque');
        $chequePath = 'vendor-properties/' . uniqid('cheque_', true) . '.' . strtolower($chequeFile->getClientOriginalExtension());
        $chequeFile->move($uploadDir, basename($chequePath));

        try {
            $property = DB::transaction(function () use ($request, $validated, $vendorId, $gstPath, $chequePath) {
                return VendorProperty::create([
                    'vendor_id'       => $vendorId,
                    'hotel_name'      => $validated['hotel_name'],
                    'address'         => $validated['address'],
                    'city'            => $validated['city'],
                    'pincode'         => $validated['pincode'],
                    'mobile_number'   => $validated['mobile_number'],
                    'reception_number'=> $validated['reception_number'] ?? null,
                    'property_type'   => $validated['property_type'],

                    'room_type'       => $validated['room_type'],
                    'price_3hrs'      => $validated['price_3hrs'],
                    'price_6hrs'      => $validated['price_6hrs'],
                    'price_fullday'   => $validated['price_fullday'],
                    'total_rooms'     => $validated['total_rooms'],

                    'amenities'             => json_encode([]),
                    'selected_perks'        => json_encode($request->input('perks', [])),
                    'selected_restrictions' => json_encode($request->input('restrictions', [])),

                    'allow_same_city_couples'        => $validated['allow_same_city_couples'],
                    'allow_outstation_couples'       => $validated['allow_outstation_couples'],
                    'allow_smoking_drinking'         => $validated['allow_smoking_drinking'],
                    'food_facility'                  => $validated['food_facility'],
                    'cancellation_policy_acceptance' => $validated['cancellation_policy_acceptance'],

                    'owner_name'    => $validated['owner_name'],
                    'owner_contact' => $validated['owner_contact'],
                    'owner_email'   => $validated['owner_email'],

                    'manager_name'        => null,
                    'manager_number'      => null,
                    'manager_designation' => null,
                    'manager_email'       => null,

                    'gstin'               => strtoupper($validated['gstin']),
                    'gst_certificate'     => $gstPath,
                    'bank_name'           => $validated['bank_name'],
                    'account_holder_name' => $validated['account_holder_name'],
                    'account_number'      => $validated['account_number'],
                    'ifsc_code'           => strtoupper($validated['ifsc_code']),
                    'branch_name'         => $validated['branch_name'] ?? null,
                    'cancelled_cheque'    => $chequePath,
                    'status'              => VendorProperty::STATUS_PENDING,
                ]);
            });
        } catch (\Throwable $e) {
            @unlink(public_path('assets/img/' . $gstPath));
            @unlink(public_path('assets/img/' . $chequePath));
            throw $e;
        }

        try {
            $vendorLabel = $vendor->username ?? $vendor->name ?? $vendor->email ?? ('Vendor #' . $vendorId);
            AdminNotification::fire(
                'property',
                'New Property Submitted',
                $vendorLabel . ' submitted "' . $property->hotel_name . '" for approval.',
                url('admin/property-submissions/' . $property->id),
                $property->id
            );
        } catch (\Throwable $e) {
            \Log::warning('Property saved but admin notification failed', [
                'property_id' => $property->id,
                'vendor_id' => $vendorId,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('vendor.properties.index')
            ->with('success', 'Property submitted for review. Admin will review it shortly.');
    }

    /**
     * Edit prices (only for live properties)
     */
    public function editPrices($id)
    {
        $vendorId = Auth::guard('vendor')->id();
        $property = VendorProperty::where('id', $id)
            ->where('vendor_id', $vendorId)
            ->where('status', VendorProperty::STATUS_LIVE)
            ->firstOrFail();

        return view('vendors.property.edit-prices', compact('property'));
    }

    /**
     * Update prices
     */
    public function updatePrices(Request $request, $id)
    {
        $vendorId = Auth::guard('vendor')->id();
        $property = VendorProperty::where('id', $id)
            ->where('vendor_id', $vendorId)
            ->where('status', VendorProperty::STATUS_LIVE)
            ->firstOrFail();

        $request->validate([
            'price_3hrs'    => 'required|numeric|min:0',
            'price_6hrs'    => 'required|numeric|min:0',
            'price_fullday' => 'required|numeric|min:0',
        ]);

        // Update property prices
        $property->update([
            'price_3hrs'    => $request->price_3hrs,
            'price_6hrs'    => $request->price_6hrs,
            'price_fullday' => $request->price_fullday,
        ]);

        // Also update hourly_room_prices if hotel+room exist
        if ($property->room_id) {
            DB::table('hourly_room_prices')
                ->where('room_id', $property->room_id)
                ->where('hour', 3)
                ->update(['price' => $request->price_3hrs, 'updated_at' => now()]);

            DB::table('hourly_room_prices')
                ->where('room_id', $property->room_id)
                ->where('hour', 6)
                ->update(['price' => $request->price_6hrs, 'updated_at' => now()]);

            DB::table('hourly_room_prices')
                ->where('room_id', $property->room_id)
                ->where('hour', 24)
                ->update(['price' => $request->price_fullday, 'updated_at' => now()]);

            // Update room min/max
            DB::table('rooms')->where('id', $property->room_id)->update([
                'min_price' => $request->price_3hrs,
                'max_price' => $request->price_fullday,
                'updated_at' => now(),
            ]);

            // Update hotel min/max
            if ($property->hotel_id) {
                DB::table('hotels')->where('id', $property->hotel_id)->update([
                    'min_price' => $request->price_3hrs,
                    'max_price' => $request->price_fullday,
                    'updated_at' => now(),
                ]);

                // Auto-sync: update ALL future inventory dates with new prices
                // Keep admin's commission % unchanged
                DB::table('hotel_daily_inventories')
                    ->where('hotel_id', $property->hotel_id)
                    ->where('booking_date', '>=', now()->format('Y-m-d'))
                    ->update([
                        'rate_3hrs'    => $request->price_3hrs,
                        'rate_6hrs'    => $request->price_6hrs,
                        'rate_fullday' => $request->price_fullday,
                        'updated_at'   => now(),
                    ]);
            }
        }

        return redirect()->route('vendor.properties.index')
            ->with('success', '✅ Prices updated successfully!');
    }

    /**
     * View property details
     */
    public function show($id)
    {
        $vendorId = Auth::guard('vendor')->id();
        $property = VendorProperty::where('id', $id)
            ->where('vendor_id', $vendorId)
            ->firstOrFail();

        return view('vendors.property.show', compact('property'));
    }
}
