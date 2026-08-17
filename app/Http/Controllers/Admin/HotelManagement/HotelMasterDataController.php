<?php

namespace App\Http\Controllers\Admin\HotelManagement;

use App\Http\Controllers\Controller;
use App\Models\HotelPerk;
use App\Models\HotelPolicy;
use App\Models\HotelRestriction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class HotelMasterDataController extends Controller
{
    // ── INDEX ──────────────────────────────────────────────
    public function index()
    {
        $information['perks']        = HotelPerk::orderBy('title')->get();
        $information['policies']     = HotelPolicy::orderBy('title')->get();
        $information['restrictions'] = HotelRestriction::orderBy('title')->get();
        return view('admin.hotel-management.master-data.index', $information);
    }

    // ── PERKS ──────────────────────────────────────────────
    public function storePerk(Request $request)
    {
        $request->validate([
            'icon'  => 'required|max:100',
            'title' => 'required|max:255|unique:hotel_perks,title',
        ]);
        HotelPerk::create($request->only('icon', 'title', 'status') + ['status' => 1]);
        Session::flash('success', 'Perk added successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function updatePerk(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:hotel_perks,title,' . $request->id,
        ]);
        HotelPerk::findOrFail($request->id)->update($request->only('icon', 'title', 'status'));
        Session::flash('success', 'Perk updated successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function destroyPerk($id)
    {
        HotelPerk::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Perk deleted successfully!');
    }

    // ── POLICIES ───────────────────────────────────────────
    public function storePolicy(Request $request)
    {
        $request->validate([
            'icon'  => 'required|max:100',
            'title' => 'required|max:255|unique:hotel_policies,title',
        ]);
        HotelPolicy::create([
            'icon'        => $request->icon,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 1,
        ]);
        Session::flash('success', 'Policy added successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function updatePolicy(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:hotel_policies,title,' . $request->id,
        ]);
        HotelPolicy::findOrFail($request->id)->update(
            $request->only('icon', 'title', 'description', 'status')
        );
        Session::flash('success', 'Policy updated successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function destroyPolicy($id)
    {
        HotelPolicy::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Policy deleted successfully!');
    }

    // ── RESTRICTIONS ───────────────────────────────────────
    public function storeRestriction(Request $request)
    {
        $request->validate([
            'icon'  => 'required|max:10',
            'title' => 'required|max:255|unique:hotel_restrictions,title',
        ]);
        HotelRestriction::create([
            'icon'         => $request->icon,
            'title'        => $request->title,
            'default_type' => $request->default_type ?? 'not_allowed',
            'status'       => 1,
        ]);
        Session::flash('success', 'Restriction added successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function updateRestriction(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:hotel_restrictions,title,' . $request->id,
        ]);
        HotelRestriction::findOrFail($request->id)->update(
            $request->only('icon', 'title', 'default_type', 'status')
        );
        Session::flash('success', 'Restriction updated successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function destroyRestriction($id)
    {
        HotelRestriction::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Restriction deleted successfully!');
    }
}
