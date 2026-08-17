@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Signup') }}
  @else
    {{ __('Signup') }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_signup }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_signup }}
  @endif
@endsection

@section('content')
<main class="list-hotel-page">
  <section class="lyh-hero">
    <div class="lyh-bg-shape lyh-shape-1"></div>
    <div class="lyh-bg-shape lyh-shape-2"></div>

    <div class="lyh-container lyh-hero-grid" style="align-items: start;">

      {{-- Left Content - Removed top spacing padding/margins and aligned to the grid ceiling --}}
      <div class="lyh-hero-copy" style="margin-top: 0; padding-top: 0; top: 0;">
        <div class="lyh-eyebrow">
          <span>Premium Partner Network</span>
          <span>Hourly + Full Day Model</span>
          <span>24x7 Support</span>
        </div>

        <h1>List Your Hotel &amp; Boost Revenue Instantly</h1>

        <p>
          Turn your empty rooms into income with hourly bookings, reach new
          customers, and grow faster with StayZio’s premium short-stay platform.
        </p>

        <div class="lyh-benefits-row">
          <div>
            <i class="fas fa-chart-line"></i>
            <b>Increase Occupancy</b>
            <small>Monetize unused inventory and improve room utilization.</small>
          </div>

          <div>
            <i class="fas fa-users"></i>
            <b>Reach More Guests</b>
            <small>Access a large base of travelers looking for short stays.</small>
          </div>

          <div>
            <i class="fas fa-bolt"></i>
            <b>Easy Onboarding</b>
            <small>Get listed quickly with a simple digital registration flow.</small>
          </div>
        </div>
      </div>

      {{-- Signup Form --}}
      <div class="lyh-form-card" id="listHotelForm" style="margin-top: 0;">

        {{-- Login / Register Tabs --}}
        <div style="display:flex;border-bottom:2px solid #f0ebe4;margin-bottom:20px">
          <button onclick="szShowTab('register')" id="tab-register"
            style="flex:1;padding:14px;border:none;background:none;font-family:'Poppins',sans-serif;font-size:13px;font-weight:800;color:#e31e24;border-bottom:2px solid #e31e24;margin-bottom:-2px;cursor:pointer">
            <i class="fas fa-user-plus" style="margin-right:5px"></i> Register
          </button>
          <button onclick="szShowTab('login')" id="tab-login"
            style="flex:1;padding:14px;border:none;background:none;font-family:'Poppins',sans-serif;font-size:13px;font-weight:800;color:#94a3b8;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer">
            <i class="fas fa-sign-in-alt" style="margin-right:5px"></i> Login
          </button>
        </div>

        {{-- Login Panel --}}
        <div id="panel-login" style="display:none">
          <div style="text-align:center;margin-bottom:18px">
            <div style="font-size:18px;font-weight:800;color:#0c0c0e;font-family:'Poppins',sans-serif">Welcome back</div>
            <div style="font-size:13px;color:#64748b;margin-top:4px">Login to manage your hotels</div>
          </div>
          @if(Session::has('login_error'))
          <div class="alert alert-danger mb-3">{{ Session::get('login_error') }}</div>
          @endif
          <form action="{{ route('vendor.login_submit') }}" method="POST">
            @csrf
            <div class="lyh-field">
              <label>Email Address</label>
              <input type="email" name="email" class="lyh-input" placeholder="your@email.com" required autocomplete="email">
            </div>
            <div class="lyh-field">
              <label>Password</label>
              <input type="password" name="password" class="lyh-input" placeholder="Your password" required>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-bottom:16px">
              <a href="{{ route('vendor.forget.password') }}" style="font-size:12px;color:#e31e24;font-weight:600;text-decoration:none">Forgot password?</a>
            </div>
            <button type="submit" class="lyh-submit">
              Login to Dashboard <i class="fas fa-arrow-right"></i>
            </button>
          </form>
          <div style="text-align:center;margin-top:16px;font-size:13px;color:#64748b">
            Don't have an account? <a href="#" onclick="szShowTab('register');return false" style="color:#e31e24;font-weight:700">Register Now</a>
          </div>
        </div>

        {{-- Register Panel --}}
        <div id="panel-register">
        <div class="lyh-form-head">
            <span><i class="fas fa-hotel"></i></span>
            <div>
                <h3>Register Your Hotel</h3>
                <p>Fill out the form below and our representative will connect with you.</p>
            </div>
        </div>

        {{-- Success Message --}}
        @if (Session::has('success'))
            <div class="alert alert-success mb-3">
                {{ Session::get('success') }}
            </div>
        @endif

        <form action="{{ route('vendor.signup_submit') }}" method="POST">
            @csrf

            {{-- Additional Design Fields --}}
            <div class="lyh-field">
                <label>Hotel Name</label>
                <input type="text" id="hotel_name" name="hotel_name" placeholder="Enter hotel name" value="{{ old('hotel_name') }}">
                @error('hotel_name')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="lyh-field">
                <label>Contact Person Name</label>
                <input type="text" id="contact_person_name" name="contact_person_name" placeholder="Owner / Manager name" value="{{ old('contact_person_name') }}">
                @error('contact_person_name')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="lyh-field">
                <label>Mobile Number</label>
                <input type="tel" id="mobile_number" name="mobile_number" placeholder="+91 98765 43210" value="{{ old('mobile_number') }}">
                @error('mobile_number')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="lyh-field">
                <label>{{ __('Email') }} *</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('Email') }}"
                    required>
                @error('email')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="lyh-field" style="position:relative">
                <label>Locality/Area Name</label>
                <input type="text" id="location" name="location" placeholder="Type your area, e.g. Vadapalani" value="{{ old('location') }}" autocomplete="off">
                <input type="hidden" id="location_city_id" name="city_id" value="">
                <div id="locationSuggestions" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #e2e2e2;border-radius:8px;box-shadow:0 8px 20px rgba(0,0,0,.12);z-index:50;max-height:220px;overflow-y:auto;margin-top:4px"></div>
                @error('location')
                  <p class="text-danger mt-2">{{ $message }}</p>
                @enderror
            </div>
            <style>.lyh-suggestion-item:hover{background:#fdf2f2}</style>
            <script>
            (function() {
              var input = document.getElementById("location");
              var hiddenCityId = document.getElementById("location_city_id");
              var suggBox = document.getElementById("locationSuggestions");
              var debounceTimer;
              if (!input) return;

              input.addEventListener("input", function() {
                hiddenCityId.value = "";
                var q = input.value.trim();
                clearTimeout(debounceTimer);
                if (q.length < 2) {
                  suggBox.style.display = "none";
                  suggBox.innerHTML = "";
                  return;
                }
                debounceTimer = setTimeout(function() {
                  fetch("{{ route('areas.search') }}?q=" + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                      if (!data.length) {
                        suggBox.style.display = "none";
                        suggBox.innerHTML = "";
                        return;
                      }
                      suggBox.innerHTML = data.map(function(item) {
                        return '<div class="lyh-suggestion-item" data-id="' + item.id + '" data-name="' + String(item.name).replace(/"/g,"&quot;") + '" style="padding:9px 14px;cursor:pointer;font-size:14px;border-bottom:1px solid #f2f2f2">' + item.name + '</div>';
                      }).join("");
                      suggBox.style.display = "block";
                    })
                    .catch(function() { suggBox.style.display = "none"; });
                }, 250);
              });

              suggBox.addEventListener("click", function(e) {
                var item = e.target.closest(".lyh-suggestion-item");
                if (!item) return;
                input.value = item.dataset.name;
                hiddenCityId.value = item.dataset.id;
                suggBox.style.display = "none";
              });

              document.addEventListener("click", function(e) {
                if (e.target !== input && !suggBox.contains(e.target)) {
                  suggBox.style.display = "none";
                }
              });
            })();
            </script>

            {{-- Username auto-generated from email --}}

            <div class="lyh-field">
                <label>{{ __('Password') }} *</label>
                <input
                    type="password"
                    name="password"
                    placeholder="{{ __('Password') }}"
                    required>
                @error('password')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="lyh-field">
                <label>{{ __('Confirm Password') }} *</label>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="{{ __('Confirm Password') }}"
                    required>
                @error('password_confirmation')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Google reCAPTCHA --}}
            @if ($recaptchaInfo->google_recaptcha_status == 1)
                <div class="mb-3">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}

                    @error('g-recaptcha-response')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- Terms Checkbox --}}
            <label class="lyh-check">
                <input type="checkbox" required>
                <span>I agree to Privacy Policy and Terms &amp; Conditions</span>
            </label>

            {{-- Submit --}}
            <button class="lyh-submit" type="submit">
                {{ __('Signup') }}
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div style="text-align:center;margin-top:16px;font-size:13px;color:#64748b">
          Already registered? <a href="#" onclick="szShowTab('login');return false" style="color:#e31e24;font-weight:700">Login here</a>
        </div>
        </div> {{-- panel-register --}}
      </div> {{-- Form card --}}
      
    </div> {{-- Structural layout wrapper card grid closed correctly without dead elements --}}
  </section>

  {{-- Stats Section --}}
  <section class="lyh-stats">
    <div class="lyh-container lyh-stats-grid">
      <div><strong>70+</strong><span>Cities</span></div>
      <div><strong>4L+</strong><span>Happy Customers</span></div>
      <div><strong>1000+</strong><span>Partner Hotels</span></div>
      <div><strong>24x7</strong><span>Support Team</span></div>
    </div>
  </section>

  {{-- How It Works --}}
  <section class="lyh-section">
    <div class="lyh-container">
      <div class="lyh-title">
        <span>How It Works</span>
        <h2>A simple partner onboarding journey</h2>
      </div>

      <div class="lyh-steps">
        <div>
          <em>1</em>
          <h3>Register</h3>
          <p>Submit your hotel details through the quick sign-up form.</p>
        </div>

        <div>
          <em>2</em>
          <h3>Get Onboarded</h3>
          <p>Our team verifies your property and guides you through the partner setup.</p>
        </div>

        <div>
          <em>3</em>
          <h3>List Your Rooms</h3>
          <p>Add room inventory, pricing plans, and available stay durations.</p>
        </div>

        <div>
          <em>4</em>
          <h3>Start Earning</h3>
          <p>Receive bookings from customers looking for short stays and flexible check-ins.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Why Partner --}}
  <section class="lyh-section lyh-why">
    <div class="lyh-container lyh-why-grid">
      <div class="lyh-title lyh-title-left">
        <span>Why Partner With StayZio?</span>
        <h2>A smarter way to grow hotel revenue</h2>
        <p>
          Designed for premium hospitality brands that want stronger occupancy,
          flexible monetization, and a cleaner digital onboarding experience.
        </p>
      </div>

      <div class="lyh-feature-grid">
        <div>
          <i class="fas fa-indian-rupee-sign"></i>
          <h3>Increase revenue from unused room hours</h3>
          <p>Monetize daytime vacancy through hourly and flexible stays.</p>
        </div>

        <div>
          <i class="fas fa-user-check"></i>
          <h3>Reach short-stay guests</h3>
          <p>Connect with transit travelers, business users, couples, and flexible-stay customers.</p>
        </div>

        <div>
          <i class="fas fa-sliders-h"></i>
          <h3>Flexible pricing control</h3>
          <p>Define durations, pricing plans, and availability in a structured way.</p>
        </div>

        <div>
          <i class="fas fa-headset"></i>
          <h3>Partner-side support</h3>
          <p>Get support for onboarding, listing activation, and booking operations.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="lyh-section lyh-faq-wrap">
    <div class="lyh-container">
      <div class="lyh-title">
        <span>Partner FAQs</span>
        <h2>Frequently asked questions</h2>
      </div>

      <div class="lyh-faq">
        <details open>
          <summary>How does StayZio help increase revenue?</summary>
          <p>StayZio helps hotels monetize unsold inventory through hourly, overnight, and full-day bookings.</p>
        </details>

        <details>
          <summary>Can I control pricing and room availability?</summary>
          <p>Yes. Hotel partners can manage inventory, stay durations, pricing plans, and room availability.</p>
        </details>

        <details>
          <summary>Which properties can join?</summary>
          <p>Hotels, resorts, boutique stays, business hotels, and compatible hospitality properties can be considered.</p>
        </details>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="lyh-cta">
    <div class="lyh-container lyh-cta-box">
      <div>
        <h2>Start earning today with StayZio</h2>
        <p>Join our growing hotel partner network and unlock new business opportunities.</p>
      </div>

      <a href="#listHotelForm">
        List Your Hotel Now
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </section>
</main>

@section('script')
<script>
function szShowTab(tab) {
  var isReg = tab === 'register';
  document.getElementById('panel-register').style.display = isReg ? '' : 'none';
  document.getElementById('panel-login').style.display   = isReg ? 'none' : '';
  document.getElementById('tab-register').style.color    = isReg ? '#e31e24' : '#94a3b8';
  document.getElementById('tab-register').style.borderBottomColor = isReg ? '#e31e24' : 'transparent';
  document.getElementById('tab-login').style.color       = isReg ? '#94a3b8' : '#e31e24';
  document.getElementById('tab-login').style.borderBottomColor   = isReg ? 'transparent' : '#e31e24';
}
if(window.location.search.includes('tab=login')) szShowTab('login');
</script>
@endsection
@endsection