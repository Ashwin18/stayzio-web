@extends('frontend.layout')

@section('pageHeading')
{{ !empty($pageHeading) ? $pageHeading->login_page_title : __('Login') }}
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_login }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_login }}
@endif
@endsection

@section('styles')

@endsection

@section('content')

<style>
/* StayZio premium login page - scoped */
.sz-auth-page { background: linear-gradient(180deg, #fff7f3 0%, #ffffff 45%, #fbfaf8 100%); min-height: calc(100vh - 80px); overflow: hidden; }
.sz-auth-wrap { max-width: 1180px; margin: 0 auto; padding: 58px 22px 70px; display: grid; grid-template-columns: 1.05fr .95fr; gap: 42px; align-items: center; position: relative; }
.sz-auth-wrap:before { content: ""; position: absolute; right: -180px; top: 20px; width: 430px; height: 430px; background: radial-gradient(circle, rgba(227, 30, 36, .14), rgba(227, 30, 36, 0) 68%); pointer-events: none; }
.sz-auth-left { position: relative; z-index: 1; }
.sz-auth-badge { display: inline-flex; align-items: center; gap: 9px; background: #fff; border: 1px solid rgba(227, 30, 36, .14); box-shadow: 0 12px 34px rgba(227, 30, 36, .08); border-radius: 999px; padding: 8px 14px; font-weight: 800; color: #e31e24; font-size: 13px; margin-bottom: 18px; }
.sz-auth-title { font-size: 52px; line-height: 1.02; font-weight: 900; color: #161826; letter-spacing: -1.8px; margin: 0 0 14px; }
.sz-auth-title span { color: #e31e24; }
.sz-auth-desc { font-size: 18px; line-height: 1.65; color: #656b7a; max-width: 560px; margin: 0 0 26px; }
.sz-auth-benefits { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin: 30px 0 0; }
.sz-benefit { background: #fff; border: 1px solid #f0dfd9; border-radius: 20px; padding: 20px 16px; box-shadow: 0 20px 42px rgba(18, 24, 40, .06); min-height: 122px; }
.sz-benefit i { width: 38px; height: 38px; border-radius: 14px; background: #fff1f0; color: #e31e24; display: flex; align-items: center; justify-content: center; font-size: 17px; margin-bottom: 12px; }
.sz-benefit b { display: block; font-size: 16px; color: #161826; margin-bottom: 5px; }
.sz-benefit span { font-size: 13px; color: #6b7280; line-height: 1.4; }
.sz-auth-visual { margin-top: 30px; background: #12131b; color: #fff; border-radius: 26px; padding: 24px 26px; display: flex; align-items: center; justify-content: space-between; gap: 18px; box-shadow: 0 24px 70px rgba(18, 19, 27, .18); position: relative; overflow: hidden; }
.sz-auth-visual:after { content: ""; position: absolute; right: -72px; top: -70px; width: 210px; height: 210px; background: rgba(227, 30, 36, .34); border-radius: 50%; }
.sz-auth-visual h3 { font-size: 26px; font-weight: 900; margin: 0 0 5px; position: relative; z-index: 1; }
.sz-auth-visual p { margin: 0; color: #d7d9e2; font-size: 14px; position: relative; z-index: 1; }
.sz-store-buttons { display: flex; gap: 10px; position: relative; z-index: 1; flex-wrap: wrap; justify-content: flex-end; }
.sz-store-buttons a { display: inline-flex; align-items: center; gap: 8px; padding: 11px 14px; border-radius: 14px; border: 1px solid rgba(255, 255, 255, .24); color: #fff; font-weight: 800; font-size: 13px; background: rgba(255, 255, 255, .08); text-decoration: none; white-space: nowrap; }
.sz-login-card { background: rgba(255, 255, 255, .94); backdrop-filter: blur(14px); border: 1px solid rgba(227, 30, 36, .12); border-radius: 32px; box-shadow: 0 34px 80px rgba(18, 24, 40, .12); padding: 34px; position: relative; z-index: 1; overflow: hidden; }
.sz-login-card:before { content: ""; position: absolute; right: -58px; top: -58px; width: 145px; height: 145px; background: #e31e24; border-radius: 42px; transform: rotate(16deg); opacity: .12; }
.sz-login-logo { display: flex; align-items: center; gap: 12px; justify-content: center; margin-bottom: 16px; }
.sz-login-logo img { width: 66px; height: 66px; object-fit: contain; background: #fff; border-radius: 18px; padding: 5px; box-shadow: 0 12px 30px rgba(227, 30, 36, .12); }
.sz-login-logo strong { font-family: Georgia, serif; font-size: 28px; color: #111; line-height: 1; }
.sz-login-logo strong span { color: #e31e24; }
.sz-login-card h2 { text-align: center; font-size: 28px; font-weight: 900; color: #111827; margin: 0 0 6px; }
.sz-login-card .sz-sub { text-align: center; color: #7a8394; font-size: 14px; margin: 0 0 26px; }
.sz-auth-tabs { display: grid; grid-template-columns: 1fr 1fr; background: #fff4f2; border: 1px solid #ffe0dc; border-radius: 16px; padding: 5px; margin-bottom: 22px; }
.sz-auth-tabs button { border: 0; background: transparent; border-radius: 12px; padding: 12px 10px; font-weight: 900; color: #797f8d; cursor: pointer; }
.sz-auth-tabs button.active { background: #e31e24; color: #fff; box-shadow: 0 10px 20px rgba(227, 30, 36, .24); }
.sz-field { margin-bottom: 15px; }
.sz-label { display: block; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: .06em; color: #8b95a7; margin-bottom: 8px; }
.sz-phone-field { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 16px; background: #fff; overflow: hidden; transition: .2s; }
.sz-phone-field:focus-within { border-color: #e31e24; box-shadow: 0 0 0 4px rgba(227, 30, 36, .08); }
.sz-country { padding: 0 14px; height: 54px; display: flex; align-items: center; gap: 7px; border-right: 1px solid #e5e7eb; font-weight: 900; color: #111827; white-space: nowrap; }
.sz-phone-field input { border: 0; outline: 0; height: 54px; flex: 1; padding: 0 14px; font-weight: 700; color: #111827; min-width: 0; }
.sz-input { width: 100%; height: 54px; border: 1.5px solid #e5e7eb; border-radius: 16px; background: #fff; padding: 0 15px; outline: 0; font-weight: 700; }
.sz-input:focus { border-color: #e31e24; box-shadow: 0 0 0 4px rgba(227, 30, 36, .08); }
.sz-primary-btn { width: 100%; height: 58px; border: 0; border-radius: 17px; background: linear-gradient(135deg, #ff3d35, #e31e24); color: #fff; font-weight: 900; font-size: 16px; box-shadow: 0 16px 34px rgba(227, 30, 36, .28); cursor: pointer; transition: .18s; }
.sz-primary-btn:hover { transform: translateY(-1px); box-shadow: 0 20px 42px rgba(227, 30, 36, .34); }
.sz-otp-area { display: none; margin-top: 20px; padding-top: 18px; border-top: 1px dashed #e5e7eb; }
.sz-otp-area.show { display: block; }
.sz-otp-title { font-size: 13px; font-weight: 900; color: #111827; margin-bottom: 12px; }
.sz-otp-row { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-bottom: 15px; }
.sz-otp-row input { width: 100%; height: 50px; text-align: center; border: 1.5px solid #e5e7eb; border-radius: 14px; font-size: 20px; font-weight: 900; outline: 0; background: #fff; }
.sz-otp-row input:focus { border-color: #e31e24; box-shadow: 0 0 0 4px rgba(227, 30, 36, .08); }
.sz-resend { text-align: center; font-size: 13px; color: #7a8394; margin: 12px 0 0; }
.sz-resend a { color: #e31e24; font-weight: 900; text-decoration: none; }
.sz-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; color: #a0a7b5; font-size: 13px; }
.sz-divider:before, .sz-divider:after { content: ""; height: 1px; background: #e5e7eb; flex: 1; }
.sz-google-btn { width: 100%; height: 52px; border: 1.5px solid #e5e7eb; background: #fff; border-radius: 15px; font-weight: 900; color: #222; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; transition: .2s; }
.sz-google-btn:hover { background: #f9f9f9; }
.sz-terms { text-align: center; color: #7a8394; font-size: 12px; line-height: 1.55; margin-top: 16px; }
.sz-terms a { color: #e31e24; font-weight: 900; text-decoration: none; }
.sz-signup { text-align: center; margin-top: 18px; color: #7a8394; font-size: 14px; }
.sz-signup a { color: #e31e24; font-weight: 900; text-decoration: none; }
.sz-form-panel { display: none; }
.sz-form-panel.active { display: block; }
.error-text { color: #dc3545; font-size: 12px; margin-top: 6px; font-weight: 600; display: block; }

@media(max-width:991px) {
    .sz-auth-wrap { grid-template-columns: 1fr; padding-top: 34px; }
    .sz-auth-title { font-size: 40px }
    .sz-auth-left { text-align: center }
    .sz-auth-desc { margin-left: auto; margin-right: auto }
    .sz-auth-benefits { max-width: 720px; margin-left: auto; margin-right: auto }
    .sz-auth-visual { max-width: 720px; margin-left: auto; margin-right: auto }
    .sz-login-card { max-width: 520px; margin: 0 auto; width: 100%; }
}
@media(max-width:640px) {
    .sz-auth-wrap { padding: 24px 14px 44px; gap: 24px }
    .sz-auth-title { font-size: 32px }
    .sz-auth-desc { font-size: 15px }
    .sz-auth-benefits { grid-template-columns: 1fr; gap: 10px }
    .sz-benefit { min-height: auto; padding: 15px; display: flex; align-items: center; gap: 12px; text-align: left }
    .sz-benefit i { margin: 0; flex: 0 0 38px }
    .sz-auth-visual { padding: 20px; border-radius: 22px; display: block; text-align: left }
    .sz-auth-visual h3 { font-size: 22px }
    .sz-store-buttons { justify-content: flex-start; margin-top: 14px }
    .sz-login-card { padding: 24px 16px; border-radius: 26px }
    .sz-login-logo img { width: 56px; height: 56px }
    .sz-login-logo strong { font-size: 24px }
    .sz-login-card h2 { font-size: 24px }
    .sz-otp-row { gap: 7px }
    .sz-otp-row input { height: 46px; border-radius: 12px; font-size: 18px }
    .sz-country { padding: 0 10px }
    .sz-phone-field input { padding: 0 10px }
    .sz-primary-btn { height: 54px }
}
</style>
<main class="sz-auth-page">
  <div class="sz-auth-wrap">
    
    <section class="sz-auth-left">
      <div class="sz-auth-badge">
          <i class="fa-solid fa-bolt"></i> Flexible hourly hotel booking
      </div>
      
      <h1 class="sz-auth-title">Welcome back to <span>StayZio</span></h1>
      
      <p class="sz-auth-desc">
          Login with your account and continue booking verified hourly hotels, couple-friendly stays, and short-stay rooms at the best price.
      </p>
      
      <div class="sz-auth-benefits">
        <div class="sz-benefit">
            <i class="fa-solid fa-clock"></i>
            <div><b>Pay by the hour</b><span>3 hrs, 6 hrs, full day</span></div>
        </div>
        <div class="sz-benefit">
            <i class="fa-solid fa-shield-heart"></i>
            <div><b>Safe stays</b><span>Verified hotels only</span></div>
        </div>
        <div class="sz-benefit">
            <i class="fa-solid fa-tags"></i>
            <div><b>Best offers</b><span>Coupons and app deals</span></div>
        </div>
      </div>
      
      <div class="sz-auth-visual">
        <div>
            <h3>Download StayZio App</h3>
            <p>Get exclusive short-stay deals, faster booking and instant confirmation.</p>
        </div>
        <div class="sz-store-buttons">
            <a href="#"><i class="fa-brands fa-google-play"></i> Google Play</a>
            <a href="#"><i class="fa-brands fa-apple"></i> App Store</a>
        </div>
      </div>
    </section>

    <section class="sz-login-card">
      <div class="sz-login-logo">
          <img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio">
          <strong>Stay<span>Zio</span></strong>
      </div>
      
      <h2>Welcome Back!</h2>
      <p class="sz-sub">Login to continue</p>

      @if (Session::has('success'))
          <div class="alert alert-success mb-3" style="background:#d1e7dd; color:#0f5132; padding:10px; border-radius:10px; font-size:14px; text-align:center; border:1px solid #badbcc;">
              {{ __(Session::get('success')) }}
          </div>
      @endif

      @if (Session::has('error'))
          <div class="alert alert-danger mb-3" style="background:#f8d7da; color:#842029; padding:10px; border-radius:10px; font-size:14px; text-align:center; border:1px solid #f5c2c7;">
              {{ __(Session::get('error')) }}
          </div>
      @endif

      <div class="sz-auth-tabs">
        <button class="active" type="button" onclick="szSwitchAuth('password', this)">Password</button>
        <button type="button" onclick="szSwitchAuth('mobile', this)">Mobile OTP</button>
      </div>

      <div class="sz-form-panel active" id="szPanelPassword">
        <form action="{{ route('user.login_submit') }}" method="POST">
          @csrf

          <div class="sz-field">
            <label class="sz-label">Email Id</label>
            <input class="sz-input" type="email" name="email" value="{{ old('username') }}" placeholder="Enter Email Id" required>
            @error('username') 
                <span class="error-text">{{ $message }}</span> 
            @enderror
          </div>

          <div class="sz-field" style="margin-bottom: 8px;">
            <label class="sz-label">{{ __('Password') }}</label>
            <input class="sz-input" type="password" name="password" placeholder="Enter password" required>
            @error('password') 
                <span class="error-text">{{ $message }}</span> 
            @enderror
          </div>

          <div style="text-align: right; margin-bottom: 20px;">
              <a href="{{ route('user.forget_password') }}" style="color: #e31e24; font-size: 13px; font-weight: 800; text-decoration: none;">Forgot Password?</a>
          </div>

          @if ($bs->google_recaptcha_status == 1)
              <div class="mb-3">
                  {!! NoCaptcha::renderJs() !!}
                  {!! NoCaptcha::display() !!}
                  @error('g-recaptcha-response')
                      <span class="error-text">{{ $message }}</span>
                  @enderror
              </div>
          @endif

          <button class="sz-primary-btn" type="submit">Login</button>
        </form>
      </div>

      <div class="sz-form-panel" id="szPanelMobile">
        <div class="sz-field">
            <label class="sz-label">Mobile Number</label>
            <div class="sz-phone-field">
                <div class="sz-country">+91</div>
                <input id="szMobile" type="tel" maxlength="10" placeholder="Enter mobile number">
            </div>
        </div>
        
        <button class="sz-primary-btn" type="button" onclick="szShowOtp()">Continue</button>
        
        <div class="sz-otp-area" id="szOtpArea">
            <div class="sz-otp-title">Enter OTP</div>
            <div class="sz-otp-row">
                <input maxlength="1"><input maxlength="1"><input maxlength="1">
                <input maxlength="1"><input maxlength="1"><input maxlength="1">
            </div>
            <button class="sz-primary-btn" type="button">Verify & Login</button>
            <p class="sz-resend">Didn’t receive OTP? <a href="#">Resend in 30s</a></p>
        </div>
      </div>

      <div class="sz-divider">or</div>

      @if ($bs->google_login_status == 1)
          <a href="{{ route('user.login.google') }}" class="sz-google-btn mb-2">
              <i class="fa-brands fa-google" style="color:#e31e24"></i> Continue with Google
          </a>
      @endif

      @if ($bs->facebook_login_status == 1)
          <a href="{{ route('user.login.facebook') }}" class="sz-google-btn" style="margin-top: 10px;">
              <i class="fa-brands fa-facebook-f" style="color:#1877F2"></i> Continue with Facebook
          </a>
      @endif

      <p class="sz-signup">Don't have an account? <a href="{{ route('user.signup') }}">Sign up now</a></p>
      
    </section>
  </div>
</main>
@endsection

@section('scripts')
<script>
    // Handles Tab Switching
    function szSwitchAuth(type, btn) {
        document.querySelectorAll('.sz-auth-tabs button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('szPanelMobile').classList.toggle('active', type === 'mobile');
        document.getElementById('szPanelPassword').classList.toggle('active', type === 'password');
    }

    // Handles expanding the OTP section (should be bound to your actual AJAX 'Send OTP' function success eventually)
    function szShowOtp() {
        document.getElementById('szOtpArea').classList.add('show');
        setTimeout(() => document.querySelector('.sz-otp-row input')?.focus(), 80);
    }

    // OTP Auto-Focus Next Field
    document.addEventListener('input', function(e) {
        if (e.target.closest('.sz-otp-row') && e.target.value) {
            let n = e.target.nextElementSibling;
            if (n) n.focus();
        }
    });

    // OTP Auto-Focus Previous Field on Backspace
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && e.target.closest('.sz-otp-row') && !e.target.value) {
            let p = e.target.previousElementSibling;
            if (p) p.focus();
        }
    });
</script>

<script src="{{ asset('stayzio/js/auth_redesign.js') }}"></script>
@endsection