<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StayZio – Partner Registration Submitted </title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <style>
    :root{
      --bg:#fbf7f4;
      --text:#1f1a17;
      --muted:#776b65;
      --line:#efe2db;
      --primary:#ef3d2f;
      --primary-dark:#d93225;
      --primary-soft:#fff2ef;
      --gold:#c9a56a;
      --gold-soft:#fbf4ea;
      --dark:#131418;
      --card:#ffffff;
      --shadow:0 20px 60px rgba(27,16,12,.10);
      --shadow-lg:0 30px 90px rgba(27,16,12,.16);
      --container:960px;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;
      color:var(--text);
      background:
        radial-gradient(circle at 12% 12%, rgba(239,61,47,.10), transparent 18%),
        radial-gradient(circle at 88% 14%, rgba(201,165,106,.10), transparent 16%),
        radial-gradient(circle at 50% 100%, rgba(239,61,47,.06), transparent 24%),
        linear-gradient(180deg,#fcf8f5 0%, #fbf7f4 100%);
      overflow-x:hidden;
    }
    a{text-decoration:none;color:inherit}
    .container{width:min(var(--container), calc(100% - 24px));margin:0 auto}
    .title-serif{font-family:"Playfair Display", Georgia, serif;letter-spacing:-.03em}
    .luxury-serif{font-family:"Cormorant Garamond", Georgia, serif;letter-spacing:-.02em}

    .topbar{
      position:sticky;top:0;z-index:50;
      background:rgba(255,251,248,.92);
      backdrop-filter:blur(10px);
      border-bottom:1px solid rgba(0,0,0,.05);
    }
    .nav{
      height:78px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:18px;
    }
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{
      width:44px;height:44px;border-radius:12px;object-fit:cover;
      box-shadow:0 10px 22px rgba(239,61,47,.15);
    }
    .brand-name{font-size:30px;font-weight:800;line-height:1}
    .brand-name .stay,.brand-name .zio{font-family:"Playfair Display", Georgia, serif}
    .brand-name .zio{color:var(--primary)}
    .top-btn{
      border:none;border-radius:999px;padding:12px 18px;font-weight:800;cursor:pointer;
      background:linear-gradient(180deg,var(--primary),var(--primary-dark));
      color:#fff;box-shadow:0 14px 28px rgba(239,61,47,.24);
    }

    .page{
      min-height:calc(100vh - 78px);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:34px 0;
      position:relative;
      overflow:hidden;
    }
    .page:before,.page:after{
      content:"";
      position:absolute;
      border-radius:50%;
      pointer-events:none;
      filter:blur(10px);
      animation:floatGlow 8s ease-in-out infinite;
    }
    .page:before{
      width:340px;height:340px;
      left:-90px;top:70px;
      background:radial-gradient(circle, rgba(239,61,47,.18), transparent 70%);
    }
    .page:after{
      width:380px;height:380px;
      right:-110px;bottom:10px;
      background:radial-gradient(circle, rgba(201,165,106,.14), transparent 70%);
      animation-direction:reverse;
      animation-duration:9s;
    }
    @keyframes floatGlow{
      0%,100%{transform:translateY(0) translateX(0)}
      50%{transform:translateY(-18px) translateX(10px)}
    }

    .success-card{
      position:relative;
      z-index:2;
      background:linear-gradient(180deg,#fffefc 0%, #fff 100%);
      border:1px solid var(--line);
      border-radius:36px;
      box-shadow:var(--shadow-lg);
      overflow:hidden;
      max-width:880px;
      width:100%;
    }
    .success-card:before{
      content:"";
      position:absolute;
      right:-50px;top:-50px;
      width:200px;height:200px;border-radius:50%;
      background:radial-gradient(circle, rgba(239,61,47,.08), transparent 66%);
      pointer-events:none;
    }
    .success-card:after{
      content:"";
      position:absolute;
      left:-46px;bottom:-46px;
      width:180px;height:180px;border-radius:50%;
      background:radial-gradient(circle, rgba(201,165,106,.10), transparent 68%);
      pointer-events:none;
    }

    .card-top{
      position:relative;
      padding:34px 34px 20px;
      text-align:center;
      background:
        linear-gradient(180deg,#fff9f7 0%, #fff 100%);
      border-bottom:1px solid #f3e8e2;
    }
    .status-pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:9px 14px;
      border-radius:999px;
      background:var(--primary-soft);
      border:1px solid #ffd7cf;
      color:var(--primary);
      font-size:12px;
      font-weight:800;
      letter-spacing:.05em;
      text-transform:uppercase;
      margin-bottom:16px;
    }
    .lottie-shell{
      width:170px;
      height:170px;
      margin:0 auto 6px;
      border-radius:28px;
      background:linear-gradient(180deg,#fff4f1,#fffaf8);
      border:1px solid #f6ddd6;
      display:grid;
      place-items:center;
      box-shadow:0 18px 34px rgba(239,61,47,.08);
    }
    .card-top h1{
      margin:8px 0 10px;
      font-size:clamp(34px,4vw,58px);
      line-height:1.02;
    }
    .card-top p{
      margin:0 auto;
      max-width:700px;
      color:var(--muted);
      font-size:15px;
      line-height:1.85;
    }

    .card-body{
      padding:26px 34px 34px;
    }
    .ref-chip{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 14px;
      border-radius:999px;
      background:#fff8f6;
      border:1px solid #f4dfd8;
      color:#7f6a63;
      font-size:13px;
      font-weight:700;
      margin-bottom:22px;
    }
    .ref-chip strong{color:var(--primary);letter-spacing:.04em}

    .info-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:18px;
      align-items:start;
    }

    .lux-box{
      background:#fff;
      border:1px solid #f1e5de;
      border-radius:26px;
      box-shadow:0 14px 32px rgba(28,18,12,.05);
      padding:22px;
      position:relative;
      overflow:hidden;
    }
    .lux-box:before{
      content:"";
      position:absolute;
      right:-30px;top:-30px;
      width:120px;height:120px;border-radius:50%;
      background:radial-gradient(circle, rgba(239,61,47,.07), transparent 68%);
      pointer-events:none;
    }
    .lux-box h3{
      margin:0 0 14px;
      font-size:28px;
      line-height:1.08;
    }

    .timeline{
      display:grid;
      gap:12px;
    }
    .timeline-row{
      display:grid;
      grid-template-columns:40px 1fr;
      gap:12px;
      align-items:start;
      padding:14px;
      border-radius:18px;
      background:#fff9f7;
      border:1px solid #f6e4dd;
    }
    .timeline-icon{
      width:40px;height:40px;border-radius:14px;
      display:grid;place-items:center;
      background:var(--primary-soft);
      color:var(--primary);
      font-weight:900;
    }
    .timeline-row strong{
      display:block;
      margin-bottom:5px;
      font-size:15px;
    }
    .timeline-row span{
      display:block;
      color:#6f6560;
      font-size:13px;
      line-height:1.65;
    }

    .status-grid{
      display:grid;
      gap:12px;
    }
    .status-row{
      padding:16px;
      border-radius:20px;
      background:linear-gradient(180deg,#fff4f1,#fff);
      border:1px solid #f4ddd6;
      text-align:left;
    }
    .status-row strong{
      display:block;
      font-size:15px;
      margin-bottom:6px;
      color:#2a2421;
    }
    .status-row span{
      display:block;
      color:#6f6560;
      font-size:13px;
      line-height:1.65;
    }

    .timer-strip{
      margin-top:18px;
      display:grid;
      grid-template-columns:repeat(3,minmax(0,1fr));
      gap:12px;
    }
    .timer-box{
      padding:16px;
      border-radius:20px;
      background:linear-gradient(180deg,#1b1c20 0%, #311313 100%);
      color:#fff;
      text-align:center;
      box-shadow:0 16px 34px rgba(28,18,12,.12);
    }
    .timer-box strong{
      display:block;
      font-size:36px;
      line-height:1;
      font-family:"Cormorant Garamond", Georgia, serif;
    }
    .timer-box span{
      display:block;
      margin-top:8px;
      color:rgba(255,246,241,.78);
      font-size:12px;
      font-weight:800;
      letter-spacing:.05em;
      text-transform:uppercase;
    }

    .cta-row{
      margin-top:22px;
      display:flex;
      justify-content:center;
      gap:12px;
      flex-wrap:wrap;
    }
    .btn{
      border:none;border-radius:999px;padding:14px 22px;font-weight:800;cursor:pointer;transition:.22s ease
    }
    .btn-primary{
      background:linear-gradient(180deg,var(--primary),var(--primary-dark));
      color:#fff;box-shadow:0 16px 28px rgba(239,61,47,.24);
    }
    .btn-outline{
      background:#fff;
      color:var(--primary);
      border:1px solid rgba(239,61,47,.18);
    }
    .btn:hover{transform:translateY(-1px)}

    .support-line{
      margin-top:18px;
      text-align:center;
      color:#7d716b;
      font-size:14px;
    }
    .support-line a{
      color:var(--primary);
      font-weight:800;
      text-decoration:none;
    }

    @media (max-width: 900px){
      .info-grid{grid-template-columns:1fr}
      .timer-strip{grid-template-columns:1fr 1fr 1fr}
    }
    @media (max-width: 760px){
      .container{width:min(var(--container), calc(100% - 20px))}
      .card-top,.card-body{padding:22px}
      .lottie-shell{width:140px;height:140px}
      .timer-strip{grid-template-columns:1fr}
      .cta-row{flex-direction:column}
      .btn{width:100%}
      .brand-name{font-size:26px}
      .top-btn{display:none}
    }
  </style>

<style>
.timer-strip,.timer-box{display:none !important;}
/* final polish */
.success-card{animation:fadeUp .8s ease both;}
.lux-box{animation:fadeUp .8s ease both;}
.lux-box:nth-of-type(2){animation-delay:.12s}
.ref-chip{
  display:flex !important;
  justify-content:center;
  width:max-content;
  margin:0 auto 22px !important;
  box-shadow:0 10px 24px rgba(239,61,47,.08);
}
.status-row,.timeline-row{
  transition:transform .22s ease, box-shadow .22s ease;
}
.status-row:hover,.timeline-row:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 24px rgba(27,16,12,.06);
}
@keyframes fadeUp{
  from{opacity:0;transform:translateY(14px)}
  to{opacity:1;transform:none}
}
</style>


<style id="shared-header-footer-nav">
:root{
  --sz-primary:#ef3d2f;
  --sz-primary-dark:#d93225;
}
.topbar,.site-header{
  position:sticky;top:0;z-index:250;background:rgba(255,251,248,.95);backdrop-filter:blur(10px);
  border-bottom:1px solid rgba(0,0,0,.05);
}
.nav,.header-inner{
  min-height:76px;display:flex;align-items:center;justify-content:space-between;gap:18px;
}
.brand,.logo-wrap{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit}
.brand img,.logo-wrap img,.brand-logo{
  width:44px;height:44px;border-radius:12px;object-fit:cover;box-shadow:0 10px 22px rgba(239,61,47,.15);
}
.brand-name{font-size:30px;font-weight:800;line-height:1}
.brand-name .stay,.brand-name .zio{font-family:"Playfair Display", Georgia, serif}
.brand-name .zio{color:var(--sz-primary)}
.nav-links,.nav-actions,.desktop-menu{
  display:flex;align-items:center;gap:22px;
}
.nav-links a,.nav-actions a,.desktop-menu a{
  font-size:14px;font-weight:600;color:#493f3a;text-decoration:none;
}
.nav-links .btn-outline,.nav-actions .btn-outline,.desktop-menu .btn-outline{
  border:1px solid rgba(239,61,47,.18);background:#fff;color:var(--sz-primary);
  padding:12px 18px;border-radius:999px;
}
.menu-toggle{
  display:none;width:44px;height:44px;border:1px solid #eadfd9;background:#fff;border-radius:12px;cursor:pointer;
  align-items:center;justify-content:center;flex-direction:column;gap:4px;
}
.menu-toggle span{display:block;width:18px;height:2px;background:#3f3733;border-radius:4px}
.mobile-drawer{
  display:none;position:fixed;inset:76px 0 auto 0;background:rgba(255,251,248,.98);backdrop-filter:blur(10px);
  border-bottom:1px solid #eee2db;padding:14px 20px 18px;z-index:240;
}
.mobile-drawer.open{display:block}
.mobile-drawer a{
  display:block;padding:14px 0;border-bottom:1px solid #f2e9e4;text-decoration:none;color:#403733;font-weight:700;
}
.mobile-drawer a:last-child{border-bottom:none}
.shared-footer{
  background:linear-gradient(180deg,#0c0d11 0%, #090a0d 100%); color:#fff; margin-top:40px; border-top:1px solid rgba(255,255,255,.04);
}
.shared-footer .footer-wrap{max-width:1360px;margin:auto;padding:40px 52px 18px;}
.shared-footer .footer-grid{display:grid;grid-template-columns:1.45fr 1fr 1fr 1fr;gap:44px;align-items:start;}
.shared-footer .footer-brand-row{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
.shared-footer .footer-brand-row img{width:38px;height:38px;border-radius:10px;object-fit:cover;}
.shared-footer .footer-desc{max-width:340px;color:#b9b0a9;font-size:15px;line-height:1.55;margin-bottom:20px;}
.shared-footer .footer-meta{display:grid;gap:8px;color:#fff;font-size:15px;font-weight:600;}
.shared-footer .footer-col h4{color:#8f837a;font-size:12px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin:0 0 16px;}
.shared-footer .footer-col div{display:grid;gap:10px;color:#f4f1ee;font-size:15px;}
.shared-footer .footer-bottom{margin-top:34px;border-top:1px solid rgba(255,255,255,.08);padding-top:18px;display:flex;justify-content:space-between;gap:16px;color:#8d837d;font-size:12px;}
.float-widgets{position:fixed;right:18px;bottom:18px;display:grid;gap:12px;z-index:260}
.float-widgets a{
  width:56px;height:56px;border-radius:999px;display:grid;place-items:center;text-decoration:none;color:#fff;font-size:24px;font-weight:900;
  box-shadow:0 14px 28px rgba(0,0,0,.16)
}
.float-widgets .wa{background:#25D366}
.float-widgets .call{background:linear-gradient(180deg,var(--sz-primary),var(--sz-primary-dark))}
@media (max-width: 900px){
  .nav-links,.nav-actions,.desktop-menu{display:none !important}
  .menu-toggle{display:flex}
  .shared-footer .footer-wrap{padding:32px 18px 18px !important}
  .shared-footer .footer-grid{grid-template-columns:1fr 1fr;gap:28px}
}
@media (max-width: 640px){
  .shared-footer .footer-grid{grid-template-columns:1fr}
  .shared-footer .footer-bottom{flex-direction:column}
}
</style>

</head>
<body>

<header class="topbar">
  <div class="container nav">
    <a class="brand" href="https://stayziohotels.com/">
      <img src="logo.png" alt="StayZio logo">
      <div class="brand-name"><span class="stay">Stay</span><span class="zio">Zio</span></div>
    </a>
    <nav class="nav-links">
      <a href="#contact-us">Contact us</a>
      <a href="list-your-hotel.html">List your Hotel</a>
      <a href="#" class="btn-outline">Login / Sign up</a>
    </nav>
    <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
<div class="mobile-drawer" id="mobileDrawer">
  <a href="#contact-us">Contact us</a>
  <a href="list-your-hotel.html">List your Hotel</a>
  <a href="#">Login / Sign up</a>
</div>

  <main class="page">
    <div class="container">
      <section class="success-card">
        <div class="card-top">
          <div class="status-pill">Partner Registration Submitted</div>
          <div class="lottie-shell">
            <lottie-player
              src="https://assets10.lottiefiles.com/packages/lf20_jbrw3hcz.json"
              background="transparent"
              speed="1"
              style="width:120px;height:120px;"
              autoplay
              loop>
            </lottie-player>
          </div>
          <h1 class="title-serif">Thank you for partnering with StayZio</h1>
          <p>Your hotel registration has been submitted successfully. Please wait while our admin team reviews your application and verifies the submitted details before approval.</p>
        </div>

        <div class="card-body">
          <div class="ref-chip" style="display:flex;justify-content:center;margin:0 auto 22px;">Reference ID <strong>STAYZIO-PH-2026-001</strong></div>

          <div class="info-grid">
            <div class="lux-box">
              <h3 class="title-serif">What happens next?</h3>
              <div class="timeline">
                <div class="timeline-row">
                  <div class="timeline-icon">1</div>
                  <div>
                    <strong>Submission received</strong>
                    <span>Your partner registration has been securely recorded in the StayZio onboarding system.</span>
                  </div>
                </div>
                <div class="timeline-row">
                  <div class="timeline-icon">2</div>
                  <div>
                    <strong>Admin review in progress</strong>
                    <span>Our team will review hotel details, contact information, and basic property readiness.</span>
                  </div>
                </div>
                <div class="timeline-row">
                  <div class="timeline-icon">3</div>
                  <div>
                    <strong>Approval and next steps</strong>
                    <span>Once approved, you will receive communication on activation and further onboarding guidance.</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="lux-box">
              <h3 class="title-serif">Approval status</h3>
              <div class="status-grid" style="margin-bottom:6px;">
                <div class="status-row">
                  <strong>Current Status</strong>
                  <span>Pending admin approval</span>
                </div>
                <div class="status-row">
                  <strong>Expected Window</strong>
                  <span>Approval generally takes a short review cycle depending on the completeness of submitted information.</span>
                </div>
                <div class="status-row">
                  <strong>Partner Support</strong>
                  <span>You may be contacted if any additional details are required during verification.</span>
                </div>
              </div>

              
                
                
              </div>
            </div>
          </div>

          <div class="cta-row">
            <button class="btn btn-outline">Contact Support</button>
            <button class="btn btn-primary">Back to Home</button>
          </div>

          <div class="support-line">
            Need help with your submission? <a href="#">Talk to Partner Support</a>
          </div>
        </div>
      </section>
    </div>
  </main>




<footer class="shared-footer" id="contact-us">
  <div class="footer-wrap">
    <div class="footer-grid">
      <div>
        <div class="footer-brand-row">
          <img src="logo.png" alt="StayZio logo">
          <div style="font-family:'Playfair Display', Georgia, serif;font-size:24px;font-weight:800;line-height:1;">
            <span style="color:#fff;">Stay</span><span style="color:#ef3d2f;">Zio</span>
          </div>
        </div>
        <div class="footer-desc">StayZio is the fastest growing hourly hotel brand in India. Book premium hotel stays by the hour across major cities.</div>
        <div class="footer-meta">
          <div>📞 +91 99444 67727</div>
          <div>✉️ info@stayziohotels.com</div>
        </div>
      </div>
      <div class="footer-col">
        <h4>Top Cities</h4>
        <div><span>Mumbai</span><span>Delhi</span><span>Bangalore</span><span>Chennai</span><span>Hyderabad</span></div>
      </div>
      <div class="footer-col">
        <h4>Stay Types</h4>
        <div><span>3 Hour Stay</span><span>6 Hour Stay</span><span>12 Hour Stay</span><span>Overnight Stay</span><span>Full Day Stay</span></div>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <div><span>About Us</span><span>List Your Hotel</span><span>Careers</span><span>Privacy Policy</span><span>Terms & Conditions</span></div>
      </div>
    </div>
    <div class="footer-bottom">
      <div>© 2026 StayZio Hotels. All rights reserved.</div>
      <div>StayZio Hotels</div>
    </div>
  </div>
</footer>
<div class="float-widgets">
  <a class="wa" href="https://wa.me/919944467727" target="_blank" aria-label="WhatsApp">✆</a>
  <a class="call" href="tel:+919944467727" aria-label="Call">☎</a>
</div>
<script>
(function(){
  var t=document.getElementById('menuToggle');
  var d=document.getElementById('mobileDrawer');
  if(t&&d){t.addEventListener('click',function(){d.classList.toggle('open');});}
})();
</script>

</body>
</html>
