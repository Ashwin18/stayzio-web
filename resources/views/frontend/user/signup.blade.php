@extends('frontend.layout')

@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->signup_page_title ? $pageHeading->signup_page_title : __('Signup') }}
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

@include('frontend.user.signup-styles')

@section('content')
@include('frontend.user.signup-styles')
<main class="sz-auth-page">
  <div class="sz-auth-wrap">
    
    <section class="sz-auth-left">
      <div class="sz-auth-badge"><i class="fas fa-bolt"></i> Flexible hourly hotel booking</div>
      <h1 class="sz-auth-title">Create your <span>StayZio</span> account</h1>
      <p class="sz-auth-desc">Sign up with your mobile number to book hourly stays, save favourites, manage bookings and unlock exclusive StayZio offers.</p>
      
      <div class="sz-auth-benefits">
        <div class="sz-benefit">
          <i class="fas fa-clock"></i>
          <div><b>Pay by the hour</b><span>3 hrs, 6 hrs and full day stays.</span></div>
        </div>
        <div class="sz-benefit">
          <i class="fas fa-shield-heart"></i>
          <div><b>Safe stays</b><span>Verified hotels and secure bookings.</span></div>
        </div>
        <div class="sz-benefit">
          <i class="fas fa-tags"></i>
          <div><b>Best offers</b><span>Coupons, wallet benefits and app deals.</span></div>
        </div>
      </div>
      
      <div class="sz-auth-visual">
        <div>
          <h3>Download StayZio App</h3>
          <p>Get exclusive short-stay deals, faster booking and instant confirmation.</p>
        </div>
        <div class="sz-store-buttons">
          <a href="#"><i class="fab fa-google-play"></i> Google Play</a>
          <a href="#"><i class="fab fa-apple"></i> App Store</a>
        </div>
      </div>
    </section>
    
    <section class="sz-login-card">
      <div class="sz-login-logo">
        <img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio">
        <strong>Stay<span>Zio</span></strong>
      </div>
      <h2>Create Account</h2>
      <p class="sz-sub">Register your account to continue</p>
      
      @if (Session::has('success'))
        <div class="alert alert-success mb-3" style="background:#d1e7dd; color:#0f5132; padding:12px; border-radius:12px; font-size:14px; text-align:center; border:1px solid #badbcc;">
          {{ __(Session::get('success')) }}
        </div>
      @endif

      <form action="{{ route('user.signup_submit') }}" method="POST">
        @csrf

        <div class="sz-form-panel active">
          
          <div class="sz-field">
            <label class="sz-label">Contact Person Name</label>
            <input class="sz-input" type="text" name="name" id="sz_name" value="{{ old('name') }}" placeholder="Enter contact person name" required autocomplete="name" oninput="this.value=this.value.replace(/[^a-zA-Z\s.]/g,'')">
            @error('name')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div>
          
          <div class="sz-field">
            <label class="sz-label">Mobile Number</label>
            <div class="sz-phone-field">
              <div class="sz-country">🇮🇳 +91</div>
              <input type="tel" name="phone" id="sz_phone" value="{{ old('phone') }}" maxlength="10" placeholder="Enter mobile number" required pattern="[0-9]{10}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            </div>
            @error('phone')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div>

          <div class="sz-field">
            <label class="sz-label">Email Address</label>
            <input class="sz-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
            @error('email')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div>
          
          
           <input class="sz-input" type="hidden" name="username" value="user" placeholder="Enter username" required>
          

          <!--<div class="sz-field">            <label class="sz-label">Username</label>
            <input class="sz-input" type="text" name="username" value="{{ old('username') }}" placeholder="Enter username" required>
            @error('username')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div> -->

          <div class="sz-field">
            <label class="sz-label">Password</label>
            <div style="position:relative">
              <input class="sz-input" type="password" name="password" id="sz_pass" placeholder="Enter password (min 6 chars)" required style="padding-right:46px">
              <button type="button" onclick="szTogglePass('sz_pass','sz_eye1')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9aa0ae;padding:0;font-size:17px"><i class="fas fa-eye" id="sz_eye1"></i></button>
            </div>
            @error('password')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div>

          <div class="sz-field">
            <label class="sz-label">Confirm Password</label>
            <div style="position:relative">
              <input class="sz-input" type="password" name="password_confirmation" id="sz_pass2" placeholder="Re-enter password" required style="padding-right:46px">
              <button type="button" onclick="szTogglePass('sz_pass2','sz_eye2')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9aa0ae;padding:0;font-size:17px"><i class="fas fa-eye" id="sz_eye2"></i></button>
            </div>
            @error('password_confirmation')
              <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
            @enderror
          </div>

          @if ($recaptchaInfo->google_recaptcha_status == 1)
            <div class="mb-3 mt-3">
              {!! NoCaptcha::renderJs() !!}
              {!! NoCaptcha::display() !!}
              @error('g-recaptcha-response')
                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
              @enderror
            </div>
          @endif

          <button class="sz-primary-btn" type="submit">Create Account</button>
          
        </div>
      </form>
<script>
function szTogglePass(inputId, iconId) {
  var inp = document.getElementById(inputId);
  var ico = document.getElementById(iconId);
  if (inp.type === 'password') {
    inp.type = 'text';
    ico.classList.remove('fa-eye');
    ico.classList.add('fa-eye-slash');
  } else {
    inp.type = 'password';
    ico.classList.remove('fa-eye-slash');
    ico.classList.add('fa-eye');
  }
}
</script>

      <div class="sz-divider">or</div>
      
      <a href="{{ route('user.login.google') }}" class="sz-google-btn">
        <i class="fab fa-google" style="color:#e31e24"></i> Continue with Google
      </a>
      
      <p class="sz-terms">By continuing, you agree to our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.</p>
      <p class="sz-signup">Already have an account? <a href="{{ route('user.login') }}">Login now</a></p>
    </section>
    
  </div>
</main>
@endsection

@section('scripts')
{{-- Layout level scripts inject zone --}}
@endsection