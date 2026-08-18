<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\AboutUs;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Banner;
use App\Models\HomePage\CustomSection;
use App\Models\HomePage\Feature;
use App\Models\HomePage\Section;
use App\Models\HomePage\SectionContent;
use App\Models\Journal\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HourlyRoomPrice;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Country;
use App\Models\Package;
use App\Models\RoomContent;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $secInfo = Section::query()->first();

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $information['language'] = $language;

    $information['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();

    if ($themeVersion == 1 || $themeVersion == 2) {
      $information['sliderInfos'] = $language->sliderInfo()->orderByDesc('id')->get();
    }

    if ($themeVersion == 3 && $secInfo->benifit_section_status == 1) {
      $information['benifits'] = $language->benifits()->orderByDesc('id')->get();
    }

    $information['sectionContent'] = SectionContent::where('language_id', $language->id)->first();

    $information['images'] = Basic::select(
      'hero_section_image',
      'feature_section_image',
      'counter_section_image',
      'call_to_action_section_image',
      'call_to_action_section_inner_image',
      'testimonial_section_image'
    )->first();

    if ($secInfo->featured_section_status == 1) {
      $information['features'] = Feature::where('language_id', $language->id)->get();
    }

    if ($themeVersion == 1) {
      $information['banners'] = Banner::where('language_id', $language->id)->get();
    }

    if ($secInfo->work_process_section_status == 1 && ($themeVersion == 1 || $themeVersion == 4)) {
      $information['workProcessSecInfo'] = $language->workProcessSection()->first();
      $information['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
    }

    if ($secInfo->counter_section_status == 1) {
      $information['counters'] = $language->counterInfo()->orderByDesc('id')->get();
    }

    $information['currencyInfo'] = $this->getCurrencyInfo();

    if ($secInfo->testimonial_section_status == 1) {
      $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
    }

    if ($secInfo->blog_section_status == 1) {
      $information['blogs'] = Blog::query()->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
        ->where('blogs.status', '=', 1)
        ->where('blog_categories.status', '=', 1)
        ->where('blog_informations.language_id', '=', $language->id)
        ->select('blogs.image', 'blogs.id', 'blog_categories.name AS categoryName', 'blog_categories.slug AS categorySlug', 'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
        ->orderBy('blogs.serial_number', 'desc')
        ->limit(3)
        ->get();

      $information['blog_count'] = Blog::query()->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
        ->where('blog_informations.language_id', '=', $language->id)
        ->where('blog_categories.status', '=', 1)
        ->where('blogs.status', '=', 1)
        ->select('blogs.id')
        ->get();
    }

    if ($themeVersion == 1) {
      $information['cities'] = City::has('hotel_city')
        ->where('language_id', $language->id)
        ->orderByDesc('updated_at')
        ->take(10)
        ->get();
    }

    if ($themeVersion == 2 || $themeVersion == 3) {
      $information['cities'] = City::has('hotel_city')
        ->where('language_id', $language->id)
        ->orderByDesc('updated_at')
        ->get();
    }

    $information['secInfo'] = $secInfo;

    $now = Carbon::now()->format('Y-m-d');

    $featuredRoomIds = \App\Models\RoomFeature::where('order_status', 'apporved')
      ->where('payment_status', 'completed')
      ->whereDate('end_date', '>=', $now)
      ->pluck('room_id')
      ->toArray();

    $information['room_contents'] = RoomContent::join('rooms', 'rooms.id', '=', 'room_contents.room_id')
      ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
      ->join('room_categories', 'room_contents.room_category', '=', 'room_categories.id')
      ->join('hotel_contents', 'rooms.hotel_id', '=', 'hotel_contents.hotel_id')
      ->join('hotel_categories', 'hotel_contents.category_id', '=', 'hotel_categories.id')
      ->leftJoin('memberships', function ($join) {
        $join->on('rooms.vendor_id', '=', 'memberships.vendor_id')
          ->where('memberships.status', '=', 1);
      })
      ->leftJoin('vendors', 'rooms.vendor_id', '=', 'vendors.id')
      ->where('hotel_contents.language_id', $language->id)
      ->where('room_categories.status', 1)
      ->where('hotel_categories.status', 1)
      ->where('room_contents.language_id', $language->id)
      ->where('rooms.status', '1')
      ->where('hotels.status', '1')
      ->where('hotels.approval_status', 1)

      // A hotel can be shown if:
      // 1) it is an admin/legacy hotel, OR
      // 2) vendor has an active membership, OR
      // 3) it was created from an admin-approved LIVE vendor property.
      ->where(function ($q) {
        $q->where('rooms.vendor_id', 0)
          ->orWhere(function ($q2) {
            $q2->where('vendors.status', 1)
              ->where('memberships.status', 1)
              ->where('memberships.start_date', '<=', now()->format('Y-m-d'))
              ->where('memberships.expire_date', '>=', now()->format('Y-m-d'));
          })
          ->orWhereExists(function ($sub) {
            $sub->select(DB::raw(1))
              ->from('vendor_properties')
              ->whereColumn('vendor_properties.hotel_id', 'hotels.id')
              ->where('vendor_properties.status', 'live');
          });
      })
      ->select(
        'rooms.*',
        'room_contents.title',
        'room_contents.slug',
        'room_contents.amenities',
        'hotels.id as hotelId',
        'hotels.stars as stars',
        'hotels.latitude as latitude',
        'hotels.longitude as longitude',
        'hotels.logo as hotelImage',
        'hotel_contents.title as hotelName',
        'hotel_contents.slug as hotelSlug',
        'hotel_contents.address as address',
        'hotel_contents.city_id',
        'hotel_contents.state_id',
        'hotel_contents.country_id'
      )
      ->orderByDesc('hotels.id')
      ->limit(6)
      ->get()
      ->each(function ($room) use ($featuredRoomIds) {
        $room->is_featured = in_array($room->id, $featuredRoomIds);
      });

    $information['room_contents_count'] = RoomContent::join('rooms', 'rooms.id', '=', 'room_contents.room_id')
      ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
      ->join('room_categories', 'room_contents.room_category', '=', 'room_categories.id')
      ->join('hotel_contents', 'rooms.hotel_id', '=', 'hotel_contents.hotel_id')
      ->join('hotel_categories', 'hotel_contents.category_id', '=', 'hotel_categories.id')
      ->leftJoin('memberships', function ($join) {
        $join->on('rooms.vendor_id', '=', 'memberships.vendor_id')
          ->where('memberships.status', '=', 1);
      })
      ->leftJoin('vendors', 'rooms.vendor_id', '=', 'vendors.id')
      ->where('hotel_contents.language_id', $language->id)
      ->where('room_categories.status', 1)
      ->where('hotel_categories.status', 1)
      ->where('room_contents.language_id', $language->id)
      ->where('rooms.status', '1')
      ->where('hotels.status', '1')
      ->where('hotels.approval_status', 1)
      ->where(function ($q) {
        $q->where('rooms.vendor_id', 0)
          ->orWhere(function ($q2) {
            $q2->where('vendors.status', 1)
              ->where('memberships.status', 1)
              ->where('memberships.start_date', '<=', now()->format('Y-m-d'))
              ->where('memberships.expire_date', '>=', now()->format('Y-m-d'));
          })
          ->orWhereExists(function ($sub) {
            $sub->select(DB::raw(1))
              ->from('vendor_properties')
              ->whereColumn('vendor_properties.hotel_id', 'hotels.id')
              ->where('vendor_properties.status', 'live');
          });
      })
      ->select('rooms.id')
      ->get();

    $sections = [
      'hero_section',
      'city_section',
      'featured_section',
      'featured_room_section',
      'counter_section',
      'testimonial_section',
      'blog_section',
      'call_to_action_section',
      'benifit_section'
    ];

    foreach ($sections as $section) {
      $information["after_" . str_replace('_section', '', $section)] = CustomSection::where('order', $section)
        ->where('page_type', 'home')
        ->orderBy('serial_number', 'asc')
        ->get();
    }

    $sectionInfo = Section::select('custom_section_status')->first();

    if (!empty($sectionInfo->custom_section_status)) {
      $info = json_decode($sectionInfo->custom_section_status, true);
      $information['homecusSec'] = $info;
    }

    $information['images'] = \App\Models\BasicSettings\Basic::select(
      'hero_section_image'
    )->first();

    if ($themeVersion == 1) {
      return view('frontend.home.index-v1', $information);
    } elseif ($themeVersion == 2) {
      return view('frontend.home.index-v2', $information);
    } elseif ($themeVersion == 3) {
      return view('frontend.home.index-v3', $information);
    }
  }

  public function about()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $information['themeVersion'] = Basic::query()->pluck('theme_version')->first();

    $information['seoInfo'] = $language->seoInfo()->select('meta_keywords_about_page', 'meta_description_about_page')->first();

    $information['pageHeading'] = $misc->getPageHeading($language);

    $information['about'] = AboutUs::where('language_id', $language->id)->first();

    $information['bgImg'] = $misc->getBreadcrumb();

    $secInfo = Section::query()->first();

    $information['secInfo'] = $secInfo;

    $information['sectionContent'] = SectionContent::where('language_id', $language->id)->first();

    $information['images'] = Basic::select(
      'about_section_image',
      'feature_section_image',
      'counter_section_image',
      'call_to_action_section_image',
      'call_to_action_section_inner_image',
      'testimonial_section_image'
    )->first();

    if ($secInfo->about_features_section_status == 1) {
      $information['features'] = Feature::where('language_id', $language->id)->get();
    }

    if ($secInfo->work_process_section_status == 1) {
      $information['workProcessSecInfo'] = $language->workProcessSection()->first();
      $information['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
    }

    if ($secInfo->about_testimonial_section_status == 1) {
      $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
      $information['testimonialSecImage'] = Basic::query()->pluck('testimonial_section_image')->first();
    }

    if ($secInfo->about_counter_section_status == 1) {
      $information['counterSectionImage'] = Basic::query()->pluck('counter_section_image')->first();
      $information['counters'] = $language->counterInfo()->orderByDesc('id')->get();
    }

    $sections = ['about_section', 'features_section', 'counter_section', 'testimonial_section'];

    foreach ($sections as $section) {
      $information["after_" . str_replace('_section', '', $section)] = CustomSection::where('order', $section)
        ->where('page_type', 'about')
        ->orderBy('serial_number', 'asc')
        ->get();
    }

    $sectionInfo = Section::select('about_custom_section_status')->first();

    if (!empty($sectionInfo->about_custom_section_status)) {
      $info = json_decode($sectionInfo->about_custom_section_status, true);
      $information['aboutSec'] = $info;
    }

    return view('frontend.about-us', $information);
  }

  public function pricing(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $data['bgImg'] = $misc->getBreadcrumb();

    $data['seoInfo'] = $language->seoInfo()->select('meta_keyword_pricing', 'meta_description_pricing')->first();

    $terms = [];

    if (Package::query()->where('status', '1')->where('term', 'monthly')->count() > 0) {
      $terms[] = 'Monthly';
    }

    if (Package::query()->where('status', '1')->where('term', 'yearly')->count() > 0) {
      $terms[] = 'Yearly';
    }

    if (Package::query()->where('status', '1')->where('term', 'lifetime')->count() > 0) {
      $terms[] = 'Lifetime';
    }

    $data['terms'] = $terms;

    $data['pageHeading'] = $misc->getPageHeading($language);

    return view('frontend.pricing', $data);
  }

  public function offline()
  {
    return view('frontend.offline');
  }

  public function getNearbyRooms(Request $request)
  {
    $latitude = $request->get('latitude');
    $longitude = $request->get('longitude');
    $radius = $request->get('radius', 3);
    $checkInDate = $request->filled('checkInDates')
      ? date('Y-m-d', strtotime($request->checkInDates))
      : now()->format('Y-m-d');
    $langId = $request->get('lang_id', 1);

    $roomsQuery = DB::table('room_contents')
      ->join('rooms', 'room_contents.room_id', '=', 'rooms.id')
      ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
      ->join('hotel_contents', function ($join) use ($langId) {
        $join->on('hotels.id', '=', 'hotel_contents.hotel_id')
          ->where('hotel_contents.language_id', '=', $langId);
      })
      ->leftJoin('memberships', function ($join) {
        $join->on('rooms.vendor_id', '=', 'memberships.vendor_id')
          ->where('memberships.status', '=', 1);
      })
      ->leftJoin('vendors', 'rooms.vendor_id', '=', 'vendors.id')
      ->select(
        'room_contents.*',
        'rooms.hotel_id',
        'rooms.feature_image',
        'rooms.average_rating',
        'hotel_contents.title as hotelName',
        'hotel_contents.city_id',
        'hotel_contents.state_id',
        'hotel_contents.country_id',
        'hotel_contents.address as address',
        'hotels.latitude',
        'hotels.longitude',
        DB::raw("(6371 * acos(cos(radians($latitude))
                * cos(radians(hotels.latitude))
                * cos(radians(hotels.longitude) - radians($longitude))
                + sin(radians($latitude))
                * sin(radians(hotels.latitude)))) AS distance")
      )
      ->where('room_contents.language_id', $langId)
      ->where('rooms.status', 1)
      ->where('hotels.status', 1)
      ->where('hotels.approval_status', 1)
      ->where(function ($q) {
        $q->where('rooms.vendor_id', 0)
          ->orWhere(function ($q2) {
            $q2->where('vendors.status', 1)
              ->where('memberships.status', 1)
              ->where('memberships.start_date', '<=', now()->format('Y-m-d'))
              ->where('memberships.expire_date', '>=', now()->format('Y-m-d'));
          })
          ->orWhereExists(function ($sub) {
            $sub->select(DB::raw(1))
              ->from('vendor_properties')
              ->whereColumn('vendor_properties.hotel_id', 'hotels.id')
              ->where('vendor_properties.status', 'live');
          });
      });

    $roomsQuery->having('distance', '<=', $radius);

    if ($request->filled('filter_type') && $request->filter_type == 'premium') {
      $roomsQuery->where('rooms.average_rating', '>=', 4);
    }

    $rooms = $roomsQuery->orderBy('distance', 'asc')->take(4)->get();

    foreach ($rooms as $room) {
      $dailyInventory = DB::table('hotel_daily_inventories')
        ->where('hotel_id', $room->hotel_id)
        ->where('booking_date', $checkInDate)
        ->first();

      $prices = HourlyRoomPrice::where('room_id', $room->room_id)
        ->whereNotNull('hourly_room_prices.price')
        ->join('booking_hours', 'hourly_room_prices.hour_id', '=', 'booking_hours.id')
        ->orderBy('booking_hours.serial_number')
        ->select('hourly_room_prices.*', 'booking_hours.hour')
        ->get();

      if ($dailyInventory) {
        foreach ($prices as $priceItem) {
          if ($priceItem->hour == 3 && !empty($dailyInventory->rate_3hrs)) {
            $priceItem->price = $dailyInventory->rate_3hrs;
          } elseif ($priceItem->hour == 6 && !empty($dailyInventory->rate_6hrs)) {
            $priceItem->price = $dailyInventory->rate_6hrs;
          } elseif ($priceItem->hour == 12 && !empty($dailyInventory->rate_12hrs)) {
            $priceItem->price = $dailyInventory->rate_12hrs;
          } elseif ($priceItem->hour == 24 && !empty($dailyInventory->rate_fullday)) {
            $priceItem->price = $dailyInventory->rate_fullday;
          }
        }
      }

      $city = null;
      $state = null;
      $country = null;

      if ($room->city_id) {
        $city = City::find($room->city_id)?->name;
      }

      if ($room->state_id) {
        $state = State::find($room->state_id)?->name;
      }

      if ($room->country_id) {
        $country = Country::find($room->country_id)?->name;
      }

      $room->prices = $prices;
      $room->city = $city;
      $room->state = $state;
      $room->country = $country;
      $room->startingPrice = $prices->first();
      $room->formatted_rating = number_format($room->average_rating ?? 0, 1);
      $room->detail_url = route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->room_id]);
      $room->image_url = asset('assets/img/room/featureImage/' . $room->feature_image);
    }

    return response()->json([
      'success' => true,
      'data' => $rooms
    ]);
  }
}
