@extends('frontend.layout')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->vendor_login_page_title : __('Login') }}
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

@section('content')
<style>
/* StayZio premium login page - scoped */
.sz-auth-page{background:linear-gradient(180deg,#fff7f3 0%,#ffffff 45%,#fbfaf8 100%);min-height:calc(100vh - 80px);overflow:hidden;}
.sz-auth-wrap{max-width:1180px;margin:0 auto;padding:58px 22px 70px;display:grid;grid-template-columns:1.05fr .95fr;gap:42px;align-items:center;position:relative;}
.sz-auth-wrap:before{content:"";position:absolute;right:-180px;top:20px;width:430px;height:430px;background:radial-gradient(circle,rgba(227,30,36,.14),rgba(227,30,36,0) 68%);pointer-events:none;}
.sz-auth-left{position:relative;z-index:1;}
.sz-auth-badge{display:inline-flex;align-items:center;gap:9px;background:#fff;border:1px solid rgba(227,30,36,.14);box-shadow:0 12px 34px rgba(227,30,36,.08);border-radius:999px;padding:8px 14px;font-weight:800;color:#e31e24;font-size:13px;margin-bottom:18px;}
.sz-auth-title{font-size:52px;line-height:1.02;font-weight:900;color:#161826;letter-spacing:-1.8px;margin:0 0 14px;}
.sz-auth-title span{color:#e31e24;}
.sz-auth-desc{font-size:18px;line-height:1.65;color:#656b7a;max-width:560px;margin:0 0 26px;}
.sz-auth-benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin:30px 0 0;}
.sz-benefit{background:#fff;border:1px solid #f0dfd9;border-radius:20px;padding:20px 16px;box-shadow:0 20px 42px rgba(18,24,40,.06);min-height:122px;}
.sz-benefit i{width:38px;height:38px;border-radius:14px;background:#fff1f0;color:#e31e24;display:flex;align-items:center;justify-content:center;font-size:17px;margin-bottom:12px;}
.sz-benefit b{display:block;font-size:16px;color:#161826;margin-bottom:5px;}
.sz-benefit span{font-size:13px;color:#6b7280;line-height:1.4;}
.sz-auth-visual{margin-top:30px;background:#12131b;color:#fff;border-radius:26px;padding:24px 26px;display:flex;align-items:center;justify-content:space-between;gap:18px;box-shadow:0 24px 70px rgba(18,19,27,.18);position:relative;overflow:hidden;}
.sz-auth-visual:after{content:"";position:absolute;right:-72px;top:-70px;width:210px;height:210px;background:rgba(227,30,36,.34);border-radius:50%;}
.sz-auth-visual h3{font-size:26px;font-weight:900;margin:0 0 5px;position:relative;z-index:1;}
.sz-auth-visual p{margin:0;color:#d7d9e2;font-size:14px;position:relative;z-index:1;}
.sz-store-buttons{display:flex;gap:10px;position:relative;z-index:1;flex-wrap:wrap;justify-content:flex-end;}
.sz-store-buttons a{display:inline-flex;align-items:center;gap:8px;padding:11px 14px;border-radius:14px;border:1px solid rgba(255,255,255,.24);color:#fff;font-weight:800;font-size:13px;background:rgba(255,255,255,.08);text-decoration:none;white-space:nowrap;}
.sz-login-card{background:rgba(255,255,255,.94);backdrop-filter:blur(14px);border:1px solid rgba(227,30,36,.12);border-radius:32px;box-shadow:0 34px 80px rgba(18,24,40,.12);padding:34px;position:relative;z-index:1;overflow:hidden;}
.sz-login-card:before{content:"";position:absolute;right:-58px;top:-58px;width:145px;height:145px;background:#e31e24;border-radius:42px;transform:rotate(16deg);opacity:.12;}
.sz-login-logo{display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:16px;}
.sz-login-logo img{width:66px;height:66px;object-fit:contain;background:#fff;border-radius:18px;padding:5px;box-shadow:0 12px 30px rgba(227,30,36,.12);}
.sz-login-logo strong{font-family:Georgia,serif;font-size:28px;color:#111;line-height:1;}.sz-login-logo strong span{color:#e31e24;}
.sz-login-card h2{text-align:center;font-size:28px;font-weight:900;color:#111827;margin:0 0 6px;}
.sz-login-card .sz-sub{text-align:center;color:#7a8394;font-size:14px;margin:0 0 26px;}
.sz-auth-tabs{display:grid;grid-template-columns:1fr;background:#fff4f2;border:1px solid #ffe0dc;border-radius:16px;padding:5px;margin-bottom:22px;}
.sz-auth-tabs button{border:0;background:transparent;border-radius:12px;padding:12px 10px;font-weight:900;color:#797f8d;cursor:default;}
.sz-auth-tabs button.active{background:#e31e24;color:#fff;box-shadow:0 10px 20px rgba(227,30,36,.24);}
.sz-field{margin-bottom:15px;}.sz-label{display:block;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.06em;color:#8b95a7;margin-bottom:8px;}
.sz-input{width:100%;height:54px;border:1.5px solid #e5e7eb;border-radius:16px;background:#fff;padding:0 15px;outline:0;font-weight:700;}.sz-input:focus{border-color:#e31e24;box-shadow:0 0 0 4px rgba(227,30,36,.08);}
.sz-primary-btn{width:100%;height:58px;border:0;border-radius:17px;background:linear-gradient(135deg,#ff3d35,#e31e24);color:#fff;font-weight:900;font-size:16px;box-shadow:0 16px 34px rgba(227,30,36,.28);cursor:pointer;transition:.18s;}.sz-primary-btn:hover{transform:translateY(-1px);box-shadow:0 20px 42px rgba(227,30,36,.34);}
.sz-divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:#a0a7b5;font-size:13px;}.sz-divider:before,.sz-divider:after{content:"";height:1px;background:#e5e7eb;flex:1;}
.sz-terms{text-align:center;color:#7a8394;font-size:12px;line-height:1.55;margin-top:16px;}.sz-terms a{color:#e31e24;font-weight:900;text-decoration:none;}
.sz-signup{text-align:center;margin-top:18px;color:#7a8394;font-size:14px;}.sz-signup a{color:#e31e24;font-weight:900;text-decoration:none;}
@media(max-width:991px){.sz-auth-wrap{grid-template-columns:1fr;padding-top:34px;}.sz-auth-title{font-size:40px}.sz-auth-left{text-align:center}.sz-auth-desc{margin-left:auto;margin-right:auto}.sz-auth-benefits{max-width:720px;margin-left:auto;margin-right:auto}.sz-auth-visual{max-width:720px;margin-left:auto;margin-right:auto}.sz-login-card{max-width:520px;margin:0 auto;width:100%;}}
@media(max-width:640px){.sz-auth-wrap{padding:24px 14px 44px;gap:24px}.sz-auth-title{font-size:32px}.sz-auth-desc{font-size:15px}.sz-auth-benefits{grid-template-columns:1fr;gap:10px}.sz-benefit{min-height:auto;padding:15px;display:flex;align-items:center;gap:12px;text-align:left}.sz-benefit i{margin:0;flex:0 0 38px}.sz-auth-visual{padding:20px;border-radius:22px;display:block;text-align:left}.sz-auth-visual h3{font-size:22px}.sz-store-buttons{justify-content:flex-start;margin-top:14px}.sz-login-card{padding:24px 16px;border-radius:26px}.sz-login-logo img{width:56px;height:56px}.sz-login-logo strong{font-size:24px}.sz-login-card h2{font-size:24px}.sz-primary-btn{height:54px}.navbar-section{position:relative;z-index:20;}}
</style>

<main class="sz-auth-page">
  <div class="sz-auth-wrap">
    
    <section class="sz-auth-left">
      <div class="sz-auth-badge"><i class="fas fa-bolt"></i> {{ __('Premium Vendor Network') }}</div>
      <h1 class="sz-auth-title">{{ __('Login To An Exciting Experience') }}</h1>
      <p class="sz-auth-desc">{{ __('Join the family of happy Vendors. Manage your premium space, access real-time bookings, and drive your hospitality performance further.') }}</p>
      
      <div class="sz-auth-benefits">
        <div class="sz-benefit">
          <i class="fas fa-chart-line"></i>
          <div>
            <b>{{ __('Grow Revenue') }}</b>
            <span>{{ __('Boost listings visibility instantly.') }}</span>
          </div>
        </div>
        <div class="sz-benefit">
          <i class="fas fa-shield-heart"></i>
          <div>
            <b>{{ __('Safe Business') }}</b>
            <span>{{ __('Verified check-ins & secure payouts.') }}</span>
          </div>
        </div>
        <div class="sz-benefit">
          <i class="fas fa-headset"></i>
          <div>
            <b>{{ __('24/7 Support') }}</b>
            <span>{{ __('Dedicated vendor helpline.') }}</span>
          </div>
        </div>
      </div>

      <div class="sz-auth-visual">
        <div>
          <h3>{{ __('Download StayZio Partner App') }}</h3>
          <p>{{ __('Manage rooms, change pricing, and monitor real-time confirmations right from your smartphone.') }}</p>
        </div>
        <div class="sz-store-buttons">
          <a href="#"><i class="fab fa-google-play"></i> Google Play</a>
          <a href="#"><i class="fab fa-apple"></i> App Store</a>
        </div>
      </div>
    </section>

    <section class="sz-login-card" id="login-card">
      <div class="sz-login-logo">
        <img src="{{ asset('assets/images/stayzio-logo.png') }}" alt="StayZio">
        <strong>Stay<span>Zio</span></strong>
      </div>
      <h2>{{ __('Welcome to Vendor') }}</h2>
      <p class="sz-sub">{{ __('Please Enter your details in the form below') }}</p>

      {{-- Flash Message Handlers --}}
      @if (Session::has('success'))
        <div class="alert alert-success m-3" role="alert">
          {{ __(Session::get('success')) }}
        </div>
      @endif
      @if (Session::has('error'))
        <div class="alert alert-danger m-3" role="alert">
          {{ __(Session::get('error')) }}
        </div>
      @endif

      

      {{-- Core Backend Form Implementation --}}
      <form action="{{ route('vendor.login_submit') }}" method="POST">
        @csrf
        
        <div class="sz-field">
          <label class="sz-label" for="email">{{ __('Email Address') }}</label>
          <input
            id="email"
            class="sz-input"
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="{{ __('Enter your email address') }}"
            required
          >
        </div>

        <div class="sz-field">
          <label class="sz-label" for="password">{{ __('Password') }}</label>
          <input 
            id="password"
            class="sz-input" 
            type="password" 
            name="password" 
            placeholder="{{ __('Enter password') }}" 
            required
          >
        </div>

        <button class="sz-primary-btn" type="submit">
          {{ __('Login') }}
        </button>
      </form>

      <div class="sz-divider"></div>
      
      <p class="sz-terms">
        {{ __('By continuing, you agree to our') }} 
        <a href="#">{{ __('Terms & Conditions') }}</a> 
        {{ __('and') }} 
        <a href="#">{{ __('Privacy Policy') }}</a>.
      </p>
      
      <p class="sz-signup">
        {{ __('Do not have an account?') }} 
        <a href="{{ route('vendor.signup') }}">{{ __('Sign up now') }}</a>
      </p>
    </section>

    <div id="signup-card" style="display:none;"></div>

  </div>
</main>
@endsection