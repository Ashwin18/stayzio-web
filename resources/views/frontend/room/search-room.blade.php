
</div>{{-- /col-lg-9 --}}
</div>{{-- /row --}}
</div>{{-- /container --}}


@section('script')
@parent
<script>
document.querySelectorAll(".sz-rating-btn").forEach(function(btn) {
    btn.addEventListener("click", function() {
        document.querySelectorAll(".sz-rating-btn").forEach(function(b) { b.classList.remove("active"); });
        this.classList.add("active");
    });
});
</script>
@endsection
= $applied_filters ?? [];
@endphp

<style>
.sz-filter-sidebar{background:#fff;border:1px solid #e8e2d9;border-radius:14px;padding:18px;position:sticky;top:80px}
.sz-filter-title{font-size:14px;font-weight:800;color:#1a1612;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between}
.sz-filter-group{margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f0ebe4}
.sz-filter-group:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
.sz-filter-label{font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;display:block}
.sz-filter-select{width:100%;padding:8px 10px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px;font-family:inherit}
.sz-filter-select:focus{border-color:#e31e24;outline:none}
.sz-filter-check{display:flex;align-items:center;gap:7px;padding:5px 0;font-size:12px;color:#1a1612;cursor:pointer}
.sz-filter-check input{accent-color:#e31e24;width:15px;height:15px}
.sz-price-inputs{display:flex;gap:6px;align-items:center}
.sz-price-input{width:100%;padding:7px 8px;border:1.5px solid #e8e2d9;border-radius:6px;background:#f8f6f1;color:#1a1612;font-size:12px;text-align:center}
.sz-price-input:focus{border-color:#e31e24;outline:none}
.sz-rating-btns{display:flex;gap:4px;flex-wrap:wrap}
.sz-rating-btn{padding:5px 10px;border:1.5px solid #e8e2d9;border-radius:6px;font-size:11px;font-weight:700;color:#6b6560;cursor:pointer;background:#f8f6f1;transition:.15s}
.sz-rating-btn:hover,.sz-rating-btn.active{border-color:#e31e24;color:#e31e24;background:rgba(227,30,36,.05)}
.sz-filter-apply{width:100%;padding:10px;background:#e31e24;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:.15s;display:flex;align-items:center;justify-content:center;gap:6px;margin-top:4px}
.sz-filter-apply:hover{background:#c71c22}
.sz-filter-clear{width:100%;padding:8px;background:none;color:#6b6560;border:1.5px solid #e8e2d9;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;margin-top:6px;text-align:center;transition:.15s}
.sz-filter-clear:hover{border-color:#e31e24;color:#e31e24}
.sz-active-filters{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px}
.sz-active-tag{padding:3px 10px;background:rgba(227,30,36,.08);border:1px solid rgba(227,30,36,.2);border-radius:20px;font-size:10px;font-weight:700;color:#e31e24;display:flex;align-items:center;gap:4px}
.sz-active-tag a{color:#e31e24;text-decoration:none;font-size:13px}
@media(max-width:992px){
  .sz-filter-sidebar{position:fixed;top:0;left:-300px;width:280px;height:100vh;z-index:1000;border-radius:0;overflow-y:auto;transition:left .3s;box-shadow:4px 0 20px rgba(0,0,0,.15)}
  .sz-filter-sidebar.open{left:0}
  .sz-filter-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999}
  .sz-filter-overlay.open{display:block}
}
</style>

<div class="container mt-3 mb-4">
<div class="row">

{{-- Mobile filter toggle --}}
<div class="d-lg-none mb-3">
  <button onclick="document.getElementById('szFilterSidebar').classList.add('open');document.getElementById('szFilterOverlay').classList.add('open')" style="padding:8px 16px;background:#e31e24;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px">
    <i class="fas fa-sliders-h"></i> Filters
  </button>
</div>
<div id="szFilterOverlay" class="sz-filter-overlay" onclick="document.getElementById('szFilterSidebar').classList.remove('open');this.classList.remove('open')"></div>

{{-- Sidebar --}}
<div class="col-lg-3">
  <form method="GET" action="{{ route('frontend.rooms') }}" id="szFilterForm">
    {{-- Preserve existing search params --}}
    @if(request('checkInDates'))<input type="hidden" name="checkInDates" value="{{ request('checkInDates') }}">@endif
    @if(request('checkInTimes'))<input type="hidden" name="checkInTimes" value="{{ request('checkInTimes') }}">@endif
    @if(request('hour'))<input type="hidden" name="hour" value="{{ request('hour') }}">@endif

    <div class="sz-filter-sidebar" id="szFilterSidebar">
      <div class="sz-filter-title">
        <span><i class="fas fa-sliders-h" style="color:#e31e24;margin-right:6px"></i> Filters</span>
        <button type="button" class="d-lg-none" onclick="document.getElementById('szFilterSidebar').classList.remove('open');document.getElementById('szFilterOverlay').classList.remove('open')" style="background:none;border:none;font-size:18px;color:#6b6560;cursor:pointer">&times;</button>
      </div>

      {{-- City --}}
      <div class="sz-filter-group">
        <label class="sz-filter-label">City</label>
        <select name="filter_city" class="sz-filter-select">
          <option value="">All Cities</option>
          @foreach($filter_cities ?? [] as $fc)
          <option value="{{ $fc->id }}" {{ ($af['filter_city'] ?? '') == $fc->id ? 'selected' : '' }}>{{ $fc->name }} ({{ $fc->hotel_count }})</option>
          @endforeach
        </select>
      </div>

      {{-- Price Range --}}
      <div class="sz-filter-group">
        <label class="sz-filter-label">Price Range (₹)</label>
        <div class="sz-price-inputs">
          <input type="number" name="min_price" class="sz-price-input" placeholder="Min" value="{{ $af['min_price'] ?? '' }}" min="0">
          <span style="color:#6b6560;font-size:11px">to</span>
          <input type="number" name="max_price" class="sz-price-input" placeholder="Max" value="{{ $af['max_price'] ?? '' }}" min="0">
        </div>
      </div>

      {{-- Duration --}}
      <div class="sz-filter-group">
        <label class="sz-filter-label">Duration</label>
        <select name="hour" class="sz-filter-select">
          <option value="">Any Duration</option>
          <option value="3" {{ request('hour')=='3' ? 'selected' : '' }}>3 Hours</option>
          <option value="6" {{ request('hour')=='6' ? 'selected' : '' }}>6 Hours</option>
          <option value="24" {{ request('hour')=='24' ? 'selected' : '' }}>Full Day</option>
        </select>
      </div>

      {{-- Rating --}}
      <div class="sz-filter-group">
        <label class="sz-filter-label">Min Rating</label>
        <div class="sz-rating-btns">
          @foreach([3,4,5] as $r)
          <label class="sz-rating-btn {{ ($af['filter_rating'] ?? 0) == $r ? 'active' : '' }}">
            <input type="radio" name="filter_rating" value="{{ $r }}" style="display:none" {{ ($af['filter_rating'] ?? 0) == $r ? 'checked' : '' }}>
            {{ $r }}★+
          </label>
          @endforeach
          <label class="sz-rating-btn {{ empty($af['filter_rating']) ? 'active' : '' }}">
            <input type="radio" name="filter_rating" value="" style="display:none" {{ empty($af['filter_rating']) ? 'checked' : '' }}>
            Any
          </label>
        </div>
      </div>

      {{-- Guest Type (multi-select) --}}
      <div class="sz-filter-group">
        <label class="sz-filter-label">Guest Type</label>
        @foreach($filter_categories ?? [] as $cat)
        <label class="sz-filter-check">
          <input type="checkbox" name="filter_category[]" value="{{ $cat->id }}" {{ in_array($cat->id, (array)($af['filter_category'] ?? [])) ? 'checked' : '' }}>
          <span>{{ $cat->name }}</span>
        </label>
        @endforeach
      </div>

      {{-- Couple Friendly --}}
      <div class="sz-filter-group">
        <label class="sz-filter-check">
          <input type="checkbox" name="couple_friendly" value="1" {{ !empty($af['couple_friendly']) ? 'checked' : '' }}>
          <span>Couple Friendly Only</span>
        </label>
      </div>

      {{-- Apply / Clear --}}
      <button type="submit" class="sz-filter-apply">
        <i class="fas fa-search"></i> Apply Filters
      </button>
      <a href="{{ route('frontend.rooms') }}{{ request('checkInDates') ? '?checkInDates='.request('checkInDates') : '' }}" class="sz-filter-clear">
        Clear All Filters
      </a>
    </div>
  </form>
</div>

{{-- Results --}}
<div class="col-lg-9">

{{-- Active filter tags --}}
@if(!empty($af['filter_city']) || !empty($af['min_price']) || !empty($af['max_price']) || !empty($af['filter_rating']) || !empty($af['couple_friendly']) || !empty($af['filter_category']))
<div class="sz-active-filters">
  <span style="font-size:11px;color:#6b6560;font-weight:600;padding:3px 0">Active:</span>
  @if(!empty($af['filter_city']))
    <span class="sz-active-tag">{{ ($filter_cities ?? collect())->firstWhere('id', $af['filter_city'])->name ?? 'City' }} <a href="javascript:void(0)" onclick="document.querySelector('[name=filter_city]').value='';document.getElementById('szFilterForm').submit()">&times;</a></span>
  @endif
  @if(!empty($af['min_price']) || !empty($af['max_price']))
    <span class="sz-active-tag">₹{{ $af['min_price'] ?? 0 }} - ₹{{ $af['max_price'] ?? '∞' }} <a href="javascript:void(0)" onclick="document.querySelector('[name=min_price]').value='';document.querySelector('[name=max_price]').value='';document.getElementById('szFilterForm').submit()">&times;</a></span>
  @endif
  @if(!empty($af['filter_rating']))
    <span class="sz-active-tag">{{ $af['filter_rating'] }}★+ <a href="javascript:void(0)" onclick="document.querySelectorAll('[name=filter_rating]').forEach(function(r){r.checked=false});document.getElementById('szFilterForm').submit()">&times;</a></span>
  @endif
  @if(!empty($af['couple_friendly']))
    <span class="sz-active-tag">Couple Friendly <a href="javascript:void(0)" onclick="document.querySelector('[name=couple_friendly]').checked=false;document.getElementById('szFilterForm').submit()">&times;</a></span>
  @endif
</div>
@endif

@if (count($featured_contents) < 1 && count($room_contents) < 1)
  <div class="p-3 text-center bg-light radius-md">
    <h6 class="mb-0">{{ __('NO ROOM FOUND') }}</h6>
  </div>
@else
  <div class="row pb-15" data-aos="fade-up">
    @foreach ($featured_contents as $room)
      <div class="col-lg-4 col-md-6">
        <div class="product-default product-default-style-2 border radius-md mb-25  border-primary featured">
          <figure class="product_img">
            <a href="{{ route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->id]) }}" target="_self"
              title="{{ __('Link') }}" class="lazy-container ratio ratio-2-3 radius-sm">
              <img class="lazyload" src="{{ asset('assets/img/room/featureImage/' . $room->feature_image) }}"
                alt="{{ __('Room Image') }}">
            </a>
            @if (Auth::guard('web')->check())
              @php
                $user_id = Auth::guard('web')->user()->id;
                $checkWishList = checkroomWishList($room->id, $user_id);
              @endphp
            @else
              @php
                $checkWishList = false;
              @endphp
            @endif

            <a href="{{ $checkWishList == false ? route('addto.wishlist.room', $room->id) : route('remove.wishlist.room', $room->id) }}"
              class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'active' }}" data-tooltip="tooltip"
              data-bs-placement="top" title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
              <i class="fal fa-heart"></i>
            </a>
            <div class="rank-star">
              @for ($i = 0; $i < $room->stars; $i++)
                <i class="fas fa-star"></i>
              @endfor
            </div>
            @if(!empty($room->is_featured))
            <div style="position:absolute;top:10px;left:10px;background:#e31e24;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;z-index:2">
              <i class="fas fa-crown"></i> Featured
            </div>
            @endif
          </figure>
          <div class="product_details">
            <div class="p-20">
              <div class="product_title">
                <h4 class="title lc-1 mb-0">
                  <a href="{{ route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->id]) }}"
                    target="_self" title="{{ __('Link') }}">{{ $room->title }}</a>
                </h4>
              </div>
              @php
                $city = null;
                $State = null;
                $country = null;

                if ($room->city_id) {
                    $city = App\Models\Location\City::Where('id', $room->city_id)->first()->name;
                }
                if ($room->state_id) {
                    $State = App\Models\Location\State::Where('id', $room->state_id)->first()->name;
                }
                if ($room->country_id) {
                    $country = App\Models\Location\Country::Where('id', $room->country_id)->first()->name;
                }

              @endphp
              <div class="list-unstyled mt-10">
                <li class="icon-start location mb-2">
                  <i class="fal fa-map-marker-alt"></i>
                  <span>
                    {{ @$city }}@if (@$State)
                      , {{ $State }}
                      @endif @if (@$country)
                        , {{ $country }}
                      @endif
                  </span>
                </li>



                <li>
                  <div class="d-flex flex-wrap gap-1 justify-content-between">

                    <div class="ratings"dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
                      <div class="product-ratings rate text-xsm">
                        <div class="rating" style="width: {{ $room->average_rating * 20 }}%;"></div>
                      </div>
                      <span>{{ number_format($room->average_rating, 2) }}
                        ({{ totalRoomReview($room->id) }}
                        {{ totalRoomReview($room->id) > 1 ? __('Reviews') : __('Review') }})
                      </span>

                    </div>

                    <div>
                      @if (!empty($room->distance))
                        <span class=" icon-start d-block">
                          <i class="fas fa-map-signs"></i>
                          {{ number_format($room->distance, 2) }} {{ __('km') }}
                        </span>
                      @endif
                    </div>
                  </div>
                </li>
              </div>
              <div class="product_author mt-14">
                <a class="d-flex align-items-center gap-1"
                  href="{{ route('frontend.hotel.details', ['slug' => $room->hotelSlug, 'id' => $room->hotelId]) }}"
                  target="_self" title="{{ __('Link') }}">
                  <img class="lazyload blur-up"
                    src="{{ asset('assets/img/hotel/logo/' . $room->hotelImage) }}"alt="{{ __('Image') }}">
                  <span class="underline lc-1 font-sm" data-tooltip="tooltip" data-bs-placement="bottom"
                    aria-label="{{ $room->hotelName }}" data-bs-original-title="{{ $room->hotelName }}"
                    aria-describedby="tooltip">
                    {{ $room->hotelName }}
                  </span>
                </a>
              </div>
              @php
                $amenities = json_decode($room->amenities);
                $totalAmenities = count($amenities);
                $displayCount = 5;
              @endphp

              <ul class="product-icon_list mt-14 list-unstyled">
                @foreach ($amenities as $index => $amenitie)
                  @php
                    if ($index >= $displayCount) {
                        break;
                    }
                    $amin = App\Models\Amenitie::find($amenitie);
                  @endphp
                  <li class="list-item" data-tooltip="tooltip" data-bs-placement="bottom"
                    aria-label="{{ $amin->title }}" data-bs-original-title="{{ $amin->title }}"
                    aria-describedby="tooltip"><i class="{{ $amin->icon }}"></i></li>
                @endforeach

                @if ($totalAmenities > $displayCount)
                  <li class="more_item_show_btn">
                    (+{{ $totalAmenities - $displayCount }}<i class="fas fa-ellipsis-h"></i>)
                    <div class="more_items_icons">
                      @foreach ($amenities as $index => $amenitie)
                        @php
                          if ($index < $displayCount) {
                              continue;
                          }
                          $amin = App\Models\Amenitie::find($amenitie);
                        @endphp
                        <a data-tooltip="tooltip" data-bs-placement="bottom" aria-label="{{ $amin->title }}"
                          data-bs-original-title="{{ $amin->title }}" aria-describedby="tooltip" href="#"><i
                            class="{{ $amin->icon }}" title="{{ $amin->title }}"></i></a>
                      @endforeach
                    </div>
                  </li>
                @endif
              </ul>

            </div>
            <div class="product_bottom pt-20 pb-20 px-10 border-top text-center">
              <ul class="product-price_list list-unstyled">
                @php
                  $hour = request()->input('hour');
                  $query = App\Models\HourlyRoomPrice::where('room_id', $room->id)
                      ->where('hourly_room_prices.price', '!=', null)
                      ->join('booking_hours', 'hourly_room_prices.hour_id', '=', 'booking_hours.id')
                      ->orderBy('booking_hours.serial_number')
                      ->select('hourly_room_prices.*', 'booking_hours.serial_number');

                  if (!is_null($hour)) {
                      $query->where('hourly_room_prices.hour', '<=', $hour);
                  }

                  $prices = $query->get();
                @endphp

                @foreach ($prices as $price)
                  <li class="radius-sm">
                    <span class="h6 mb-0">{{ symbolPrice($price->price) }}</span>
                    <span class="time">{{ $price->hour }} {{ __('Hrs') }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <!-- product-default -->
      </div>
    @endforeach
    @foreach ($room_contents as $room)
      <div class="col-lg-4 col-md-6">
        <div class="product-default product-default-style-2 border radius-md mb-25">
          <figure class="product_img">
            <a href="{{ route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->id]) }}" target="_self"
              title="{{ __('Link') }}" class="lazy-container ratio ratio-2-3 radius-sm">
              <img class="lazyload" src="{{ asset('assets/img/room/featureImage/' . $room->feature_image) }}"
                alt="{{ __('Room Image') }}">
            </a>
            @if (Auth::guard('web')->check())
              @php
                $user_id = Auth::guard('web')->user()->id;
                $checkWishList = checkroomWishList($room->id, $user_id);
              @endphp
            @else
              @php
                $checkWishList = false;
              @endphp
            @endif

            <a href="{{ $checkWishList == false ? route('addto.wishlist.room', $room->id) : route('remove.wishlist.room', $room->id) }}"
              class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'active' }}" data-tooltip="tooltip"
              data-bs-placement="top" title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
              <i class="fal fa-heart"></i>
            </a>
            <div class="rank-star">
              @for ($i = 0; $i < $room->stars; $i++)
                <i class="fas fa-star"></i>
              @endfor
            </div>
            @if(!empty($room->is_featured))
            <div style="position:absolute;top:10px;left:10px;background:#e31e24;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;z-index:2">
              <i class="fas fa-crown"></i> Featured
            </div>
            @endif
          </figure>
          <div class="product_details">
            <div class="p-20">
              <div class="product_title">
                <h4 class="title lc-1 mb-0">
                  <a href="{{ route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->id]) }}"
                    target="_self" title="{{ __('Link') }}">{{ $room->title }}</a>
                </h4>
              </div>
              @php
                $city = null;
                $State = null;
                $country = null;

                if ($room->city_id) {
                    $city = App\Models\Location\City::Where('id', $room->city_id)->first()->name;
                }
                if ($room->state_id) {
                    $State = App\Models\Location\State::Where('id', $room->state_id)->first()->name;
                }
                if ($room->country_id) {
                    $country = App\Models\Location\Country::Where('id', $room->country_id)->first()->name;
                }

              @endphp
              <div class="list-unstyled mt-10">
                <li class="icon-start location mb-2">
                  <i class="fal fa-map-marker-alt"></i>
                  <span>
                    {{ @$city }}@if (@$State)
                      , {{ $State }}
                      @endif @if (@$country)
                        , {{ $country }}
                      @endif
                  </span>
                </li>

                <li>
                  <div class="d-flex flex-wrap gap-1 justify-content-between">
                    <div class="ratings"dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
                      <div class="product-ratings rate text-xsm">
                        <div class="rating" style="width: {{ $room->average_rating * 20 }}%;"></div>
                      </div>
                      <span>{{ number_format($room->average_rating, 2) }}
                        ({{ totalRoomReview($room->id) }}
                        {{ totalRoomReview($room->id) > 1 ? __('Reviews') : __('Review') }})
                      </span>
                    </div>
                    <div>
                      @if (!empty($room->distance))
                        <span class="icon-start d-block">
                          <i class="fas fa-map-signs"></i>
                          {{ number_format($room->distance, 2) }} {{ __('km') }}
                        </span>
                      @endif
                    </div>

                  </div>
                </li>
              </div>
              <div class="product_author mt-14">
                <a class="d-flex align-items-center gap-1"
                  href="{{ route('frontend.hotel.details', ['slug' => $room->hotelSlug, 'id' => $room->hotelId]) }}"
                  target="_self" title="{{ __('Link') }}">
                  <img class="lazyload blur-up"
                    src="{{ asset('assets/img/hotel/logo/' . $room->hotelImage) }}"alt="{{ __('Image') }}">
                  <span class="underline lc-1 font-sm" data-tooltip="tooltip" data-bs-placement="bottom"
                    aria-label="{{ $room->hotelName }}" data-bs-original-title="{{ $room->hotelName }}"
                    aria-describedby="tooltip">
                    {{ $room->hotelName }}
                  </span>
                </a>
              </div>
              @php
                $amenities = json_decode($room->amenities);
                $totalAmenities = count($amenities);
                $displayCount = 5;
              @endphp

              <ul class="product-icon_list mt-14 list-unstyled">
                @foreach ($amenities as $index => $amenitie)
                  @php
                    if ($index >= $displayCount) {
                        break;
                    }
                    $amin = App\Models\Amenitie::find($amenitie);
                  @endphp
                  <li class="list-item" data-tooltip="tooltip" data-bs-placement="bottom"
                    aria-label="{{ $amin->title }}" data-bs-original-title="{{ $amin->title }}"
                    aria-describedby="tooltip"><i class="{{ $amin->icon }}"></i></li>
                @endforeach

                @if ($totalAmenities > $displayCount)
                  <li class="more_item_show_btn">
                    (+{{ $totalAmenities - $displayCount }}<i class="fas fa-ellipsis-h"></i>)
                    <div class="more_items_icons">
                      @foreach ($amenities as $index => $amenitie)
                        @php
                          if ($index < $displayCount) {
                              continue;
                          }
                          $amin = App\Models\Amenitie::find($amenitie);
                        @endphp
                        <a data-tooltip="tooltip" data-bs-placement="bottom" aria-label="{{ $amin->title }}"
                          data-bs-original-title="{{ $amin->title }}" aria-describedby="tooltip" href="#"><i
                            class="{{ $amin->icon }}" title="{{ $amin->title }}"></i></a>
                      @endforeach
                    </div>
                  </li>
                @endif
              </ul>
            </div>
            <div class="product_bottom pt-20 pb-20 px-10 border-top text-center">
              <ul class="product-price_list list-unstyled">
                @php
                  $hour = request()->input('hour');
                  $query = App\Models\HourlyRoomPrice::where('room_id', $room->id)
                      ->where('hourly_room_prices.price', '!=', null)
                      ->join('booking_hours', 'hourly_room_prices.hour_id', '=', 'booking_hours.id')
                      ->orderBy('booking_hours.serial_number')
                      ->select('hourly_room_prices.*', 'booking_hours.serial_number');

                  if (!is_null($hour)) {
                      $query->where('hourly_room_prices.hour', '<=', $hour);
                  }

                  $prices = $query->get();
                @endphp
                @foreach ($prices as $price)
                  <li class="radius-sm">
                    <span class="h6 mb-0">{{ symbolPrice($price->price) }}</span>
                    <span class="time">{{ $price->hour }} {{ __('Hrs') }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <!-- product-default -->
      </div>
    @endforeach

  </div>
  {{-- @if ($room_contents->count() / $perPage > 1)
    <nav class="pagination-nav mb-40" data-aos="fade-up">
      <ul class="pagination justify-content-center">

        @if (request()->input('page'))
          @if (request()->input('page') != 1)
            <li class="page-item">
              <a class="page-link" data-page="{{ request()->input('page') - 1 }}" aria-label="Previous">
                <i class="far fa-angle-left"></i>
              </a>
            </li>
          @else
            <li class="page-item disabled">
              <a class="page-link" aria-label="Previous" tabindex="-1" aria-disabled="true">
                <i class="far fa-angle-left"></i>
              </a>
            </li>
          @endif
        @endif

        @if ($room_contents->count() / $perPage > 1)
          @for ($i = 1; $i <= ceil($room_contents->count() / $perPage); $i++)
            <li class="page-item @if (request()->input('page') == $i) active @endif">
              <a class="page-link" data-page="{{ $i }}">{{ $i }}</a>
            </li>
          @endfor
        @endif

        @php
          $totalPages = ceil($room_contents->count() / $perPage);
        @endphp

        @if (request()->input('page'))
          @if (request()->input('page') != $totalPages)
            <li class="page-item">
              <a class="page-link" data-page="{{ request()->input('page') + 1 }}" aria-label="Previous">
                <i class="far fa-angle-right"></i>
              </a>
            </li>
          @else
            <li class="page-item disabled">
              <a class="page-link" aria-label="Previous" tabindex="-1" aria-disabled="true">
                <i class="far fa-angle-right"></i>
              </a>
            </li>
          @endif
        @endif
      </ul>
    </nav>
  @endif --}}

  @if ($roomQuery->count() / $perPage > 1)
    <div class="pagination-nav mt-20 mb-40 justify-content-center" data-aos="fade-up">
      <ul class="pagination justify-content-center">
        @for ($i = 1; $i <= ceil($roomQuery->count() / $perPage); $i++)
          <li class="page-item @if (request()->input('page') == $i) active @endif">
            <a class="page-link" data-page="{{ $i }}">{{ $i }}</a>
          </li>
        @endfor
      </ul>
    </div>
  @endif
@endif
<script>
  "use strict";
  var featured_contents = {!! json_encode($featured_contents) !!};
  var room_contents = {!! json_encode($room_contents) !!};
</script>
