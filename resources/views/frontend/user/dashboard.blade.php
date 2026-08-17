@extends('frontend.layout')

@section('pageHeading')
{{ __('My Profile') }}
@endsection

@section('styles')

<link rel="stylesheet" href="{{ asset('stayzio/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('stayzio/css/user_style.css') }}">
<link rel="stylesheet" href="{{ asset('stayzio/css/login_style.css') }}">
<link rel="stylesheet" href="{{ asset('stayzio/css/hotel_list.css') }}">
<link rel="stylesheet" href="{{ asset('stayzio/css/hotel_detials.css') }}">

@endsection

@section('content')


<form action="{{ route('user.update_profile') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<!-- ══ HERO BANNER ══ -->

<section class="hero-banner">

<div class="hero-inner">

    <div class="hero-left">

        <!-- Avatar -->
        <div class="avatar-wrap">

            @if(Auth::guard('web')->user()->image)

                <img src="{{ asset('assets/img/users/' . Auth::guard('web')->user()->image) }}"
                     alt="User">

            @else

                <img src="{{ asset('assets/img/blank-user.jpg') }}"
                     alt="User">

            @endif

            <label class="avatar-edit-btn"
                   for="imageUpload">

                <i class="fas fa-camera"></i>

            </label>

            <input type="file"
                   id="imageUpload"
                   name="image"
                   hidden>

        </div>

        <!-- Name + Referral -->
        <div class="hero-info">

            <h1>
                {{ Auth::guard('web')->user()->name }}
            </h1>

            <div class="referral-pill">

              <!--  <span class="ref-label">                    Referral Code :
                </span>
                <span class="ref-code">
                    {{ Auth::guard('web')->user()->username }}
                </span>
                <button class="ref-copy-btn"
                        type="button">

                    <i class="fas fa-copy"></i>

                </button> -->

            </div>

        </div>

    </div>

    <!-- Brevi Cash -->
    <div class="brevi-cash-pill">

       <!-- <div class="cash-icon">₹</div>

        <span>Brevi Cash :</span>

        <strong>0</strong>

        <i class="fas fa-chevron-right cash-arrow"></i> -->

    </div>

</div>

</section>

<!-- ══ PROFILE CARD ══ -->

<div class="profile-card-wrap">

<div class="profile-card">

    <div class="pc-header">
        <div class="pc-title">My Profile</div>
    </div>

    <div class="pc-layout">

        <!-- Menu Button -->
        <button class="menu-btn" id="menuBtn">
            ☰ Menu
        </button>

        <div class="sidebar-overlay"></div>

        <aside class="pc-sidebar" id="sidebar">

            <div class="sidebar-remove">

                <button class="close-btn"
                        id="closeSidebar">

                    ✕

                </button>

            </div>

            <div class="pc-nav-item active" onclick="showSection('profile',this)">
                <i class="fas fa-user"></i> My Profile
            </div>

             <div class="pc-nav-item" onclick="showSection('bookings',this)">
                    <i class="fas fa-calendar-check"></i> My Bookings
                    
                </div>
                <div class="pc-nav-item" onclick="showSection('wishlist',this)">
                    <i class="far fa-heart"></i> Wishlist
                </div>
              <!--  <div class="pc-nav-item" onclick="showSection('wallet',this)">                    <i class="fas fa-wallet"></i> Wallet
                </div>
                <div class="pc-nav-item" onclick="showSection('referral',this)">
                    <i class="fas fa-gift"></i> Refer &amp; Earn
                </div> -->
                <div class="pc-nav-item" onclick="showSection('help',this)">
                    <i class="fas fa-headset"></i> Help &amp; Support
                </div>
                <div class="pc-nav-item" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </div>
       
        </aside>

        <!-- Main Content -->
        <main class="pc-content">

            @if(Session::has('success'))

                <div class="alert alert-success mb-4">
                    {{ Session::get('success') }}
                </div>

            @endif

            <!-- ─ PROFILE SECTION ─ -->
            <div id="sec-profile">

                <!-- Personal Information -->
                <div class="pf-section">

                    <div class="pf-section-title">
                        Personal Information
                    </div>

                    <div class="pf-grid">

                        <!-- NAME -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Contact Person Name
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input filled"
                                       type="text"
                                       value="{{ old('name', Auth::guard('web')->user()->name) }}"
                                       name="name"
                                       id="fname">

                              

                            </div>

                        </div>

                        <!-- USERNAME -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Username
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input filled"
                                       type="text"
                                       value="{{ old('username', Auth::guard('web')->user()->username) }}"
                                       name="username"
                                       id="lname">

                               
                            </div>

                        </div>

                        <!-- DOB -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Date of Birth
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input"
                                       type="text"
                                       placeholder="DD/MM/YYYY"
                                       id="dob"
                                       name="dob"
                                       value="{{ old('dob', Auth::guard('web')->user()->dob) }}">

                              

                            </div>

                        </div>

                        <!-- GENDER -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Gender
                            </label>

                            <div class="pf-input-wrap">

                                <select class="pf-input"
                                        id="gender"
                                        name="gender">

                                    <option value="">
                                        Pick Gender
                                    </option>

                                    <option value="Male"
                                        {{ Auth::guard('web')->user()->gender == 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="Female"
                                        {{ Auth::guard('web')->user()->gender == 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                </select>

                              

                            </div>

                        </div>

                        <!-- RELATIONSHIP -->
                       <!-- <div class="pf-field">

                            <label class="pf-label">
                                Relationship Status
                            </label>

                            <div class="pf-input-wrap">

                                <select class="pf-input"
                                        id="rel-status"
                                        name="relationship_status">

                                    <option value="">
                                        Pick Status
                                    </option>

                                    <option value="Single">
                                        Single
                                    </option>

                                    <option value="Married">
                                        Married
                                    </option>

                                </select>

                                <span class="add-earn-pill">
                                    ADD & EARN
                                </span>

                            </div>

                        </div> -->

                    </div>

                </div>

                <!-- Login Information -->
                <div class="pf-section">

                    <div class="pf-section-title">
                        Login Information
                    </div>

                    <div class="pf-grid pf-grid-3">

                        <!-- Phone -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Phone
                            </label>

                            <div class="phone-wrap">

                                <div class="phone-country">
                                    🇮🇳 +91
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                                <div class="phone-number-wrap">

                                    <input class="pf-input filled"
                                           type="tel"
                                           value="{{ old('phone', Auth::guard('web')->user()->phone) }}"
                                           name="phone"
                                           style="padding-right:2.5rem;">

                                    <div class="phone-verified-icon">
                                        <i class="fas fa-check"></i>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Password -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Password
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input password filled"
                                       type="password"
                                       value=""
                                       name="password"
                                       id="passField">

                               

                            </div>

                        </div>

                        <!-- Email -->
                        <div class="pf-field">

                            <label class="pf-label">
                                Email
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input filled"
                                       type="email"
                                       value="{{ Auth::guard('web')->user()->email }}"
                                       readonly
                                       name="email"
                                       id="emailField"
                                       style="padding-right:5.5rem;font-size:.78rem;">

                               

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Location Information -->
                <div class="pf-section">

                    <div class="pf-section-title">
                        Location Information
                    </div>

                    <div class="pf-grid pf-grid-2">

                        <!-- City -->
                        <div class="pf-field">

                            <label class="pf-label">
                                City of Residence
                            </label>

                            <div class="pf-input-wrap">

                                <input class="pf-input"
                                       type="text"
                                       placeholder="Pick City"
                                       id="cityField"
                                       name="city"
                                       value="{{ old('city', Auth::guard('web')->user()->city) }}">

                                

                            </div>

                        </div>

                        <!-- Nationality -->
                      <!--  <div class="pf-field">

                            <label class="pf-label">
                                Nationality
                            </label>

                            <div class="pf-input-wrap">

                                <select class="pf-input"
                                        id="natField"
                                        name="country">

                                    <option value="">
                                        Pick Nationality
                                    </option>

                                    <option value="Indian">
                                        Indian
                                    </option>

                                </select>

                                <span class="pf-action edit"
                                      style="right:.6rem;">

                                    EDIT

                                </span>

                            </div>

                        </div> -->

                    </div>

                </div>

                <!-- Save Button -->
                <div style="display:flex;justify-content:flex-end;gap:.7rem;margin-top:.5rem;">

                    <button type="submit"
                            style="background:var(--red);color:#fff;border:none;border-radius:var(--r);padding:.65rem 2rem;font-family:'Syne',sans-serif;font-size:.85rem;font-weight:800;cursor:pointer;transition:background .15s;box-shadow:0 4px 14px rgba(217, 40, 40, 0.3);">

                        Save Changes

                    </button>

                </div>

            </div>
            

             <!-- ─ BOOKINGS SECTION (REDESIGNED) ─ -->
     <div id="sec-bookings" style="display:none;">
    <div class="pf-section">
        <div class="pf-section-title">{{ __('My Bookings') }}</div>

        @include('frontend.user.booking.my-bookings-styles')

        @if(count($bookings) > 0)
        <div class="mb-toolbar">
            <div class="mb-count">{{ count($bookings) }} bookings · view, manage and cancel</div>
            <div class="mb-pills">
                <span class="mb-pill active" data-filter="all">All</span>
                <span class="mb-pill" data-filter="upcoming">Upcoming</span>
                <span class="mb-pill" data-filter="past">Past</span>
                <span class="mb-pill" data-filter="cancelled">Cancelled</span>
            </div>
        </div>

        <div class="mb-list" id="mbList">
        @foreach ($bookings as $booking)
            @include('frontend.user.booking.my-bookings-card', ['booking' => $booking])
        @endforeach
        </div>

        <script>
        (function(){
            var pills = document.querySelectorAll('.mb-pill');
            var cards = document.querySelectorAll('.mb-card[data-status]');
            pills.forEach(function(p) {
                p.addEventListener('click', function() {
                    pills.forEach(function(x) { x.classList.remove('active'); });
                    p.classList.add('active');
                    var f = p.getAttribute('data-filter');
                    cards.forEach(function(c) {
                        var s = c.getAttribute('data-status');
                        c.style.display = (f === 'all' || s === f) ? '' : 'none';
                    });
                });
            });
        })();
        </script>
        @else
        <div class="mb-empty">
            <i class="far fa-calendar-times" style="font-size:42px;color:#cbd5e1;margin-bottom:14px"></i>
            <p style="font-size:15px;font-weight:700;color:#374151;margin:0 0 4px">No bookings yet</p>
            <p style="font-size:12px;color:#9ca3af;margin:0 0 16px">Browse hotels and book your first hourly stay</p>
            <a href="{{ url('/') }}" style="display:inline-block;padding:10px 22px;background:#e31e24;color:#fff;border-radius:10px;font-size:13px;font-weight:800;text-decoration:none">
                <i class="fas fa-search"></i> Browse Hotels
            </a>
        </div>
        @endif
    </div>
</div>
                <!-- ─ WISHLIST ─ -->
                <div id="sec-wishlist" style="display:none;">
                    <div class="pf-section">
                        <div class="pf-section-title">Wishlist</div>
                        @if (count($wishlists) == 0)
                        <h4 class="text-center mt-4" style="color: var(--ink-light, #6b7280);">{{ __('NO ROOM WISHLIST ITEM FOUND') . '!' }}</h4>
                    @else
                        <div style="display:flex;flex-direction:column;gap:.8rem;">
                            @foreach ($wishlists as $item)
                                <div style="border:1.5px solid var(--border);border-radius:var(--r-lg);padding:1.2rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                                    
                                    <img src="{{ !empty($item->image) ? asset('assets/img/rooms/' . $item->image) : (!empty($item->room->image) ? asset('assets/img/rooms/' . $item->room->image) : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=120&q=80') }}"
                                         style="width:80px;height:60px;border-radius:8px;object-fit:cover;flex-shrink:0;" 
                                         alt="{{ __('Room Image') }}">
                                    
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-family:'Syne',sans-serif;font-size:.92rem;font-weight:800;color:var(--ink);margin-bottom:.2rem;">
                                            {{ $item->title ?? ($item->room->title ?? __('Unnamed Room')) }}
                                        </div>
                                        
                                        @if(!empty($item->location) || !empty($item->room->location))
                                            <div style="font-size:.75rem;color:var(--ink-light);margin-bottom:.4rem;">
                                                <i class="fas fa-map-marker-alt" style="color:var(--purple);font-size:.65rem;margin-right:.2rem;"></i>
                                                {{ $item->location ?? $item->room->location }}
                                            </div>
                                        @endif
                                        
                                        <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                                            <span style="background:#f3f4f6;border-radius:5px;padding:2px 8px;font-size:.68rem;font-weight:600;color:var(--ink-mid);">
                                                {{ __('Saved Item') }} #{{ $loop->iteration }}
                                            </span>
                                            <span style="background:#e0e7ff;border-radius:5px;padding:2px 8px;font-size:.68rem;font-weight:600;color:var(--purple);">
                                                {{ __('Active Wishlist') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:.4rem;">
                                        @if(!empty($item->cost) || !empty($item->room->cost))
                                            <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:900;color:var(--purple);">
                                                {{ symbolPrice($item->cost ?? $item->room->cost) }}
                                            </div>
                                        @endif
                                        
                                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                            <a href="{{ route('frontend.room.details', ['slug' => $item->slug, 'id' => $item->room_id]) }}" 
                                               target="_blank"
                                               style="border:1.5px solid var(--purple);background:var(--purple-light);color:var(--purple);border-radius:8px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;cursor:pointer;display:inline-block;">
                                                {{ __('View') }}
                                            </a>
                                            
                                            <a href="{{ route('remove.wishlist.room', $item->room_id) }}" 
                                               style="border:1.5px solid var(--border);background:#fee2e2;color:var(--red, #dc2626);border-radius:8px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;cursor:pointer;display:inline-block;">
                                                {{ __('Remove') }}
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                    </div>
                </div>

                <!-- ─ WALLET ─ -->
                <div id="sec-wallet" style="display:none;">
                    <div class="pf-section">
                        <div class="pf-section-title">Wallet &amp; Brevi Cash</div>
                        <div
                            style="background:linear-gradient(135deg,var(--purple),var(--purple-mid));border-radius:var(--r-lg);padding:1.5rem;color:#fff;margin-bottom:1rem;">
                            <div
                                style="font-size:.72rem;opacity:.65;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem;">
                                Available Balance</div>
                            <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:900;">₹0</div>
                            <div style="font-size:.75rem;opacity:.6;margin-top:.3rem;">Earn cash by referring
                                friends or completing your profile</div>
                        </div>
                        <div class="pf-bookings-empty">
                            <i class="fas fa-history"></i>
                            <p>No transactions yet.</p>
                        </div>
                    </div>
                </div>

                <!-- ─ REFERRAL ─ -->
                <div id="sec-referral" style="display:none;">
                    <div class="pf-section">
                        <div class="pf-section-title">Refer &amp; Earn</div>
                        <div
                            style="background:linear-gradient(110deg,#fff7ed,#fde8d0);border:1.5px solid #f9c89b;border-radius:var(--r-lg);padding:1.5rem;text-align:center;">
                            <div style="font-size:2rem;margin-bottom:.6rem;">🎁</div>
                            <div
                                style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:#92400e;margin-bottom:.3rem;">
                                Invite friends, earn ₹100 each!</div>
                            <div style="font-size:.8rem;color:#b45309;margin-bottom:1.2rem;">Share your referral
                                code and earn Brevi Cash when they book.</div>
                            <div
                                style="display:flex;align-items:center;gap:.6rem;background:#fff;border:1.5px dashed #f59e0b;border-radius:var(--r);padding:.6rem 1rem;max-width:320px;margin:0 auto 1rem;">
                                <span
                                    style="flex:1;font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:var(--ink);letter-spacing:.06em;">ASHW24F2B7</span>
                                <button onclick="copyReferral()"
                                    style="background:var(--amber);color:#fff;border:none;border-radius:8px;padding:.4rem .9rem;font-size:.75rem;font-weight:700;cursor:pointer;">Copy</button>
                            </div>
                            <button
                                style="background:var(--ink);color:#fff;border:none;border-radius:var(--r);padding:.65rem 1.5rem;font-size:.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                                <i class="fas fa-share-alt"></i> Share Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ─ HELP ─ -->
                <div id="sec-help" style="display:none;">
                    <div class="pf-section">
                        <div class="pf-section-title">Help &amp; Support</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
                            <div style="border:1.5px solid var(--border);border-radius:var(--r-lg);padding:1.2rem;cursor:pointer;transition:border-color .15s;"
                                onmouseover="this.style.borderColor='var(--purple)'"
                                onmouseout="this.style.borderColor='var(--border)'">
                                <i class="fas fa-phone"
                                    style="color:var(--purple);font-size:1.2rem;margin-bottom:.6rem;display:block;"></i>
                                <div style="font-weight:800;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">
                                    Call Us</div>
                                <div style="font-size:.76rem;color:var(--ink-faint);">+91 99444 67727</div>
                            </div>
                            <div style="border:1.5px solid var(--border);border-radius:var(--r-lg);padding:1.2rem;cursor:pointer;transition:border-color .15s;"
                                onmouseover="this.style.borderColor='#25d366'"
                                onmouseout="this.style.borderColor='var(--border)'">
                                <i class="fab fa-whatsapp"
                                    style="color:#25d366;font-size:1.2rem;margin-bottom:.6rem;display:block;"></i>
                                <div style="font-weight:800;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">
                                    WhatsApp</div>
                                <div style="font-size:.76rem;color:var(--ink-faint);">Chat with us</div>
                            </div>
                            <div style="border:1.5px solid var(--border);border-radius:var(--r-lg);padding:1.2rem;cursor:pointer;transition:border-color .15s;"
                                onmouseover="this.style.borderColor='var(--purple)'"
                                onmouseout="this.style.borderColor='var(--border)'">
                                <i class="fas fa-envelope"
                                    style="color:var(--purple);font-size:1.2rem;margin-bottom:.6rem;display:block;"></i>
                                <div style="font-weight:800;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">
                                    Email</div>
                                <div style="font-size:.76rem;color:var(--ink-faint);">support@hotelbooking.com</div>
                            </div>
                            <div style="border:1.5px solid var(--border);border-radius:var(--r-lg);padding:1.2rem;cursor:pointer;transition:border-color .15s;"
                                onmouseover="this.style.borderColor='var(--purple)'"
                                onmouseout="this.style.borderColor='var(--border)'">
                                <i class="fas fa-question-circle"
                                    style="color:var(--purple);font-size:1.2rem;margin-bottom:.6rem;display:block;"></i>
                                <div style="font-weight:800;font-size:.88rem;color:var(--ink);margin-bottom:.2rem;">
                                    FAQs</div>
                                <div style="font-size:.76rem;color:var(--ink-faint);">Browse common questions</div>
                            </div>
                        </div>
                    </div>
                </div>


        </main>

    </div>

</div>

</div>

</form>

<!-- Hidden form for booking cancellation (outside main profile form to avoid nested forms) -->
<form id="bookingCancelForm" action="{{ route('frontend.room_booking.cancel_booking') }}" method="POST" style="display:none">
    @csrf
    <input type="hidden" name="booking_id" id="cancelBookingId" value="">
</form>
<script>
function cancelBookingViaJS(bookingId, orderNumber) {
    if (!confirm('Cancel booking #' + orderNumber + '?\n\nIf this booking is eligible for refund (24h+ before check-in OR within 15 min of booking), you will receive a full refund. Continue?')) {
        return false;
    }
    document.getElementById('cancelBookingId').value = bookingId;
    document.getElementById('bookingCancelForm').submit();
}
</script>

@endsection

@section('scripts')
@endsection
