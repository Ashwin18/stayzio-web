<!DOCTYPE html>
<html lang="zxx" dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="KreativDev">
  <meta name="keywords" content="@yield('metaKeywords')">
  <meta name="description" content="@yield('metaDescription')">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta property="og:title" content="@yield('ogTitle')">
  <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
  <link class="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  <link class="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  @includeIf('frontend.partials.styles')
</head>

<body>

  <div id="stayzio-loader" class="stayzio-loader" aria-label="Loading StayZio">
    <div class="stayzio-loader-card">
      <img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio Logo">
      <div class="stayzio-loader-line"><span></span></div>
    </div>
  </div>

  @php
      $ssNavCities = \Illuminate\Support\Facades\DB::table('cities')
          ->where(
              'language_id',
              \Illuminate\Support\Facades\DB::table('languages')
                  ->where('is_default',1)
                  ->value('id') ?? 20
          )
          ->orderBy('name')
          ->get();

      // Home page sends check_time as e.g. "12:00 PM".
      // HTML <input type="time"> requires 24-hour HH:mm, so normalize it here.
      try {
          $ssCheckTimeValue = \Carbon\Carbon::parse(request('check_time', '20:00'))->format('H:i');
      } catch (\Exception $e) {
          $ssCheckTimeValue = '20:00';
      }

      try {
          $ssDateValue = \Carbon\Carbon::parse(request('date_range', now()->format('Y-m-d')))->format('Y-m-d');
      } catch (\Exception $e) {
          $ssDateValue = now()->format('Y-m-d');
      }

      $ssSelectedHour = (int) request('hour', 24);
  @endphp

  <header class="topbar sz-results-header">
    <div class="topbar-inner">

      <button class="site-hamburger"
              type="button"
              aria-label="Open menu"
              onclick="toggleSiteMenu()">
        <i class="fas fa-bars"></i>
      </button>

      <a href="{{ url('/') }}" class="brand stayzio-main-brand">
        <img src="{{ asset('stayzio/images/stayzio-logo.png') }}"
             alt="StayZio Logo"
             class="stayzio-brand-logo">
      </a>

      {{-- Same search data model as Home page:
           filter_city + date_range + check_time + hour --}}
      <form action="{{ route('frontend.rooms') }}"
            method="GET"
            class="search-strip sz-results-search">

        <div class="ss-field ss-field-location">
          <div class="ss-field-text">
            <small>Where are you going?</small>
            <select name="filter_city"
                    aria-label="Where are you going?">
              <option value="">All Cities</option>
              @foreach($ssNavCities as $ssCity)
                <option value="{{ $ssCity->id }}"
                        @selected((string)request('filter_city') === (string)$ssCity->id)>
                  {{ $ssCity->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="ss-field ss-field-date">
          <div class="ss-field-text">
            <small>Check-in Date</small>
            <input type="date"
                   name="date_range"
                   min="{{ now()->format('Y-m-d') }}"
                   value="{{ $ssDateValue }}"
                   aria-label="Check-in Date">
          </div>
        </div>

        <div class="ss-field ss-field-time">
          <div class="ss-field-text">
            <small>Check-in Time</small>
            <input type="time"
                   name="check_time"
                   value="{{ $ssCheckTimeValue }}"
                   aria-label="Check-in Time">
          </div>
        </div>

        <div class="ss-field ss-field-duration">
          <div class="ss-field-text">
            <small>Duration</small>
            <div class="ss-duration-pills">
              <label class="ss-pill">
                <input type="radio"
                       name="hour"
                       value="3"
                       {{ $ssSelectedHour === 3 ? 'checked' : '' }}>
                <span>3H</span>
              </label>

              <label class="ss-pill">
                <input type="radio"
                       name="hour"
                       value="6"
                       {{ $ssSelectedHour === 6 ? 'checked' : '' }}>
                <span>6H</span>
              </label>

              <label class="ss-pill">
                <input type="radio"
                       name="hour"
                       value="24"
                       {{ $ssSelectedHour === 24 ? 'checked' : '' }}>
                <span>Day</span>
              </label>
            </div>
          </div>
        </div>

        <button type="submit" class="ss-search-btn">
          <i class="fas fa-search"></i>
          <span>Search</span>
        </button>
      </form>

      <div class="topbar-right">
        <a href="{{ route('index') }}" class="t-link">Home</a>
        <a href="{{ route('vendor.signup') }}" class="t-link list-hotel-top-link">List Hotel</a>
        <a href="{{ route('frontend.rooms') }}" class="t-link results-top-link">← Results</a>

        @auth('web')
          <a href="{{ route('user.dashboard') }}">
            <button class="t-btn">
              <i class="fas fa-user-circle" style="margin-right:5px"></i>
              My Account
            </button>
          </a>
        @else
          <a href="{{ route('user.login') }}">
            <button class="t-btn">Login / Sign up</button>
          </a>
        @endauth
      </div>
    </div>
  </header>

  <div class="site-menu-backdrop"
       id="siteMenuBackdrop"
       onclick="closeSiteMenu()"></div>

  <aside class="site-mobile-menu"
         id="siteMobileMenu"
         aria-hidden="true">

    <div class="smm-head">
      <a href="{{ route('index') }}" class="smm-brand">
        <img src="{{ asset('stayzio/images/stayzio-logo.png') }}"
             alt="StayZio Logo"
             class="stayzio-brand-logo">
      </a>
      <button type="button" onclick="closeSiteMenu()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <a href="{{ route('index') }}"><i class="fas fa-home"></i> Home</a>
    <a href="{{ route('frontend.rooms') }}"><i class="fas fa-hotel"></i> Hotels</a>
    <a href="{{ route('vendor.signup') }}"><i class="fas fa-building"></i> List Hotel</a>

    @auth('web')
      <a href="{{ route('user.dashboard') }}">
        <i class="fas fa-user"></i> {{ Auth::guard('web')->user()->name }}
      </a>
    @else
      <a href="{{ route('user.login') }}"><i class="fas fa-user"></i> My Profile</a>
    @endauth

    <a href="#"><i class="fas fa-gift"></i> Offers</a>
    <a href="#"><i class="fas fa-headset"></i> Help & Support</a>

    @guest('web')
      <a href="{{ route('user.login') }}" class="smm-login">
        <i class="fas fa-sign-in-alt"></i> Login / Sign up
      </a>
    @endguest
  </aside>

  @yield('content')

  @include('frontend.partials.footer')

  @includeIf('frontend.partials.popups')

  @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
    @include('cookie-consent::index')
  @endif

  @include('frontend.partials.scripts')
</body>
</html>
