<style>
/* StayZio premium login page - scoped */
.sz-auth-page{background:linear-gradient(180deg,#fff7f3 0%,#ffffff 45%,#fbfaf8 100%);min-height:calc(100vh - 80px);overflow:hidden;}
/* FIXED: Reduced top padding to 24px and changed align-items to start to remove excess top space */
.sz-auth-wrap{max-width:1180px;margin:0 auto;padding:24px 22px 60px;display:grid;grid-template-columns:1.05fr .95fr;gap:42px;align-items:start;position:relative;}
.sz-auth-wrap:before{content:"";position:absolute;right:-180px;top:20px;width:430px;height:430px;background:radial-gradient(circle,rgba(227,30,36,.14),rgba(227,30,36,0) 68%);pointer-events:none;}
/* FIXED: Added a small top margin padding adjustment to align nicely with the right card logo */
.sz-auth-left{position:relative;z-index:1;padding-top:12px;}
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
.sz-field{margin-bottom:15px;}.sz-label{display:block;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.06em;color:#8b95a7;margin-bottom:8px;}
.sz-phone-field{display:flex;align-items:center;border:1.5px solid #e5e7eb;border-radius:16px;background:#fff;overflow:hidden;transition:.2s;}.sz-phone-field:focus-within{border-color:#e31e24;box-shadow:0 0 0 4px rgba(227,30,36,.08);}.sz-country{padding:0 14px;height:54px;display:flex;align-items:center;gap:7px;border-right:1px solid #e5e7eb;font-weight:900;color:#111827;white-space:nowrap;}.sz-phone-field input{border:0;outline:0;height:54px;flex:1;padding:0 14px;font-weight:400;color:#111827;min-width:0;}.sz-input{width:100%;height:54px;border:1.5px solid #e5e7eb;border-radius:16px;background:#fff;padding:0 15px;outline:0;font-weight:400;}.sz-input:focus{border-color:#e31e24;box-shadow:0 0 0 4px rgba(227,30,36,.08);}
.sz-primary-btn{width:100%;height:58px;border:0;border-radius:17px;background:linear-gradient(135deg,#ff3d35,#e31e24);color:#fff;font-weight:900;font-size:16px;box-shadow:0 16px 34px rgba(227,30,36,.28);cursor:pointer;transition:.18s;display:flex;align-items:center;justify-content:center;gap:8px;}.sz-primary-btn:hover{transform:translateY(-1px);box-shadow:0 20px 42px rgba(227,30,36,.34);}
.sz-divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:#a0a7b5;font-size:13px;}.sz-divider:before,.sz-divider:after{content:"";height:1px;background:#e5e7eb;flex:1;}
.sz-google-btn{width:100%;height:52px;border:1.5px solid #e5e7eb;background:#fff;border-radius:15px;font-weight:900;color:#222;display:flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;transition:.2s;}.sz-google-btn:hover{background:#f9f9f9;}
.sz-terms{text-align:center;color:#7a8394;font-size:12px;line-height:1.55;margin-top:16px;}.sz-terms a{color:#e31e24;font-weight:900;text-decoration:none;}
.sz-signup{text-align:center;margin-top:18px;color:#7a8394;font-size:14px;}.sz-signup a{color:#e31e24;font-weight:900;text-decoration:none;}
.sz-form-panel{display:none;}.sz-form-panel.active{display:block;}
.text-danger{color:#dc3545;font-size:12.5px;margin-top:5px;font-weight:700;display:block;}

@media(max-width:991px){
  .sz-auth-wrap{grid-template-columns:1fr;padding-top:24px;}
  .sz-auth-left{padding-top:0;text-align:center}
  .sz-auth-title{font-size:40px}
  .sz-auth-desc{margin-left:auto;margin-right:auto}
  .sz-auth-benefits{max-width:720px;margin-left:auto;margin-right:auto}
  .sz-auth-visual{max-width:720px;margin-left:auto;margin-right:auto}
  .sz-login-card{max-width:520px;margin:0 auto;width:100%;}
}
@media(max-width:640px){
  .sz-auth-wrap{padding:16px 14px 44px;gap:24px}
  .sz-auth-title{font-size:32px}
  .sz-auth-desc{font-size:15px}
  .sz-auth-benefits{grid-template-columns:1fr;gap:10px}
  .sz-benefit{min-height:auto;padding:15px;display:flex;align-items:center;gap:12px;text-align:left}
  .sz-benefit i{margin:0;flex:0 0 38px}
  .sz-auth-visual{padding:20px;border-radius:22px;display:block;text-align:left}
  .sz-auth-visual h3{font-size:22px}
  .sz-store-buttons{justify-content:flex-start;margin-top:14px}
  .sz-login-card{padding:24px 16px;border-radius:26px}
  .sz-login-logo img{width:56px;height:56px}	.sz-login-logo strong{font-size:24px}
  .sz-login-card h2{font-size:24px}
  .sz-country{padding:0 10px}
  .sz-phone-field input{padding:0 10px}
  .sz-primary-btn{height:54px}
  .navbar-section{position:relative;z-index:20;}
}
</style>