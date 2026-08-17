<?php

namespace App\Http\Controllers\Admin\HotelManagement\Location;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Support\Facades\Session;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['countries'] = $language->countryInfo()->orderByDesc('id')->get();
        $information['states'] = $language->stateInfo()->orderByDesc('id')->get();
        $information['stateCount'] = $language->stateInfo()->orderByDesc('id')->count();
        $information['cities'] = $language->cityInfo()->orderByDesc('id')->paginate(10)->appends(['language' => $request->language]);
        $information['langs'] = Language::all();
        $information['language'] = $language;

        return view('admin.hotel-management.location.city.index', $information);
    }
    public function getCountry($language_id)
    {
        $countries = Country::where('language_id', $language_id)->get();
        $states = State::where('language_id', $language_id)->get();

        return response()->json([
            'status' => 'success',
            'countries' => $countries,
            'states' => $states
        ], 200);
    }

    public function getState($country)
    {
        $states = State::where('country_id', $country)->get();
        return response()->json(['status' => 'success', 'states' => $states], 200);
    }

    public function store(Request $request)
    {
        $totalCountry = Country::Where('language_id', $request->m_language_id)->count();
        if ($totalCountry > 0) {
            $country = true;
            $totalState = State::Where('country_id', $request->country_id)->count();
            if ($totalState > 0) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $country = false;
            $totalState = State::Where('language_id', $request->m_language_id)->count();
            if ($totalState > 0) {
                $state = true;
            } else {
                $state = false;
            }
        }

        $rules = [
            'm_language_id' => 'required',
            'feature_image' => [
                new ImageMimeTypeRule()
            ],
            'name' => 'required',
            'country_id' => $country ? 'required' : '',
            'state_id' => $state ? 'required' : '',
        ];

        $messages = [
            'm_language_id.required' => __('The language field is required.'),
            'country_id.required' => __('The country field is required.'),
            'state_id.required' => __('The state field is required.')
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $featuredImgName = null;
        if ($request->hasFile('feature_image')) {
            $featuredImgURL = $request->feature_image;
            $featuredImgExt = $featuredImgURL->getClientOriginalExtension();
            $featuredImgName = time() . '.' . $featuredImgExt;
            $featuredDir = public_path('assets/img/location/city/');
            if (!file_exists($featuredDir)) {
                @mkdir($featuredDir, 0777, true);
            }
            copy($featuredImgURL, $featuredDir . $featuredImgName);
        }

        $state = new City();

        $state->language_id = $request->m_language_id;
        $state->country_id = $request->country_id;
        $state->state_id = $request->state_id;
        $state->feature_image = $featuredImgName;
        $state->name = $request->name;
        $state->icon = $request->icon;

        $state->save();

        Session::flash('success', __('State stored successfully') . '!');

        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $totalCountry = Country::Where('language_id', $request->language_id)->count();
        if ($totalCountry > 0) {
            $country = true;
        } else {
            $country = false;
        }
        $totalState = State::Where('country_id', $request->country_id)->count();
        if ($totalState > 0) {
            $state = true;
        } else {
            $state = false;
        }

        $rules = [
            'name' => 'required',
            'country_id' => $country ? 'required' : '',
            'state_id' => $state ? 'required' : '',
        ];
        if ($request->hasFile('image')) {
            $rules['image'] = [
                new ImageMimeTypeRule(),
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $city = City::find($request->id);

        $in = $request->all();

        if ($request->hasFile('image')) {
            @unlink(public_path('assets/img/location/city/') . $city->feature_image);
            $img = $request->file('image');
            $filename = uniqid() . '.jpg';
            $directory = public_path('assets/img/location/city/');
            @mkdir($directory, 0775, true);
            $img->move($directory, $filename);
            $in['feature_image'] = $filename;
        }
        $States = State::where('country_id', $request->country_id)->count();
        if ($States < 1) {
            $in['state_id'] = null;
        }

        $city->update($in);

        Session::flash('success', __('City updated successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $city = City::query()->find($id);

        $contents = $city->hotel_city()->get();

        if (count($contents) > 0) {
            return redirect()->back()->with('warning',  __('First delete all the hotel of this City') . '!');
        } else {
            @unlink(public_path('assets/img/location/city/') . $city->feature_image);
            $city->delete();
            return redirect()->back()->with('success',  __('City deleted successfully') . '!');
        }
    }

    public function nearbyAreas(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $cities = $language->cityInfo()->orderBy('name')->get();
        $selectedCityId = $request->filled('city_id') ? (int)$request->city_id : optional($cities->first())->id;
        $linkedIds = [];
        if ($selectedCityId) {
            $linkedIds = \Illuminate\Support\Facades\DB::table('city_nearby_links')
                ->where('city_id', $selectedCityId)
                ->pluck('nearby_city_id')
                ->toArray();
        }
        return view('admin.hotel-management.location.city.nearby-areas', [
            'cities' => $cities,
            'selectedCityId' => $selectedCityId,
            'linkedIds' => $linkedIds,
            'langs' => Language::all(),
            'language' => $language,
            'defaultLang' => $language,
        ]);
    }

    public function saveNearbyAreas(Request $request)
    {
        $cityId = (int)$request->city_id;
        $nearbyIds = $request->input('nearby_city_ids', []);
        $nearbyIds = array_map('intval', $nearbyIds);
        $nearbyIds = array_filter($nearbyIds, function ($id) use ($cityId) {
            return $id !== $cityId;
        });

        \Illuminate\Support\Facades\DB::table('city_nearby_links')->where('city_id', $cityId)->delete();
        \Illuminate\Support\Facades\DB::table('city_nearby_links')->where('nearby_city_id', $cityId)->delete();

        foreach ($nearbyIds as $nid) {
            \Illuminate\Support\Facades\DB::table('city_nearby_links')->insert([
                'city_id' => $cityId, 'nearby_city_id' => $nid, 'created_at' => now(), 'updated_at' => now()
            ]);
            \Illuminate\Support\Facades\DB::table('city_nearby_links')->insert([
                'city_id' => $nid, 'nearby_city_id' => $cityId, 'created_at' => now(), 'updated_at' => now()
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request['ids'];

        $errorOccurred = false;
        foreach ($ids as $id) {
            $city = City::query()->find($id);
            $contents = $city->hotel_city()->get();

            if (count($contents) > 0) {
                $errorOccurred = true;
                break;
            } else {
                @unlink(public_path('assets/img/location/city/') . $city->feature_image);
                $city->delete();
            }
        }
        if ($errorOccurred == true) {
            Session::flash('warning', __('First delete all the hotel of these City') . '!');
        } else {
            Session::flash('success', __('Selected Informations deleted successfully') . '!');
        }
        return Response::json(['status' => 'success'], 200);
    }
}
