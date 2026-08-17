@extends('frontend.detaillayout')

@section('pageHeading')
  {{ $roomContent->title }}
@endsection

@section('metaKeywords')
  @if (!empty($roomContent))
    {{ $roomContent->meta_keyword }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($roomContent))
    {{ $roomContent->meta_description }}
  @endif
@endsection

@section('ogTitle')
  @if (!empty($roomContent))
    {{ $roomContent->title }}
  @endif
@endsection

@section('content')

<nav class="tab-nav" id="tabNav">
    <div class="tab-nav-inner">
        <div class="tab-list" id="tabList" aria-label="Hotel detail sections">
            <a href="#basicInfo"><div class="tab-item active" onclick="tabScroll('basicInfo',this)"><i class="fas fa-circle-info"></i> Intro</div></a>
            <a href="#amenities"><div class="tab-item" onclick="tabScroll('amenities',this)"><i class="fas fa-th-large"></i> Facilities</div></a>
            <a href="#perks"><div class="tab-item" onclick="tabScroll('perks',this)"><i class="fas fa-gem"></i> Perks</div></a>
            <a href="#policies"><div class="tab-item" onclick="tabScroll('policies',this)"><i class="fas fa-clipboard-list"></i> Policies</div></a>
            <a href="#restrictions"><div class="tab-item" onclick="tabScroll('restrictions',this)"><i class="fas fa-ban"></i> Restriction</div></a>
        </div>

        @php
            $startingPrice = $hourlyPrices->first();
        @endphp

        <button type="button" class="tab-book-cta" onclick="submit_booking();">
            Book Now — <span class="mobile-cta-price">{{ $startingPrice ? symbolPrice($startingPrice->price) : '₹0' }}</span>
            <i class="fas fa-arrow-right" style="font-size:.65rem;margin-left:4px;"></i>
        </button>
    </div>
</nav>

<div class="page-body">

    <div class="left p-3">

        {{-- Gallery Section --}}
        <section class="gallery-wrap">
            <div class="gallery-grid">
                @foreach ($roomImages->take(5) as $index => $gallery)
                    <div class="{{ $index == 0 ? 'gallery-main' : 'gallery-thumb' }}"
                         onclick="openGallery({{ $index }})"
                         @if($index == 4) style="position:relative;" @endif>

                        <img src="{{ asset('assets/img/room/room-gallery/' . $gallery->image) }}"
                             alt="{{ $roomContent->title }}">

                        @if ($index == 4)
                            <button class="view-all-btn" onclick="event.stopPropagation();openGallery(0)">
                                <i class="fas fa-images"></i> View all
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Hotel Header --}}
        <div class="scard" id="basicInfo">
            <div class="hotel-hdr">
                <div class="hotel-name-row">
                    <h1 class="hotel-title">{{ $roomContent->hoteltitle ?? $roomContent->title }}</h1>
                    <div class="hdr-actions">
                        <button class="hdr-btn" id="wishBtn2" onclick="toggleWish()" title="Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="hdr-btn" onclick="shareHotel()" title="Share">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="meta-row">
                    <span class="rating-pill">
                        <i class="fas fa-star"></i>
                        {{ number_format($roomContent->average_rating ?? 0, 1) }}
                    </span>

                    <span class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            {!! $i <= ($roomContent->stars ?? 0) ? '★' : '☆' !!}
                        @endfor
                    </span>

                    <span class="review-ct">Based on {{ $numOfReview }} reviews</span>
                    <span class="star-tier">{{ $roomContent->stars ?? 0 }}-Star Hotel</span>
                </div>

                <div class="props-row">
                    <span class="prop-tag"><i class="fas fa-heart"></i> Couple Friendly</span>
                    <span class="prop-tag"><i class="fas fa-id-card"></i> Accepts Local ID</span>
                    <span class="prop-tag"><i class="fas fa-user-check"></i> 18+ Allowed</span>
                    <span class="prop-tag"><i class="fas fa-globe"></i> Foreign Guests Welcome</span>
                </div>

                <div class="addr-line">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $roomContent->address }}</span>
                </div>
            </div>
        </div>

        {{-- Overview --}}
        <div class="scard" id="perks">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-gem"></i></div>
                <div>
                    <span class="scard-eyebrow">Overview</span>
                    <div class="scard-title">About this room</div>
                </div>
            </div>
            <div class="scard-body tinymce-content">
                {!! $roomContent->description !!}
            </div>
        </div>

        {{-- Amenities --}}
        @if (!empty($roomContent->amenities) && $roomContent->amenities != '[]')
        <div class="scard" id="amenities">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-th-large"></i></div>
                <div>
                    <span class="scard-eyebrow">Amenities</span>
                    <div class="scard-title">Things that make the stay better</div>
                </div>
            </div>
            <div class="scard-body">
                <div class="am-grid">
                    @php
                        $amenities = json_decode($roomContent->amenities, true) ?? [];
                    @endphp

                    @foreach ($amenities as $amenityId)
                        @php
                            $amenity = App\Models\Amenitie::find($amenityId);
                        @endphp
                        @if ($amenity)
                            <div class="am-item">
                                <i class="{{ $amenity->icon }}"></i>{{ $amenity->title }}
                            </div>
                        @endif
                    @endforeach
                </div>

              
            </div>
        </div>
        @endif

        {{-- Location --}}
        <div class="scard" id="location">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <span class="scard-eyebrow">Location</span>
                    <div class="scard-title">Where you need to go</div>
                </div>
            </div>
            <div class="scard-body">
                <div id="map" class="map-canvas" style="height: 400px; width: 100%;"></div>
                <input type="text" id="search-address" value="{{ $roomContent->address }}" style="display: none !important;">
                <div style="display: none !important;">
                    <div id="room-single-map"></div>
                    <div id="hotel-map"></div>
                    <div id="gmap"></div>
                    <div id="map_canvas"></div>
                    <div id="contact-map"></div>
                    <div id="main-map"></div>
                </div>
                <div class="addr-block mt-3">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><strong>{{ $roomContent->title }}</strong>, {{ $roomContent->address }}</span>
                </div>
            </div>
        </div>

        {{-- Policies --}}
        <div class="scard" id="policies">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <span class="scard-eyebrow">Policies</span>
                    <div class="scard-title">What you must know</div>
                </div>
            </div>
            <div class="scard-body tinymce-content">
                {!! $roomContent->room_policy ?? '<p>No policy information available.</p>' !!}
            </div>
        </div>

        {{-- Restrictions --}}
        <div class="scard" id="restrictions">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-info-circle"></i></div>
                <div>
                    <span class="scard-eyebrow">Restrictions</span>
                    <div class="scard-title">What to follow</div>
                </div>
            </div>
            
            @php
                $content = $roomContent->restrictions;
                $restrictions = [];
                if (!empty($content)) {
                    $restrictions = json_decode($content, true) ?? [];
                }
            @endphp

            @if (!empty($restrictions))
            <div class="scard-body">
                <div class="rest-grid">
                    @foreach ($restrictions as $restriction)
                        @php
                            $type = $restriction['type'] ?? 'allowed';
                            $textClass = ($type == 'allowed') ? 'rest-allowed' : '';
                            $badgeClass = ($type == 'allowed') ? 'rest-allowed-badge' : 'rest-denied-badge';
                        @endphp

                        <div class="rest-item">
                            <span class="rest-em">
                                {{ $restriction['icon'] ?? 'ℹ️' }}
                            </span>

                            <span class="{{ $textClass }}">
                                {{ $restriction['title'] ?? '' }}
                            </span>

                            <span class="{{ $badgeClass }}">
                                {{ ucfirst($restriction['type'] ?? '') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Similar Hotels --}}
        @if (count($rooms) > 0)
        <div class="scard" id="similar">
            <div class="scard-head">
                <div class="scard-head-icon"><i class="fas fa-hotel"></i></div>
                <div>
                    <span class="scard-eyebrow">Similar Hotels</span>
                    <div class="scard-title">Similar hotels like {{ $roomContent->title }}</div>
                </div>
            </div>
            <div class="scard-body">
                <div class="sim-row">
                    @foreach ($rooms->take(4) as $room)
                        <div class="sim-card">
                            <a href="{{ route('frontend.room.details', ['slug' => $room->slug, 'id' => $room->id]) }}">
                                <img class="sim-img"
                                     src="{{ asset('assets/img/room/featureImage/' . $room->feature_image) }}"
                                     alt="{{ $room->title }}">
                            </a>
                            <div class="sim-body">
                                <div class="sim-top">
                                    <span class="sim-rb">{{ number_format($room->average_rating ?? 0, 1) }}</span>
                                    <span class="sim-rv">{{ $room->num_of_reviews ?? 0 }} reviews</span>
                                </div>
                                <div class="sim-nm">{{ $room->title }}</div>
                                <div class="sim-loc"><i class="fas fa-map-marker-alt"></i>{{ $room->hotelName }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

    @php
        $startingPrice = $hourlyPrices->first();
        $basePrice = $startingPrice ? $startingPrice->price : 0;
        $currencySymbol = preg_replace('/[0-9,.\s]+/', '', symbolPrice(0));
    @endphp

    {{-- Booking Sidebar --}}
    <div class="right-col">
        <div class="bwidget mobile-collapsed" id="bookingWidget">

            {{-- Top Price --}}
            <div class="bw-top">
                <div class="bw-from">Starting from</div>
                <div class="bw-price-row">
                    <span class="bw-price" id="widPrice">
                        {{ $startingPrice ? symbolPrice($startingPrice->price) : $currencySymbol . '0' }}
                    </span>
                    <span class="bw-per">/ slot</span>
                </div>
                <div class="bw-meta">
                    <span class="bw-rb">{{ number_format($roomContent->average_rating ?? 0, 1) }}</span>
                    <span class="bw-rv">Excellent · {{ $numOfReview }} reviews</span>
                </div>
            </div>

            <form id="roomCheckoutForm" action="{{ route('frontend.room.room_booking') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="room_id" value="{{ $roomContent->id }}">

                {{-- Hourly Pricing Slots Container --}}
                <div style="padding:1.2rem 1.4rem 0">
                    <div class="bw-dtabs" id="hourlySlotsContainer">
                        @foreach ($hourlyPrices as $index => $hourlyPrice)
                            @php
                                $bookingHour = App\Models\BookingHour::find($hourlyPrice->hour_id);
                                $hourValue = optional($bookingHour)->hour ?? '';
                            @endphp

                            <label class="bw-dt {{ $index == 0 ? 'on' : '' }}">
                                <input type="radio"
                                       name="price"
                                       value="{{ $hourlyPrice->id }}"
                                       data-price="{{ $hourlyPrice->price }}"
                                       data-hour-label="{{ $hourValue }}"
                                       {{ $index == 0 ? 'checked' : '' }}
                                       hidden>

                                <span class="bw-dt-lbl">
                                    {{-- Standardized output flag matching rule criteria --}}
                                    @if ($hourValue == 'Full Day' || $hourValue == '24')
                                         Full Day
                                    @else
                                         {{ $hourValue }} Hrs
                                    @endif
                                </span>

                                <span class="bw-dt-pr">
                                    {{ symbolPrice($hourlyPrice->price) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bw-form">

                    {{-- Check-in Date Only --}}
                    <div class="bw-field">
                        <label>Check-in Date</label>
                        <input type="date"
                               class="bw-inp"
                               id="checkInDate"
                               name="checkInDate"
                               min="{{ date('Y-m-d') }}"
                               value="{{ ($checkinDate && \Carbon\Carbon::parse($checkinDate)->gte(now()->startOfDay())) ? \Carbon\Carbon::parse($checkinDate)->format('Y-m-d') : date('Y-m-d') }}">
                    </div>

                    {{-- Check-out Date --}}
                    <div class="bw-field" id="checkOutDateField" style="display: none;">
                        <label>Check-out Date</label>
                        <input type="date"
                               class="bw-inp"
                               id="checkOutDate"
                               name="checkOutDate"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               value="">
                    </div>

                    {{-- Check-in Time --}}
                    <div class="bw-field">
                        <label>Check-in Time</label>
                        <input type="time"
                               class="bw-inp"
                               id="checkInTime"
                               name="checkInTime"
                               value="{{ request()->input('checkInTime') ? date('H:i', strtotime(request()->input('checkInTime'))) : date('H:i') }}">
                    </div>

                    {{-- Room Count, Guests, and Visible Children Selector Wrapper --}}
                    <div class="bw-row2">
                        <div class="bw-field">
                            <label>Rooms</label>
                            <select class="bw-inp" id="roomCount" name="room_qty">
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="bw-field">
                            <label>Adults</label>
                            <select class="bw-inp" name="adult">
                                @for ($i = 1; $i <= ($roomContent->adult ?? 1); $i++)
                                    <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>
                                        {{ $i }} Adult{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Visible Children Dropdown Option Element (Replaced hidden block) --}}
                    <div class="bw-field mt-2">
                        <label>Children</label>
                        <select class="bw-inp" name="children" id="childrenCount">
                            @for ($i = 0; $i <= ($roomContent->children ?? 5); $i++)
                                <option value="{{ $i }}">{{ $i }} Child{{ $i != 1 ? 'ren' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Add-ons --}}
                    @if (!empty($additionalServices) && count($additionalServices) > 0)
                        <div class="bw-addons">
                            <div class="bw-addons-head">
                                <div>
                                    <span class="bw-addons-kicker">Make your stay special</span>
                                    <h4>Add-ons</h4>
                                </div>
                                <span class="bw-addons-note">Optional</span>
                            </div>

                            <div class="bw-addon-list">
                                @foreach ($additionalServices as $id => $charge)
                                    @php
                                        $serviceContent = App\Models\AdditionalServiceContent::where([
                                            ['language_id', $language->id],
                                            ['additional_service_id', $id],
                                        ])->first();

                                        $serviceTitle = $serviceContent->title ?? 'Additional Service';
                                        $serviceDescription = $serviceContent->description ?? 'Optional service';
                                    @endphp

                                    <label class="bw-addon-item">
                                        <input id="additional_service_{{ $id }}" type="checkbox"
                                               class="addon-check"
                                               name="additional_service[]"
                                               value="{{ $id }}"
                                               data-price="{{ $charge }}"
                                               data-shipping_charge="{{ $charge }}"
                                               >

                                        <span class="bw-addon-icon"><i class="fas fa-gift"></i></span>

                                        <span class="bw-addon-info">
                                            <strong>{{ $serviceTitle }}</strong>
                                            <small>{{ $serviceDescription }}</small>
                                        </span>

                                        <span class="bw-addon-price">
                                            + {{ symbolPrice($charge) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Price Summary --}}
                    <div class="bw-summary">
                        <div>
                            <span>Room price <span id="summaryNights" style="font-size:11px;color:#e31e24;font-weight:700"></span></span>
                            <strong>
                                {{ $currencySymbol }}<span id="summaryRoomPrice"></span>
                            </strong>
                        </div>

                        <div>
                            <span>Add-ons</span>
                            <strong>
                                {{ $currencySymbol }}<span id="summaryAddonPrice">0</span>
                            </strong>
                        </div>

                        <div>
                            <span>No. of Rooms</span>
                            <strong>
                                × <span id="summaryRoomCount">1</span>
                            </strong>
                        </div>

                        <div class="bw-summary-total">
                            <span>Total payable</span>
                            <strong>
                                {{ $currencySymbol }}<span id="summaryTotalPrice">{{ number_format($startingPrice->price) }}</span>
                            </strong>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="gateway" value="razorpay" id="gatewayField" />

                <!-- Payment Method Toggle (optional - admin can choose later when Razorpay key is added) -->
                <div style="padding:10px 14px;background:#f9f7f6;border-radius:10px;margin-bottom:12px;border:1px solid #efe9e7">
                    <div style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.07em;color:#9aa0ae;margin-bottom:6px">
                        Payment Method
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="fas fa-lock" style="color:#e31e24;font-size:14px"></i>
                        <span style="font-size:13px;font-weight:700;color:#1a1a2e">Secure Online Payment</span>
                        <span style="font-size:10px;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:10px;font-weight:800;margin-left:auto">RAZORPAY</span>
                    </div>
                    <div style="font-size:11px;color:#7a8394;margin-top:6px">
                        Pay securely via UPI, Card, Net Banking, or Wallet.
                    </div>
                </div>

                <!-- Cancellation Policy Notice -->
                <div style="padding:10px 12px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;margin-bottom:12px">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <i class="fas fa-info-circle" style="color:#ca8a04;font-size:13px"></i>
                        <span style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:0.07em;color:#92400e">Cancellation Policy</span>
                    </div>
                    <div style="font-size:11px;color:#78350f;line-height:1.7">
                        &bull; <b>Full refund</b> if cancelled <b>24+ hours</b> before check-in<br>
                        &bull; <b>Full refund</b> if cancelled within <b>15 minutes</b> of booking<br>
                        &bull; No refund for no-shows or mid-stay cancellations
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bw-cta-wrap">
                    <button type="button" class="bw-cta" onclick="submit_booking()">
                        <i class="fas fa-lock" style="font-size:.8rem;"></i> Book Now
                    </button>

                    <div class="bw-trust">
                        <div class="bw-trust-item"><i class="fas fa-check-circle"></i> Instant booking confirmation</div>
                        <div class="bw-trust-item"><i class="fas fa-undo"></i> Free cancellation upto 2 hrs before</div>
                        <div class="bw-trust-item"><i class="fas fa-shield-alt"></i> 100% secure payment gateway</div>
                        <div class="bw-trust-item"><i class="fas fa-headset"></i> 24/7 customer support</div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Cancellation --}}
        <div class="cancel-card">
            <h5><i class="fas fa-check-circle"></i> Free Cancellation Available</h5>
            <p>
                Cancel up to 2 hours before your check-in time for a full refund.
                Refund processed within 5–7 business days to your original payment method.
            </p>
        </div>

        {{-- Help --}}
        <div class="help-card">
            <div class="big-ico"><i class="fas fa-headset"></i></div>
            <h4>Need Help?</h4>
            <p>Our support team is available 24/7 for you</p>
            <div class="help-btns">
                <button class="h-btn call"><i class="fas fa-phone"></i> Call Us</button>
                <button class="h-btn wa"><i class="fab fa-whatsapp"></i> WhatsApp</button>
            </div>
        </div>
    </div> 
</div>

{{-- Gallery Modal --}}
<div class="gallery-modal" id="galleryModal">
    <div class="gm-top">
        <div>
            <div class="gm-title">{{ $roomContent->title }}</div>
            <div class="gm-count" id="gmCount">Photo 1 of 5</div>
        </div>
        <button class="gm-close" onclick="closeGallery()"><i class="fas fa-times"></i></button>
    </div>
    <div class="gm-main">
        <button class="gm-nav prev" onclick="changePhoto(-1)"><i class="fas fa-chevron-left"></i></button>
        <img class="gm-img" id="gmMainImg" src="" alt="Hotel Photo">
        <button class="gm-nav next" onclick="changePhoto(1)"><i class="fas fa-chevron-right"></i></button>
    </div>
    <div class="gm-thumbs" id="gmThumbs"></div>
</div>

{{-- Toast --}}
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Referral code copied!</span>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
    var room_id = "{{ $roomContent->id }}";
    var latitude = "{{ $roomContent->latitude }}";
    var longitude = "{{ $roomContent->longitude }}";
    var visitor_store_url = "{{ route('frontend.store_visitor') }}";
    var searchUrl = "{{ route('frontend.room.details.get_hourly_price', ['slug' => $roomContent->slug, 'id' => $roomContent->id]) }}";
    var holidays = @json($holidayDates ?? []);
</script>

<script>
function initMap() {
    var lat = parseFloat("{{ $roomContent->latitude ?? 0 }}");
    var lng = parseFloat("{{ $roomContent->longitude ?? 0 }}");
    var roomLocation = { lat: lat, lng: lng };

    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: roomLocation,
        mapTypeControl: false,
        streetViewControl: true
    });

    var marker = new google.maps.Marker({
        position: roomLocation,
        map: map,
        title: "{{ $roomContent->title }}"
    });

    var infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="padding: 5px;">
                <strong style="font-size:16px;">{{ $roomContent->title }}</strong><br>
                <span style="color:#555;">{{ $roomContent->address }}</span>
            </div>
        `
    });

    marker.addListener("click", function() {
        infoWindow.open(map, marker);
    });
}

const photos = @json($roomImages->take(20)->map(function($gallery) {
        return asset('assets/img/room/room-gallery/' . $gallery->image); // Change 'image_path' to your column
    }));
    
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ $basicInfo->google_map_api_key }}&libraries=places&callback=initMap" async defer></script>
<script src="{{ asset('assets/front/js/room-single-map.js') }}"></script>

<script src="{{ asset('assets/front/js/room-checkout.js') }}"></script>
  <script>
    @if (old('gateway') == 'autorize.net')
      $(document).ready(function() {
        $('#stripe-element').removeClass('d-none');
      })
    @endif
  </script>

<script>
function formatPrice(value) {
    return Number(value).toLocaleString('en-IN');
}

function updateBookingTotal() {
    const selectedPrice = document.querySelector('input[name="price"]:checked');
    let roomPrice = selectedPrice ? parseFloat(selectedPrice.dataset.price || 0) : 0;

    const checkOutFieldContainer = document.getElementById('checkOutDateField');
    const checkOutDateInput = document.getElementById('checkOutDate');
    const checkInDateVal = document.getElementById('checkInDate').value;

    // Show/hide checkout date for Full Day
    const isFullDay = selectedPrice && (selectedPrice.dataset.hourLabel === 'Full Day' || selectedPrice.dataset.hourLabel === '24');
    if (isFullDay && checkInDateVal) {
        if (checkOutFieldContainer) checkOutFieldContainer.style.display = 'block';
        // Default checkout = checkin + 1 day if not set
        if (checkOutDateInput && !checkOutDateInput.value) {
            const d = new Date(checkInDateVal);
            d.setDate(d.getDate() + 1);
            checkOutDateInput.value = d.toISOString().split('T')[0];
        }
    } else {
        if (checkOutFieldContainer) checkOutFieldContainer.style.display = 'none';
        if (checkOutDateInput) checkOutDateInput.value = '';
    }

    // Calculate number of nights for Full Day bookings
    let numNights = 1;
    if (isFullDay && checkInDateVal && checkOutDateInput && checkOutDateInput.value) {
        const d1 = new Date(checkInDateVal);
        const d2 = new Date(checkOutDateInput.value);
        const diff = Math.round((d2 - d1) / 86400000);
        if (diff > 0) numNights = diff;
    }

    const roomCountEl = document.getElementById('roomCount');
    let roomCount = roomCountEl ? parseInt(roomCountEl.value || 1) : 1;

    let addonTotal = 0;
    document.querySelectorAll('.addon-check:checked').forEach(function (checkbox) {
        addonTotal += parseFloat(checkbox.dataset.price || 0);
    });

    const roomSubtotal = roomPrice * numNights * roomCount;
    const total = roomSubtotal + addonTotal;

    // Update nights display
    const nightsEl = document.getElementById('summaryNights');
    if (nightsEl) nightsEl.textContent = isFullDay && numNights > 1 ? '× ' + numNights + ' nights' : '';
    const currency = @json($currencySymbol);

    const summaryRoomPriceEl = document.getElementById('summaryRoomPrice');
    if(summaryRoomPriceEl) summaryRoomPriceEl.textContent = formatPrice(roomSubtotal);
    
    const summaryAddonPriceEl = document.getElementById('summaryAddonPrice');
    if(summaryAddonPriceEl) summaryAddonPriceEl.textContent = formatPrice(addonTotal);
    
    const summaryRoomCountEl = document.getElementById('summaryRoomCount');
    if(summaryRoomCountEl) summaryRoomCountEl.textContent = roomCount;
    
    const summaryTotalPriceEl = document.getElementById('summaryTotalPrice');
    if(summaryTotalPriceEl) summaryTotalPriceEl.textContent = formatPrice(total);

    const widPrice = document.getElementById('widPrice');
    if (widPrice) {
        widPrice.textContent = currency + formatPrice(total);
    }

    const mobileCtaPrice = document.querySelector('.mobile-cta-price');
    if (mobileCtaPrice) {
        mobileCtaPrice.textContent = currency + formatPrice(total);
    }
}

function bindPricingSlotListeners() {
    document.querySelectorAll('input[name="price"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.bw-dt').forEach(function (item) {
                item.classList.remove('on');
            });
            const label = this.closest('.bw-dt');
            if (label) label.classList.add('on');
            updateBookingTotal();
        });
    });

    // Recalculate when dates or room count change
    var checkOut = document.getElementById('checkOutDate');
    if (checkOut) checkOut.addEventListener('change', updateBookingTotal);

    var checkIn = document.getElementById('checkInDate');
    if (checkIn) {
        checkIn.addEventListener('change', function() {
            // If user picked today, ensure time is not in past
            var today = new Date().toISOString().split('T')[0];
            var timeEl = document.getElementById('checkInTime');
            if (this.value === today && timeEl) {
                var now = new Date();
                var currentTime = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
                if (timeEl.value < currentTime) {
                    timeEl.value = currentTime;
                }
            }
            // Auto-bump checkout date if it's before/equal check-in
            var co = document.getElementById('checkOutDate');
            if (co && co.value && co.value <= this.value) {
                var d = new Date(this.value);
                d.setDate(d.getDate() + 1);
                co.value = d.toISOString().split('T')[0];
            }
            updateBookingTotal();
        });
    }

    var roomCount = document.getElementById('roomCount');
    if (roomCount) roomCount.addEventListener('change', updateBookingTotal);
}

function fetchLiveRoomRates() {
    const checkInDateVal = document.getElementById('checkInDate').value;
    const checkInTimeVal = document.getElementById('checkInTime').value;
    
    if (!checkInDateVal || !checkInTimeVal || typeof searchUrl === 'undefined') return;

    let ajaxUrl = searchUrl + "?checkInDates=" + encodeURIComponent(checkInDateVal) + "&checkInTime=" + encodeURIComponent(checkInTimeVal);

    fetch(ajaxUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(htmlMarkup => {
        const container = document.getElementById('hourlySlotsContainer');
        if (container && htmlMarkup.trim() !== '') {
            container.innerHTML = htmlMarkup;
            
            bindPricingSlotListeners();
            const firstRadio = container.querySelector('input[name="price"]');
            if (firstRadio) {
                firstRadio.checked = true;
                const parentLabel = firstRadio.closest('.bw-dt');
                if (parentLabel) parentLabel.classList.add('on');
            }
            updateBookingTotal();
        }
    })
    .catch(error => console.error('Error fetching dynamic live inventory configurations:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    
    bindPricingSlotListeners();
 
    const roomCount = document.getElementById('roomCount');
    if (roomCount) {
        roomCount.addEventListener('change', updateBookingTotal);
    }

    document.querySelectorAll('.addon-check').forEach(function (checkbox) {
        checkbox.addEventListener('change', updateBookingTotal);
    });

    const checkInDateInput = document.getElementById('checkInDate');
    const checkInTimeInput = document.getElementById('checkInTime');

    if (checkInTimeInput) {
        checkInTimeInput.addEventListener('change', fetchLiveRoomRates);
    }

    if (checkInDateInput) {
        checkInDateInput.addEventListener('change', function() {
            fetchLiveRoomRates();
            updateBookingTotal();
        });

        if (window.jQuery || window.$) {
            $(checkInDateInput).on('changeDate change', function() {
                fetchLiveRoomRates();
                updateBookingTotal();
            });
        }

        const dateValueObserver = new MutationObserver(function(mutationsList) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    fetchLiveRoomRates();
                    updateBookingTotal();
                }
            }
        });
        dateValueObserver.observe(checkInDateInput, { attributes: true });

        const nativeValueDescriptor = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');
        Object.defineProperty(checkInDateInput, 'value', {
            get: function() {
                return nativeValueDescriptor.get.call(this);
            },
            set: function(val) {
                nativeValueDescriptor.set.call(this, val);
                fetchLiveRoomRates();
                updateBookingTotal();
            }
        });
    }

    fetchLiveRoomRates();
});

var _booking_in_progress = false;
function submit_booking(){
    // Prevent double-click / duplicate submission
    if (_booking_in_progress) {
        console.log('Booking already in progress, ignoring duplicate click');
        return false;
    }
    _booking_in_progress = true;
    
    // Disable all Book Now buttons visually
    document.querySelectorAll('.bw-cta, .tab-book-cta').forEach(function(btn) {
        btn.style.opacity = '0.5';
        btn.style.pointerEvents = 'none';
        btn.disabled = true;
    });
    
    // Refresh CSRF token before submit to avoid 419 errors
    var meta = document.querySelector('meta[name="csrf-token"]');
    var formToken = document.querySelector('#roomCheckoutForm input[name="_token"]');
    if (meta && formToken) {
        formToken.value = meta.getAttribute('content');
    }
    
    document.getElementById('roomCheckoutForm').submit();
    return true;
}
</script>


<script>
  var room_id = "{{ $roomContent->id }}";
  var latitude = "{{ $roomContent->latitude }}";
  var longitude = "{{ $roomContent->longitude }}";
  var visitor_store_url = "{{ route('frontend.store_visitor') }}";
  var searchUrl = "{{ route('frontend.room.details.get_hourly_price', ['slug' => $roomContent->slug, 'id' => $roomContent->id]) }}";
  var holidays = @json($holidayDates);
</script>

@endsection