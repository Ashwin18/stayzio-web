@extends('frontend.layout')
@section('pageHeading')
  {{ __('Success') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection

@section('content')
 <style>
.sz-confirm-page{min-height:calc(100vh - 80px);background:linear-gradient(180deg,#fff7f3 0%,#fff 52%,#fbfaf8 100%);padding:54px 18px 70px;position:relative;overflow:hidden;}
.sz-confirm-page:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 12% 18%,rgba(227,30,36,.12),transparent 28%),radial-gradient(circle at 88% 28%,rgba(255,182,64,.16),transparent 30%);pointer-events:none;}
.sz-confirm-shell{max-width:1050px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:.95fr 1.05fr;gap:30px;align-items:start;}
.sz-confirm-hero{background:linear-gradient(135deg,#f93932,#d80f18);border-radius:34px;padding:42px 32px;color:#fff;box-shadow:0 30px 76px rgba(227,30,36,.28);position:relative;overflow:hidden;min-height:560px;display:flex;flex-direction:column;justify-content:space-between;}
.sz-confirm-hero:before{content:"";position:absolute;right:-86px;top:-84px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.12);}.sz-confirm-hero:after{content:"";position:absolute;left:-80px;bottom:-92px;width:240px;height:240px;border-radius:50%;background:rgba(0,0,0,.08);}
.sz-checkmark{width:112px;height:112px;border:4px solid rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:54px;margin-bottom:24px;box-shadow:0 16px 36px rgba(0,0,0,.12);position:relative;z-index:1;}.sz-confirm-hero h1{font-size:42px;line-height:1.1;font-weight:900;margin:0 0 10px;position:relative;z-index:1;}.sz-confirm-hero p{font-size:16px;color:rgba(255,255,255,.88);line-height:1.6;margin:0;position:relative;z-index:1;}.sz-confirm-id{position:relative;z-index:1;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);border-radius:20px;padding:16px;margin-top:34px;}.sz-confirm-id span{display:block;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);font-weight:900;margin-bottom:5px;}.sz-confirm-id b{font-size:20px;letter-spacing:.03em;}
.sz-confirm-card{background:#fff;border:1px solid #f0dfd9;border-radius:32px;box-shadow:0 30px 80px rgba(18,24,40,.10);overflow:hidden;}.sz-confirm-top{padding:26px 28px;border-bottom:1px solid #f2ece9;display:flex;align-items:center;gap:16px;}.sz-confirm-top img{width:96px;height:82px;object-fit:cover;border-radius:20px;}.sz-confirm-top h2{font-size:22px;font-weight:900;margin:0 0 5px;color:#111827;}.sz-confirm-top p{margin:0;color:#737b8c;font-size:14px;}.sz-confirm-body{padding:26px 28px;}.sz-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px;}.sz-info-item{background:#fbfaf8;border:1px solid #f0ece9;border-radius:18px;padding:16px;}.sz-info-item span{display:block;color:#8b95a7;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;}.sz-info-item b{font-size:16px;color:#111827;}.sz-price-box{border:1px dashed rgba(227,30,36,.38);background:#fff8f7;border-radius:22px;padding:20px;margin-top:14px;}.sz-price-row{display:flex;justify-content:space-between;gap:12px;padding:9px 0;color:#606a7b;font-weight:700;}.sz-price-row.total{border-top:1px solid #f0d8d4;margin-top:8px;padding-top:16px;color:#111827;font-size:20px;font-weight:900;}.sz-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:24px;}.sz-actions a{height:54px;border-radius:16px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-weight:900;}.sz-actions .primary{background:linear-gradient(135deg,#ff3d35,#e31e24);color:#fff;box-shadow:0 14px 32px rgba(227,30,36,.25);}.sz-actions .secondary{background:#fff;color:#e31e24;border:1.5px solid #ffd2ce;}.sz-help-strip{margin-top:18px;background:#11131b;color:#fff;border-radius:22px;padding:18px 20px;display:flex;justify-content:space-between;align-items:center;gap:15px;}.sz-help-strip b{display:block;font-size:16px}.sz-help-strip span{color:#c9ced8;font-size:13px}.sz-help-strip a{color:#fff;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:13px;padding:10px 14px;text-decoration:none;font-weight:900;white-space:nowrap;}
@media(max-width:900px){.sz-confirm-shell{grid-template-columns:1fr;max-width:620px}.sz-confirm-hero{min-height:auto;padding:34px 26px}.sz-confirm-hero h1{font-size:34px}.sz-checkmark{width:94px;height:94px;font-size:44px}.sz-confirm-id{margin-top:24px}}
@media(max-width:560px){.sz-confirm-page{padding:26px 12px 46px}.sz-confirm-hero,.sz-confirm-card{border-radius:26px}.sz-confirm-hero{padding:30px 22px}.sz-confirm-hero h1{font-size:30px}.sz-confirm-top{padding:20px;align-items:flex-start}.sz-confirm-top img{width:82px;height:72px;border-radius:16px}.sz-confirm-body{padding:20px}.sz-info-grid{grid-template-columns:1fr}.sz-actions{grid-template-columns:1fr}.sz-help-strip{display:block}.sz-help-strip a{margin-top:13px;display:inline-flex}.sz-price-row.total{font-size:18px}}
</style>
<main class="sz-confirm-page">
  <div class="sz-confirm-shell">
    <section class="sz-confirm-hero">
      <div><div class="sz-checkmark"><i class="fas fa-check"></i></div><h1>Booking Confirmed!</h1><p>Your StayZio booking is confirmed. Show this booking ID at the hotel reception during check-in.</p><div class="sz-confirm-id"><span>Booking ID</span><b>{{$bookingInfo->order_number}}</b></div></div>
      <p style="margin-top:30px;font-size:13px;opacity:.85"><i class="fas fa-shield-alt"></i> 100% verified hotel · Instant confirmation · Secure payment</p>
    </section>
    <section>
      <div class="sz-confirm-card">
        <div class="sz-confirm-top"><img src="assets/images/hotel1.jpg" alt="Hotel"><div><h2>{{$hotelContent->title}}</h2><p><i class="fas fa-location-dot" style="color:#e31e24"></i> {{$cities->name}},{{$countries->name}}</p></div></div>
        <div class="sz-confirm-body">
            
          <div class="sz-info-grid"><div class="sz-info-item"><span>Stay Date</span><b>{{$bookingInfo->check_in_date}}</b></div><div class="sz-info-item"><span>Slot</span><b>Full Day · {{$bookingInfo->check_in_time}}</b></div><div class="sz-info-item"><span>Rooms</span><b>1 Room</b></div><div class="sz-info-item"><span>Guests</span><b>{{$bookingInfo->adult}} Adults</b></div></div>
          <div class="sz-price-box"><div class="sz-price-row"><span>Room price</span><b>₹{{$bookingInfo->roomPrice}}</b></div><div class="sz-price-row"><span>Add-ons</span><b>₹{{$bookingInfo->serviceCharge}}</b></div><div class="sz-price-row"><span>Discount</span><b style="color:#16a34a">-₹{{$bookingInfo->discount}}</b></div><div class="sz-price-row"><span>Taxes & Fees</span><b>₹{{$bookingInfo->tax}}</b></div><div class="sz-price-row total"><span>Total Paid</span><b>₹{{$bookingInfo->grand_total}}</b></div></div>
          <div class="sz-actions"><a href="user.html" class="primary">View Booking</a><a href="index.html" class="secondary">Back to Home</a></div>
        </div>
      </div>
      <div class="sz-help-strip"><div><b>Need help with this booking?</b><span>Our support team is available 24/7.</span></div><a href="#"><i class="fas fa-headset"></i> Contact Support</a></div>
    </section>
  </div>
</main> <!-- End Purchase Success Section -->
@endsection
