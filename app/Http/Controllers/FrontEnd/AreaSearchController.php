<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Location\City;
use Illuminate\Http\Request;

class AreaSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }
        $results = City::where('name', 'like', $q . '%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);
        return response()->json($results);
    }

    public function suggest(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $areas = City::where('name', 'like', $q . '%')
            ->orderBy('name')
            ->limit(5)
            ->get(['id', 'name'])
            ->map(function ($c) {
                return ['type' => 'Area', 'id' => $c->id, 'name' => $c->name, 'url' => null];
            });

        $properties = \App\Models\HotelContent::join('hotels', 'hotels.id', '=', 'hotel_contents.hotel_id')
            ->where('hotel_contents.title', 'like', '%' . $q . '%')
            ->where('hotels.status', 1)
            ->where('hotels.approval_status', 1)
            ->limit(5)
            ->get(['hotel_contents.hotel_id', 'hotel_contents.title', 'hotel_contents.slug'])
            ->map(function ($h) {
                return [
                    'type' => 'Property',
                    'id' => $h->hotel_id,
                    'name' => $h->title,
                    'url' => route('frontend.hotel.details', ['slug' => $h->slug, 'id' => $h->hotel_id]),
                ];
            });

        return response()->json($areas->concat($properties)->values());
    }
}