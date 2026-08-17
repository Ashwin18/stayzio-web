<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingIdSettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('basic_settings')->first();
        return view('admin.basic-settings.booking-id-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id_prefix' => 'required|string|max:10|alpha_num',
            'booking_id_next_number' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        DB::table('basic_settings')->update([
            'booking_id_prefix' => strtoupper($request->booking_id_prefix),
            'booking_id_next_number' => $request->booking_id_next_number,
        ]);

        return redirect()->back()->with('success', 'Booking ID settings updated successfully.');
    }
}