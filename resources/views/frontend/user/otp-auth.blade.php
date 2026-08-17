<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>StayZio Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{--red:#e31e24;--red2:#ff4a3f;--dark:#15151d;--muted:#727886;--soft:#f7f4ef;--line:#eee7df;--white:#fff;--shadow:0 24px 70px rgba(227,30,36,.18);--radius:28px}*{box-sizing:border-box;margin:0;padding:0}body{font-family:Inter,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:linear-gradient(180deg,#fffaf4 0%,#fff 55%,#fff7f2 100%);color:var(--dark)}a{text-decoration:none;color:inherit}.page{min-height:100vh;overflow:hidden}.topbar{height:78px;background:#f5f0e8;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:20}.topbar-inner{max-width:1180px;margin:auto;height:100%;display:flex;align-items:center;justify-content:space-between;padding:0 22px}.brand{display:flex;align-items:center;gap:10px;font-weight:900;font-size:24px}.brand img{width:48px;height:48px;object-fit:contain}.brand span b{color:var(--red)}.nav{display:flex;align-items:center;gap:28px;font-size:12px;font-weight:600}.nav a{color:#2d2d34}.nav .outline{border:1px solid #1d1d22;border-radius:999px;padding:11px 20px}.hamb{display:none;border:0;background:#fff;border-radius:14px;padding:11px;box-shadow:0 10px 25px rgba(0,0,0,.08)}.hero{position:relative;padding:54px 22px 70px}.hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 20% 8%,rgba(227,30,36,.14),transparent 28%),radial-gradient(circle at 88% 12%,rgba(255,122,92,.16),transparent 30%);pointer-events:none}.hero-inner{max-width:1180px;margin:auto;position:relative;display:grid;grid-template-columns:1.05fr .95fr;gap:42px;align-items:center}.eyebrow{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid #ffd2c9;color:var(--red);padding:10px 14px;border-radius:999px;font-weight:900;font-size:13px;box-shadow:0 10px 22px rgba(227,30,36,.1)}h1{font-size:64px;line-height:.98;letter-spacing:-2.4px;margin:22px 0 18px}.lead{font-size:18px;line-height:1.75;color:#5c6270;max-width:570px}.hero-points{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:30px}.point{background:#fff;border:1px solid var(--line);border-radius:20px;padding:16px;box-shadow:0 15px 35px rgba(0,0,0,.05)}.point i{color:var(--red);font-size:18px;margin-bottom:10px}.point strong{display:block;font-size:14px}.point small{display:block;color:var(--muted);font-size:12px;margin-top:5px}.visual{position:relative;min-height:640px;display:flex;align-items:flex-start;justify-content:flex-start}.red-card{position:absolute;right:0;top:20px;width:76%;height:490px;border-radius:40px;background:linear-gradient(135deg,var(--red),#bf090f);box-shadow:var(--shadow);overflow:hidden}.red-card:before{content:"";position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80') center/cover;opacity:.25}.red-card:after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(227,30,36,.88),rgba(227,30,36,.42))}.phone-card{position:relative;left:0;top:auto;margin-top:62px;width:420px;max-width:100%;background:#fff;border-radius:34px;padding:28px;box-shadow:0 32px 90px rgba(20,20,30,.18);border:1px solid #fff;z-index:2}.login-head{text-align:center;margin-bottom:22px}.login-head img{width:86px;height:86px;object-fit:contain;margin:auto auto 10px}.login-head h2{font-size:28px;letter-spacing:-.8px}.login-head p{color:var(--muted);font-size:14px;margin-top:7px}.tabs{display:grid;grid-template-columns:1fr 1fr;background:#f5f3f1;border-radius:16px;padding:5px;margin-bottom:18px}.tabs button{border:0;background:transparent;border-radius:12px;padding:12px;font-weight:900;color:#7b808b;cursor:pointer;font-family:inherit}.tabs button.active{background:#fff;color:var(--red);box-shadow:0 8px 18px rgba(0,0,0,.06)}.field{margin-bottom:14px}.field label{display:block;font-size:12px;font-weight:900;color:#8b93a1;margin-bottom:8px;text-transform:uppercase;letter-spacing:.08em}.input-wrap{display:flex;align-items:center;gap:10px;border:1.5px solid #ece7e2;border-radius:16px;background:#fff;padding:0 14px;height:56px}.input-wrap i{color:var(--red)}.input-wrap input{border:0;outline:0;font:inherit;width:100%;font-weight:700}.country{font-weight:900;border-right:1px solid #eee;padding-right:10px}.forgot{display:flex;justify-content:flex-end;margin:-4px 0 14px;color:var(--red);font-size:13px;font-weight:800}.btn{width:100%;height:58px;border:0;border-radius:17px;background:linear-gradient(135deg,var(--red),var(--red2));color:#fff;font-size:16px;font-weight:900;box-shadow:0 18px 35px rgba(227,30,36,.28);cursor:pointer;font-family:inherit}.btn:disabled{opacity:.6;cursor:not-allowed}.or{display:flex;align-items:center;gap:12px;margin:18px 0;color:#a1a7b2;font-size:13px}.or:before,.or:after{content:"";height:1px;background:#eee;flex:1}.google{height:52px;border:1.5px solid #e9e4de;background:#fff;border-radius:16px;width:100%;font-weight:900;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer;font-family:inherit}.signup{text-align:center;margin-top:18px;color:#707784;font-size:14px}.signup b{color:var(--red)}.otp-boxes{display:flex;align-items:center;justify-content:space-between;gap:8px;margin:12px 0 18px;width:100%}.otp-boxes input{width:48px;min-width:0;text-align:center;height:52px;border:1.5px solid #e7e2dd;border-radius:14px;font-weight:900;font-size:20px;background:#fff;box-shadow:0 8px 18px rgba(0,0,0,.035);transition:.2s}.otp-boxes input:focus{outline:none;border-color:var(--red);box-shadow:0 0 0 4px rgba(227,30,36,.1)}.otp-panel{margin-top:18px;padding:18px;background:#fff8f7;border:1px solid #ffd8d1;border-radius:18px}.otp-panel .otp-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}.otp-panel .otp-title{font-size:12px;font-weight:900;color:#8b93a1;text-transform:uppercase;letter-spacing:.08em}.otp-panel .resend{font-size:12px;font-weight:800;color:var(--red);cursor:pointer;background:none;border:none}.err-msg{color:#dc3545;font-size:12.5px;margin-top:6px;display:none}.test-mode{background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:9px 12px;font-size:11.5px;color:#996400;margin-bottom:10px}
.app-banner{max-width:1180px;margin:0 auto 60px;padding:0 22px}.app-card{background:#15151d;color:#fff;border-radius:34px;padding:34px;display:flex;align-items:center;justify-content:space-between;gap:25px;box-shadow:0 22px 70px rgba(0,0,0,.18);overflow:hidden;position:relative}.app-card:before{content:"";position:absolute;right:-60px;top:-90px;width:260px;height:260px;border-radius:50%;background:rgba(227,30,36,.28)}.app-card h3{font-size:28px;margin-bottom:8px}.app-card p{color:#c9cbd2}.store-row{display:flex;gap:12px;position:relative}.store{border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.08);color:#fff;border-radius:16px;padding:12px 18px;font-weight:900;display:flex;align-items:center;gap:10px}
@media(max-width:900px){.nav{display:none}.hamb{display:block}.hero{padding-top:30px;padding-bottom:44px}.hero-inner{grid-template-columns:1fr}.visual{min-height:auto;display:block}.red-card{display:none}.phone-card{position:relative;top:0;left:auto;width:100%;max-width:430px;margin:26px auto 0}.hero-points{grid-template-columns:1fr}h1{font-size:42px}.lead{font-size:16px}.app-card{flex-direction:column;text-align:center}.store-row{flex-wrap:wrap;justify-content:center}}
@media(max-width:520px){.topbar{height:68px}.brand{font-size:20px}.brand img{width:40px;height:40px}.hero{padding-left:14px;padding-right:14px;padding-bottom:34px}.phone-card{padding:18px;border-radius:26px}.login-head img{width:72px;height:72px}.login-head h2{font-size:24px}.input-wrap{height:52px}.btn{height:54px}h1{font-size:36px}.app-banner{padding:0 14px}.app-card{border-radius:24px;padding:25px 18px}.store{width:100%;justify-content:center}.otp-boxes{gap:6px}.otp-boxes input{width:42px;height:46px;font-size:18px;border-radius:12px}.otp-panel{padding:14px}}
</style>
</head>
<body>
<div class="page">
  <header class="topbar">
    <div class="topbar-inner">
      <a class="brand" href="{{ route('index') }}"><img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio"><span>Stay<b>Zio</b></span></a>
      <nav class="nav"><a href="{{ route('index') }}">Home</a><a href="{{ route('vendor.signup') }}">List Hotel</a><a>Help & Support</a><a class="outline" href="{{ route('user.otp.show') }}">Login / Sign up</a></nav>
      <button class="hamb"><i class="fa-solid fa-bars"></i></button>
    </div>
  </header>

  <section class="hero">
    <div class="hero-inner">
      <div>
        <div class="eyebrow"><i class="fa-solid fa-clock"></i> Hourly hotel booking made simple</div>
        <h1>Welcome back to StayZio.</h1>
        <p class="lead">Login with mobile OTP or password and continue booking verified hourly hotels, couple-friendly stays, and short-stay rooms at the best price.</p>
        <div class="hero-points">
          <div class="point"><i class="fa-solid fa-bolt"></i><strong>Pay by the hour</strong><small>3 hrs, 6 hrs, full day</small></div>
          <div class="point"><i class="fa-solid fa-shield-heart"></i><strong>Safe stays</strong><small>Verified hotels only</small></div>
          <div class="point"><i class="fa-solid fa-tags"></i><strong>Best offers</strong><small>Coupons and app deals</small></div>
        </div>
      </div>

      <div class="visual">
        <div class="red-card"></div>
        <div class="phone-card">
          <div class="login-head" id="loginHead">
            <img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio">
            <h2>Welcome Back</h2>
            <p>Login / Sign up to continue</p>
          </div>

          <div class="tabs" id="authTabs"><button class="active" onclick="switchTab('otp')">OTP</button><button onclick="switchTab('password')">Password</button></div>

          <!-- OTP FORM -->
          <div id="otpForm">
            <input type="hidden" id="otpPhone">
            <div class="field" id="phoneFieldWrap">
              <label>Mobile Number</label>
              <div class="input-wrap"><span class="country">+91</span><input id="inputPhone" maxlength="10" inputmode="numeric" placeholder="Enter mobile number" /></div>
              <div class="err-msg" id="errPhone"></div>
            </div>
            <button class="btn" id="btnSendOtp" onclick="sendOtp()">Continue</button>

            <div id="otpArea" class="otp-panel" style="display:none">
              <div class="otp-meta"><span class="otp-title" id="otpAreaTitle">Enter OTP</span><button class="resend" onclick="sendOtp()">Resend</button></div>
              <div class="test-mode" id="testModeBanner" style="display:none"></div>
              <div class="otp-boxes">
                <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
              </div>
              <div class="err-msg" id="errOtp"></div>
              <button class="btn" id="btnVerifyOtp" onclick="verifyOtp()">Verify & Login</button>
            </div>

            <!-- NEW USER NAME STEP -->
            <div id="nameArea" class="otp-panel" style="display:none">
              <div class="otp-meta"><span class="otp-title">Welcome! What's your name?</span></div>
              <div class="field" style="margin-top:10px"><div class="input-wrap"><i class="fa-regular fa-user"></i><input id="inputName" placeholder="Your full name" /></div></div>
              <div class="err-msg" id="errName"></div>
              <button class="btn" id="btnCompleteSignup" onclick="completeSignup()">Create Account</button>
            </div>
          </div>

          <!-- PASSWORD FORM -->
          <form id="passwordForm" style="display:none" action="{{ route('user.login_submit') }}" method="POST">
            @csrf
            <div class="field"><label>Email / Mobile</label><div class="input-wrap"><i class="fa-regular fa-user"></i><input name="email" placeholder="Enter email or mobile" /></div></div>
            <div class="field"><label>Password</label><div class="input-wrap"><i class="fa-solid fa-lock"></i><input type="password" name="password" placeholder="Enter password" /></div></div>
            <a class="forgot" href="{{ route('user.forget_password') }}">Forgot Password?</a>
            <button class="btn" type="submit">Login</button>
          </form>

          <div class="or">or</div>
          <a class="google" href="{{ route('user.login.google') }}"><i class="fa-brands fa-google"></i> Continue with Google</a>
          <div class="signup" id="signupLine">Do not have an account? <b>It's automatic with OTP!</b></div>
        </div>
      </div>
    </div>
  </section>

  <section class="app-banner"><div class="app-card"><div><h3>Download StayZio App</h3><p>Get exclusive short-stay deals, instant confirmation, and faster booking.</p></div><div class="store-row"><div class="store"><i class="fa-brands fa-google-play"></i> Google Play</div><div class="store"><i class="fa-brands fa-apple"></i> App Store</div></div></div></section>
</div>

<script>
function switchTab(tab){
  document.querySelectorAll('#authTabs button').forEach(function(b,i){
    b.classList.toggle('active',(tab==='otp'&&i===0)||(tab==='password'&&i===1));
  });
  document.getElementById('otpForm').style.display = tab==='otp' ? 'block' : 'none';
  document.getElementById('passwordForm').style.display = tab==='password' ? 'block' : 'none';
  document.getElementById('signupLine').style.display = tab==='otp' ? 'none' : 'block';
}

function showErr(id, msg){ var el=document.getElementById(id); el.textContent=msg; el.style.display = msg ? 'block' : 'none'; }

function sendOtp(){
  var phone = document.getElementById('inputPhone').value.trim();
  if(!/^[6-9][0-9]{9}$/.test(phone)){ showErr('errPhone','Enter a valid 10-digit mobile number'); return; }
  showErr('errPhone','');
  document.getElementById('otpPhone').value = phone;
  var btn = document.getElementById('btnSendOtp');
  btn.disabled = true; btn.textContent = 'Sending...';

  fetch('{{ route("user.otp.send") }}', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify({phone:phone})
  }).then(function(r){return r.json();}).then(function(data){
    btn.disabled = false; btn.textContent = 'Continue';
    if(data.status==='success'){
      document.getElementById('phoneFieldWrap').style.display='none';
      btn.style.display='none';
      var banner = document.getElementById('testModeBanner');
      if(data.test_mode_otp){ banner.style.display='block'; banner.textContent='TEST MODE (Nettyfish not yet connected) — OTP: '+data.test_mode_otp; }
      else { banner.style.display='none'; }
      document.querySelectorAll('#otpArea .otp-boxes input').forEach(function(d){d.value='';});
      document.getElementById('otpArea').style.display='block';
      document.querySelector('#otpArea .otp-boxes input').focus();
    } else {
      showErr('errPhone', data.message || 'Could not send OTP, please try again');
    }
  }).catch(function(){
    btn.disabled=false; btn.textContent='Continue';
    showErr('errPhone','Something went wrong, please try again');
  });
}

function verifyOtp(){
  var digits = document.querySelectorAll('#otpArea .otp-boxes input');
  var otp = Array.from(digits).map(function(d){return d.value;}).join('');
  if(otp.length!==6){ showErr('errOtp','Enter all 6 digits'); return; }
  showErr('errOtp','');
  var phone = document.getElementById('otpPhone').value;
  var btn = document.getElementById('btnVerifyOtp');
  btn.disabled=true; btn.textContent='Verifying...';

  fetch('{{ route("user.otp.verify") }}', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify({phone:phone, otp:otp})
  }).then(function(r){return r.json();}).then(function(data){
    btn.disabled=false; btn.textContent='Verify & Login';
    if(data.status==='success'){
      if(data.is_new_user){
        document.getElementById('otpArea').style.display='none';
        document.getElementById('nameArea').style.display='block';
      } else {
        window.location.href = data.redirect;
      }
    } else {
      showErr('errOtp', data.message || 'Invalid OTP');
    }
  }).catch(function(){
    btn.disabled=false; btn.textContent='Verify & Login';
    showErr('errOtp','Something went wrong, please try again');
  });
}

function completeSignup(){
  var name = document.getElementById('inputName').value.trim();
  if(!name){ showErr('errName','Please enter your name'); return; }
  showErr('errName','');
  var phone = document.getElementById('otpPhone').value;
  var btn = document.getElementById('btnCompleteSignup');
  btn.disabled=true; btn.textContent='Creating account...';

  fetch('{{ route("user.otp.complete_signup") }}', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify({phone:phone, name:name})
  }).then(function(r){return r.json();}).then(function(data){
    if(data.status==='success'){ window.location.href = data.redirect; }
    else { btn.disabled=false; btn.textContent='Create Account'; showErr('errName', data.message || 'Something went wrong'); }
  }).catch(function(){
    btn.disabled=false; btn.textContent='Create Account';
    showErr('errName','Something went wrong, please try again');
  });
}

document.querySelectorAll('#otpArea .otp-boxes input').forEach(function(input,idx,all){
  input.addEventListener('input',function(){
    this.value = this.value.replace(/\D/g,'').slice(0,1);
    if(this.value && all[idx+1]) all[idx+1].focus();
  });
  input.addEventListener('keydown',function(e){
    if(e.key==='Backspace' && !this.value && all[idx-1]) all[idx-1].focus();
  });
});
</script>
</body>
</html>