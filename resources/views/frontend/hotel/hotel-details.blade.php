@extends('frontend.layout')

@section('pageHeading'){{ $hotel->title }}@endsection
@section('metaKeywords')@if(!empty($hotel)){{ $hotel->meta_keyword }}@endif@endsection
@section('metaDescription')@if(!empty($hotel)){{ $hotel->meta_description }}@endif@endsection
@section('ogTitle')@if(!empty($hotel)){{ $hotel->title }}@endif@endsection

@section('content')
@php
  // ── Currency ──
  $sym = $currencyInfo->base_currency_symbol ?? '₹';
  $pos = $currencyInfo->base_currency_symbol_position ?? 'left';
  $fmt = fn($v) => $pos==='left' ? $sym.number_format($v,0) : number_format($v,0).$sym;

  // ── Hotel amenities ──
  $hotelAmenities = json_decode($hotel->amenities ?? '[]', true) ?? [];

  // ── Perks from master data ──
  $selPerkIds = json_decode($hotel->perks ?? '[]', true) ?? [];
  $hotelPerks = \App\Models\HotelPerk::whereIn('id', $selPerkIds)->where('status',1)->get();

  // ── Policies from master data ──
  $selPolIds = json_decode($hotel->policies ?? '[]', true) ?? [];
  $hotelPolicies = \App\Models\HotelPolicy::whereIn('id', $selPolIds)->where('status',1)->get();

  // ── Restrictions from master data ──
  $selRestrictions = json_decode($hotel->restrictions ?? '[]', true) ?? [];
  $restrMap = [];
  foreach($selRestrictions as $r) {
    if(isset($r['id'])) $restrMap[$r['id']] = $r['type'] ?? 'not_allowed';
  }
  $restrIds = array_keys($restrMap);
  $hotelRestrictions = \App\Models\HotelRestriction::whereIn('id', $restrIds)->where('status',1)->get();

  // ── Wishlist ──
  $checkWishList = false;
  if(Auth::guard('web')->check()) {
    $checkWishList = checkHotelWishList($hotel->id, Auth::guard('web')->user()->id);
  }

  // ── Gallery images ──
  $galleryImages = $hotelImages ?? collect();
@endphp

{{-- ── STYLES ── --}}
<style>
/* ── Hotel Detail Page Overrides ── */
.hd-wrap{max-width:1180px;margin:0 auto;padding:0 20px 80px}

/* Gallery */
.hd-gallery{display:grid;grid-template-columns:1.9fr 1fr;grid-template-rows:220px 110px;gap:5px;margin:18px 0 0}
.hd-g-main{grid-row:1/3;border-radius:14px;overflow:hidden;cursor:pointer;position:relative}
.hd-g-main img,.hd-g-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.hd-g-main:hover img,.hd-g-thumb:hover img{transform:scale(1.04)}
.hd-g-thumb{border-radius:10px;overflow:hidden;cursor:pointer;position:relative}
.hd-g-more{position:absolute;inset:0;background:rgba(0,0,0,.48);display:flex;align-items:center;justify-content:center;gap:6px;color:#fff;font-size:13px;font-weight:700}

/* Hotel info bar */
.hd-infobar{background:#fff;border-radius:14px;padding:18px 22px;margin:14px 0 0;display:flex;justify-content:space-between;align-items:flex-start;gap:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid #f0ebe4}
.hd-name{font-family:'Poppins',sans-serif;font-size:22px;font-weight:800;color:#0c0c0e;margin-bottom:6px;line-height:1.2}
.hd-meta{display:flex;flex-wrap:wrap;align-items:center;gap:7px;margin-bottom:8px}
.hd-cat{background:#fef3c7;color:#92400e;font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em}
.hd-loc{font-size:12px;color:#64748b;display:flex;align-items:center;gap:4px}
.hd-loc i{color:var(--red);font-size:11px}
.hd-stars-row{color:#f59e0b;font-size:13px;letter-spacing:1px}
.hd-rating-badge{background:#dcfce7;color:#15803d;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:4px}
.hd-infobar-actions{display:flex;gap:8px;flex-shrink:0}
.hd-action-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:10px;border:1.5px solid #e8eaed;background:#fff;font-size:12px;font-weight:700;color:#64748b;cursor:pointer;font-family:inherit;transition:.15s}
.hd-action-btn:hover{border-color:#64748b;color:#0c0c0e}

/* Sticky tab nav — reuse existing .tab-nav styles */
.hd-tab-nav{background:#fff;border-bottom:1.5px solid #e8eaed;position:sticky;top:64px;z-index:400;box-shadow:0 2px 8px rgba(0,0,0,.04)}
.hd-tab-nav-inner{max-width:1180px;margin:0 auto;padding:0 20px;display:flex;overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch}
.hd-tab-nav-inner::-webkit-scrollbar{display:none}
.hd-tab{padding:13px 18px;font-size:13px;font-weight:600;color:#64748b;border-bottom:2.5px solid transparent;cursor:pointer;white-space:nowrap;background:none;border-top:none;border-left:none;border-right:none;font-family:inherit;transition:all .15s;display:inline-flex;align-items:center;gap:6px}
.hd-tab:hover{color:#0c0c0e}
.hd-tab.active{color:var(--red);border-bottom-color:var(--red)}
.hd-tab i{font-size:12px}

/* Body grid */
.hd-body{max-width:1180px;margin:0 auto;padding:18px 20px 0;display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start}

/* Reuse scard from hotel_detials.css - but with smaller padding for hotel page */
.hd-scard{background:#fff;border-radius:14px;border:1px solid #f0ebe4;box-shadow:0 2px 12px rgba(0,0,0,.06);overflow:visible;margin-bottom:14px}
.hd-scard-head{padding:14px 18px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px}
.hd-scard-icon{width:34px;height:34px;background:#fff1f1;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.hd-scard-icon i{color:var(--red);font-size:14px}
.hd-scard-eyebrow{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;display:block;margin-bottom:1px}
.hd-scard-title{font-size:14px;font-weight:800;color:#0c0c0e}
.hd-scard-body{padding:16px 18px;font-size:13px;color:#64748b;line-height:1.75}

/* Amenities - reuse am-grid */
.hd-am-grid{display:flex;flex-wrap:wrap;gap:8px}
.hd-am-item{background:#f7f8fa;border:1px solid #e8eaed;border-radius:20px;padding:6px 13px;font-size:12px;font-weight:600;color:#2d2d35;display:inline-flex;align-items:center;gap:7px;transition:.15s}
.hd-am-item:hover{border-color:var(--red);background:#fff1f1}
.hd-am-item i{color:var(--red);font-size:13px}

/* Perks - same as amenities */
.hd-perks-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.hd-perk-item{background:#f7f8fa;border:1px solid #e8eaed;border-radius:10px;padding:10px 13px;display:flex;align-items:center;gap:9px;font-size:12px;font-weight:600;color:#2d2d35;transition:.15s}
.hd-perk-item:hover{border-color:var(--red);background:#fff1f1}
.hd-perk-item i{color:var(--red);font-size:15px;width:20px;text-align:center}

/* Policies */
.hd-pol-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.hd-pol-item{background:#f7f8fa;border:1px solid #e8eaed;border-radius:10px;padding:11px 14px}
.hd-pol-label{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;display:flex;align-items:center;gap:5px;margin-bottom:4px}
.hd-pol-label i{color:var(--red);font-size:11px}
.hd-pol-val{font-size:12px;font-weight:700;color:#0c0c0e;line-height:1.4}

/* Restrictions - reuse rest-grid pattern */
.hd-rest-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.hd-rest-item{display:flex;align-items:center;gap:9px;padding:9px 12px;background:#f7f8fa;border:1px solid #e8eaed;border-radius:10px;transition:.15s}
.hd-rest-item:hover{border-color:var(--border)}
.hd-rest-icon{font-size:18px;flex-shrink:0;width:26px;text-align:center}
.hd-rest-name{font-size:12px;font-weight:600;color:#2d2d35;flex:1}
.hd-rest-badge{font-size:9px;font-weight:800;padding:2px 8px;border-radius:20px;white-space:nowrap}
.badge-allowed{background:#dcfce7;color:#15803d}
.badge-limited{background:#fef3c7;color:#92400e}
.badge-not_allowed{background:#fee2e2;color:#dc2626}

/* Map placeholder */
.hd-map{background:#f7f8fa;border:1px solid #e8eaed;border-radius:10px;height:200px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:#64748b;font-size:13px;font-weight:600}
.hd-map i{font-size:32px;color:var(--red)}

/* Right col */
.hd-right{display:flex;flex-direction:column;gap:12px;position:sticky;top:124px}

/* Booking widget - reuse bwidget from hotel_detials.css */
.hd-bwidget{background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.1);border:1px solid #f0ebe4}
.hd-bw-top{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);padding:16px 18px;position:relative;overflow:hidden}
.hd-bw-top::before{content:'';position:absolute;top:-40px;right:-40px;width:140px;height:140px;border-radius:50%;background:rgba(227,30,36,.12)}
.hd-bw-from{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:4px}
.hd-bw-from-note{font-size:11px;color:rgba(255,255,255,.6);margin-top:4px}
.hd-bw-slots{display:flex;gap:5px;margin-bottom:12px}
.hd-bw-slot{flex:1;padding:7px 4px;border:1.5px solid #e8eaed;border-radius:10px;text-align:center;cursor:pointer;transition:all .15s;background:#fff}
.hd-bw-slot:hover,.hd-bw-slot.active{border-color:var(--red);background:#fff1f1}
.hd-bw-slot-hr{display:block;font-size:11px;font-weight:700;color:#0c0c0e;font-family:'Poppins',sans-serif}
.hd-bw-slot.active .hd-bw-slot-hr{color:var(--red)}
.hd-bw-slot-pr{display:block;font-size:10px;color:#94a3b8;margin-top:2px}
.hd-bw-slot.active .hd-bw-slot-pr{color:var(--red)}
.hd-bw-form{padding:14px 18px;display:flex;flex-direction:column;gap:10px}
.hd-field-lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;display:block;margin-bottom:3px}
.hd-bw-inp{width:100%;border:1.5px solid #e8eaed;border-radius:10px;padding:8px 11px;font-size:13px;color:#0c0c0e;font-family:inherit;transition:border-color .2s;background:#fff}
.hd-bw-inp:focus{outline:none;border-color:var(--red);box-shadow:0 0 0 3px rgba(227,30,36,.08)}
.hd-bw-row2{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.hd-bw-summary{background:#f7f8fa;border-radius:10px;padding:11px 14px;margin:4px 0}
.hd-bw-line{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#64748b;padding:4px 0;border-bottom:1px solid #f3f4f6}
.hd-bw-line:last-child{border:none;padding-top:7px;margin-top:3px;font-size:13px;font-weight:800;color:#0c0c0e}
.hd-bw-line:last-child span:last-child{color:var(--red)}
.hd-bw-line.addon-line span:last-child{color:var(--red)}
.hd-bw-cta-wrap{padding:0 18px 16px}
.hd-bw-cta{width:100%;background:var(--red);color:#fff;border:none;border-radius:10px;padding:13px;font-family:'Poppins',sans-serif;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;gap:7px;cursor:pointer;box-shadow:0 4px 18px rgba(227,30,36,.35);transition:all .2s}
.hd-bw-cta:hover{background:#b91c1c;transform:translateY(-1px)}
.hd-bw-trust{background:#f7f8fa;border-radius:10px;padding:10px 14px;display:flex;flex-direction:column;gap:5px;margin:0 18px 4px}
.hd-bw-trust-item{display:flex;align-items:center;gap:6px;font-size:11px;color:#64748b}
.hd-bw-trust-item i{color:#059669;font-size:10px}

/* Cancel card (reuse from hotel_detials.css) */
.hd-cancel-card{background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:14px 16px}
.hd-cancel-card h5{font-size:12px;font-weight:800;color:#059669;display:flex;align-items:center;gap:5px;margin-bottom:5px}
.hd-cancel-card p{font-size:11px;color:#166534;line-height:1.65}

/* Help card (reuse from hotel_detials.css) */
.hd-help-card{background:#fff;border:1.5px solid #e8eaed;border-radius:14px;padding:18px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.06)}
.hd-help-card .hd-help-ico{font-size:28px;color:var(--red);margin-bottom:8px}
.hd-help-card h4{font-size:14px;font-weight:800;color:#0c0c0e;margin-bottom:3px}
.hd-help-card p{font-size:11px;color:#94a3b8;margin-bottom:12px}
.hd-help-btns{display:flex;gap:8px}
.hd-h-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:5px;border-radius:10px;padding:9px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;border:1.5px solid}
.hd-h-btn.call{border-color:#fecaca;color:var(--red);background:#fff}
.hd-h-btn.call:hover{background:#fff1f1}
.hd-h-btn.wa{border-color:#86efac;color:#15803d;background:#fff}
.hd-h-btn.wa:hover{background:#f0fdf4}

/* Addons */
.hd-addon-item{display:flex;align-items:center;gap:8px;padding:9px 10px;border:1.5px solid #e8eaed;border-radius:10px;margin-bottom:6px;cursor:pointer;transition:all .15s;background:#fff}
.hd-addon-item:last-child{margin-bottom:0}
.hd-addon-item:hover,.hd-addon-item.checked{border-color:var(--red);background:#fff1f1}
.hd-addon-chk{width:15px;height:15px;accent-color:var(--red);flex-shrink:0}
.hd-addon-ico{width:30px;height:30px;background:#f7f8fa;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.hd-addon-ico i{font-size:14px;color:var(--red)}
.hd-addon-info{flex:1;min-width:0}
.hd-addon-info strong{display:block;font-size:12px;font-weight:700;color:#0c0c0e}
.hd-addon-info small{font-size:10px;color:#94a3b8}
.hd-addon-price{font-size:12px;font-weight:700;color:var(--red);white-space:nowrap}
.hd-bw-divider{height:1px;background:#f3f4f6;margin:6px 0}
.hd-addons-hdr{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.hd-addons-kicker{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;display:block;margin-bottom:2px}
.hd-addons-title{font-size:13px;font-weight:700;color:#0c0c0e}
.hd-addons-opt{font-size:10px;font-weight:700;color:#94a3b8;background:#f7f8fa;border:1px solid #e8eaed;padding:3px 9px;border-radius:20px}

/* Mobile bottom bar */
.hd-mob-bar{display:none;position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1.5px solid #e8eaed;padding:12px 16px;z-index:500;box-shadow:0 -4px 16px rgba(0,0,0,.1);align-items:center;justify-content:space-between}
.hd-mob-price{font-family:'Poppins',sans-serif;font-size:20px;font-weight:900;color:var(--red);line-height:1}
.hd-mob-sub{font-size:11px;color:#94a3b8;margin-top:2px}
.hd-mob-cta{background:var(--red);color:#fff;font-size:13px;font-weight:800;padding:12px 22px;border-radius:10px;border:none;cursor:pointer;font-family:'Poppins',sans-serif}

/* Mobile drawer */
.hd-drawer{display:none;position:fixed;inset:0;z-index:600}
.hd-drawer-overlay{position:absolute;inset:0;background:rgba(0,0,0,.45)}
.hd-drawer-panel{position:absolute;bottom:0;left:0;right:0;background:#fff;border-radius:22px 22px 0 0;max-height:90vh;overflow-y:auto;transform:translateY(100%);transition:transform .3s ease}
.hd-drawer-panel.open{transform:translateY(0)}
.hd-drawer-handle{width:40px;height:4px;background:#e8eaed;border-radius:2px;margin:14px auto 10px}
.hd-drawer-title{font-size:15px;font-weight:800;color:#0c0c0e;padding:0 18px 12px;border-bottom:1px solid #f3f4f6;font-family:'Poppins',sans-serif}

/* Responsive */
@media(max-width:900px){
  .hd-body{grid-template-columns:1fr}
  .hd-right{display:none}
  .hd-mob-bar{display:flex}
  .hd-drawer{display:block}
  body{padding-bottom:72px}
  .hd-gallery{grid-template-rows:180px 90px}
  .hd-infobar{flex-direction:column}
  .hd-perks-grid,.hd-pol-grid,.hd-rest-grid{grid-template-columns:1fr}
}
@media(max-width:640px){
  .hd-wrap{padding:0 12px 80px}
  .hd-gallery{grid-template-columns:1fr;grid-template-rows:200px}
  .hd-g-thumb{display:none}
  .hd-gallery .hd-g-thumb:last-child{display:block}
  .hd-name{font-size:18px}
  .hd-body{padding:14px 12px 0}
  .hd-infobar{padding:14px}
}
</style>

{{-- ── BREADCRUMB ── --}}
@includeIf('frontend.partials.breadcrumb', [
  'breadcrumb' => $bgImg->breadcrumb,
  'title' => strlen($hotel->title ?? '') > 35 ? mb_substr($hotel->title, 0, 35, 'utf-8').'...' : ($hotel->title ?? ''),
])

<div class="hd-wrap">

  {{-- ── GALLERY ── --}}
  <div class="hd-gallery">
    <div class="hd-g-main" onclick="openGallery(0)">
      @if($galleryImages->count() > 0)
        <img src="{{ asset('assets/img/hotel/hotel-gallery/'.$galleryImages->first()->image) }}" alt="{{ $hotel->title }}">
      @else
        <img src="{{ asset('assets/img/hotel/logo/'.$hotel->logo) }}" alt="{{ $hotel->title }}">
      @endif
    </div>
    @if($galleryImages->count() > 1)
    <div class="hd-g-thumb" onclick="openGallery(1)">
      <img src="{{ asset('assets/img/hotel/hotel-gallery/'.$galleryImages->skip(1)->first()->image) }}" alt="{{ $hotel->title }}">
    </div>
    @endif
    @if($galleryImages->count() > 2)
    <div class="hd-g-thumb" onclick="openGallery(2)">
      <img src="{{ asset('assets/img/hotel/hotel-gallery/'.$galleryImages->skip(2)->first()->image) }}" alt="{{ $hotel->title }}">
      @if($galleryImages->count() > 3)
      <div class="hd-g-more"><i class="fas fa-images"></i> +{{ $galleryImages->count() - 3 }} photos</div>
      @endif
    </div>
    @endif
  </div>

  {{-- ── HOTEL INFO BAR ── --}}
  <div class="hd-infobar">
    <div>
      <div class="hd-name">{{ $hotel->title }}</div>
      <div class="hd-meta">
        @if(!empty($hotel->categoryName))
        <span class="hd-cat">{{ $hotel->categoryName }}</span>
        @endif
        <span class="hd-loc"><i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}</span>
        @if($hotel->stars > 0)
        <span class="hd-stars-row">{{ str_repeat('★', $hotel->stars) }}{{ str_repeat('☆', 5 - $hotel->stars) }}</span>
        @endif
        @if($hotel->average_rating > 0)
        <span class="hd-rating-badge"><i class="fas fa-star" style="font-size:10px"></i> {{ number_format($hotel->average_rating, 1) }} · {{ totalHotelReview($hotel->id) }} reviews</span>
        @endif
      </div>
    </div>
    <div class="hd-infobar-actions">
      <a class="hd-action-btn" href="{{ $checkWishList == false ? route('addto.wishlist.hotel', $hotel->id) : route('remove.wishlist.hotel', $hotel->id) }}">
        <i class="fa{{ $checkWishList ? 's' : 'l' }} fa-bookmark"></i>
        {{ $checkWishList ? __('Saved') : __('Save') }}
      </a>
      <button class="hd-action-btn" onclick="shareHotel()"><i class="fas fa-share-alt"></i> Share</button>
    </div>
  </div>

</div>{{-- close hd-wrap before sticky tab nav --}}

{{-- ── STICKY TAB NAV ── --}}
<div class="hd-tab-nav">
  <div class="hd-tab-nav-inner">
    <button class="hd-tab active" onclick="hdTab('sec-intro',this)"><i class="fas fa-circle-info"></i> Intro</button>
    @if($hotelAmenities && count($hotelAmenities) > 0)
    <button class="hd-tab" onclick="hdTab('sec-facilities',this)"><i class="fas fa-th-large"></i> Facilities</button>
    @endif
    @if($hotelPerks->count() > 0)
    <button class="hd-tab" onclick="hdTab('sec-perks',this)"><i class="fas fa-gem"></i> Perks</button>
    @endif
    @if($hotelPolicies->count() > 0)
    <button class="hd-tab" onclick="hdTab('sec-policies',this)"><i class="fas fa-clipboard-list"></i> Policies</button>
    @endif
    @if(count($hotelRestrictions) > 0)
    <button class="hd-tab" onclick="hdTab('sec-restrictions',this)"><i class="fas fa-ban"></i> Restriction</button>
    @endif
  </div>
</div>

<div class="hd-wrap">
<div class="hd-body">

{{-- ── LEFT COLUMN ── --}}
<div>

  {{-- INTRO --}}
  <div class="hd-scard" id="sec-intro">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-circle-info"></i></div>
      <div><span class="hd-scard-eyebrow">Overview</span><div class="hd-scard-title">About this hotel</div></div>
    </div>
    <div class="hd-scard-body tinymce-content">{!! $hotel->description !!}</div>
  </div>

  {{-- FACILITIES --}}
  @if($hotelAmenities && count($hotelAmenities) > 0)
  <div class="hd-scard" id="sec-facilities">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-th-large"></i></div>
      <div><span class="hd-scard-eyebrow">Amenities</span><div class="hd-scard-title">Things that make the stay better</div></div>
    </div>
    <div class="hd-scard-body">
      <div class="hd-am-grid">
        @foreach($hotelAmenities as $amenityId)
          @php $amin = App\Models\Amenitie::find($amenityId); @endphp
          @if($amin)
          <div class="hd-am-item"><i class="{{ $amin->icon }}"></i> {{ $amin->title }}</div>
          @endif
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- PERKS --}}
  @if($hotelPerks->count() > 0)
  <div class="hd-scard" id="sec-perks">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-gem"></i></div>
      <div><span class="hd-scard-eyebrow">Special perks</span><div class="hd-scard-title">Why guests love staying here</div></div>
    </div>
    <div class="hd-scard-body">
      <div class="hd-perks-grid">
        @foreach($hotelPerks as $perk)
        <div class="hd-perk-item"><i class="{{ $perk->icon }}"></i> {{ $perk->title }}</div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- POLICIES --}}
  @if($hotelPolicies->count() > 0)
  <div class="hd-scard" id="sec-policies">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-clipboard-list"></i></div>
      <div><span class="hd-scard-eyebrow">Policies</span><div class="hd-scard-title">What you must know</div></div>
    </div>
    <div class="hd-scard-body">
      <div class="hd-pol-grid">
        @foreach($hotelPolicies as $pol)
        <div class="hd-pol-item">
          <div class="hd-pol-label"><i class="{{ $pol->icon }}"></i> {{ $pol->title }}</div>
          @if($pol->description)
          <div class="hd-pol-val">{{ $pol->description }}</div>
          @endif
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- RESTRICTIONS --}}
  @if(count($hotelRestrictions) > 0)
  <div class="hd-scard" id="sec-restrictions">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-ban"></i></div>
      <div><span class="hd-scard-eyebrow">Restrictions</span><div class="hd-scard-title">What to follow</div></div>
    </div>
    <div class="hd-scard-body">
      <div class="hd-rest-grid">
        @foreach($hotelRestrictions as $restr)
        @php $rtype = $restrMap[$restr->id] ?? 'not_allowed'; @endphp
        <div class="hd-rest-item">
          <span class="hd-rest-icon">{{ $restr->icon }}</span>
          <span class="hd-rest-name">{{ $restr->title }}</span>
          <span class="hd-rest-badge badge-{{ $rtype }}">
            @if($rtype === 'allowed') ✅ Allowed
            @elseif($rtype === 'limited') ⚠️ Limited
            @else 🚫 Not Allowed @endif
          </span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- LOCATION --}}
  <div class="hd-scard" id="sec-location">
    <div class="hd-scard-head">
      <div class="hd-scard-icon"><i class="fas fa-map-marker-alt"></i></div>
      <div><span class="hd-scard-eyebrow">Location</span><div class="hd-scard-title">Where to find us</div></div>
    </div>
    <div class="hd-scard-body">
      <div class="hd-map">
        <i class="fas fa-map"></i>
        <span style="text-align:center;max-width:340px">{{ $hotel->address }}</span>
      </div>
      <div style="margin-top:10px;display:flex;align-items:flex-start;gap:7px;font-size:13px;color:#64748b">
        <i class="fas fa-map-marker-alt" style="color:var(--red);margin-top:2px;flex-shrink:0"></i>
        <span><strong style="color:#0c0c0e">{{ $hotel->title }}</strong>, {{ $hotel->address }}</span>
      </div>
    </div>
  </div>

</div>{{-- end left col --}}

{{-- ── RIGHT COLUMN ── --}}
<div class="hd-right">

  {{-- BOOKING WIDGET --}}
  <div class="hd-bwidget">
    <div class="hd-bw-top">
      <div class="hd-bw-from">Book your stay</div>
      <div style="font-family:'Poppins',sans-serif;font-size:16px;font-weight:800;color:#fff;margin-bottom:3px">Instant confirmation</div>
      <div class="hd-bw-from-note">Select a room below to see pricing</div>
    </div>
    <div class="hd-bw-form">
      <div>
        <span class="hd-field-lbl">Select Duration</span>
        <div class="hd-bw-slots" id="hdSlots">
          @foreach($rooms as $room)
            @php
              $prices = App\Models\HourlyRoomPrice::where('room_id', $room->id)
                ->join('booking_hours','hourly_room_prices.hour_id','=','booking_hours.id')
                ->orderBy('booking_hours.serial_number')
                ->select('hourly_room_prices.*','booking_hours.serial_number')
                ->get();
            @endphp
            @foreach($prices as $price)
              @if(!in_array($price->hour, array_column(isset($shownSlots) ? $shownSlots : [], 'hour')))
              @php $shownSlots[] = ['hour' => $price->hour, 'price' => $price->price]; @endphp
              <div class="hd-bw-slot {{ $loop->parent->first && $loop->first ? 'active' : '' }}"
                onclick="hdSelectSlot(this,{{ $price->price }},'{{ $price->hour }}')"
                data-price="{{ $price->price }}" data-hour="{{ $price->hour }}">
                <span class="hd-bw-slot-hr">{{ $price->hour == 24 || strtolower($price->hour) == 'full day' ? 'Full Day' : $price->hour.' Hrs' }}</span>
                <span class="hd-bw-slot-pr">{{ $fmt($price->price) }}</span>
              </div>
              @endif
            @endforeach
          @endforeach
        </div>
      </div>
      <div>
        <span class="hd-field-lbl">Check-in Date</span>
        <input type="date" class="hd-bw-inp" id="hdCheckIn" min="{{ date('Y-m-d') }}" onchange="hdCalc()">
      </div>
      <div id="hdCheckOutWrap">
        <span class="hd-field-lbl">Check-out Date</span>
        <input type="date" class="hd-bw-inp" id="hdCheckOut" min="{{ date('Y-m-d', strtotime('+1 day')) }}" onchange="hdCalc()">
      </div>
      <div>
        <span class="hd-field-lbl">Check-in Time</span>
        <input type="time" class="hd-bw-inp" id="hdTime" value="10:00">
      </div>
      <div class="hd-bw-row2">
        <div>
          <span class="hd-field-lbl">Rooms</span>
          <select class="hd-bw-inp" id="hdRooms" onchange="hdCalc()">
            <option value="1">1 Room</option>
            <option value="2" selected>2 Rooms</option>
            <option value="3">3 Rooms</option>
          </select>
        </div>
        <div>
          <span class="hd-field-lbl">Adults</span>
          <select class="hd-bw-inp" id="hdAdults">
            <option value="1">1 Adult</option>
            <option value="2" selected>2 Adults</option>
            <option value="3">3 Adults</option>
          </select>
        </div>
      </div>

      {{-- Add-ons if hotel has any --}}
      @if($rooms->count() > 0)
      @php
        $firstRoom = $rooms->first();
        $additionalServices = json_decode($firstRoom->additional_service ?? '{}', true) ?? [];
      @endphp
      @if(count($additionalServices) > 0)
      <div>
        <div class="hd-bw-divider"></div>
        <div class="hd-addons-hdr">
          <div><span class="hd-addons-kicker">Make your stay special</span><span class="hd-addons-title">Add-ons</span></div>
          <span class="hd-addons-opt">Optional</span>
        </div>
        @foreach($additionalServices as $svcId => $charge)
          @php
            $svc = App\Models\AdditionalServiceContent::where('language_id', $language->id)
              ->where('additional_service_id', $svcId)->first();
          @endphp
          @if($svc)
          <label class="hd-addon-item" onclick="hdToggleAddon(this)">
            <input type="checkbox" class="hd-addon-chk" data-price="{{ $charge }}">
            <div class="hd-addon-ico"><i class="fas fa-gift"></i></div>
            <div class="hd-addon-info">
              <strong>{{ $svc->title }}</strong>
              <small>{{ $svc->description ?? '' }}</small>
            </div>
            <span class="hd-addon-price">+{{ $fmt($charge) }}</span>
          </label>
          @endif
        @endforeach
        <div class="hd-bw-divider"></div>
      </div>
      @endif
      @endif

      <div class="hd-bw-summary" id="hdSummary">
        <div class="hd-bw-line"><span id="hdRoomLabel">Room price</span><span id="hdRoomTotal">—</span></div>
        <div class="hd-bw-line addon-line" id="hdAddonRow" style="display:none"><span>Add-ons</span><span id="hdAddonTotal">—</span></div>
        <div class="hd-bw-line"><span>GST (5%)</span><span id="hdGst">—</span></div>
        <div class="hd-bw-line"><span>Total payable</span><span id="hdTotal">—</span></div>
      </div>
    </div>

    <div class="hd-bw-trust">
      <div class="hd-bw-trust-item"><i class="fas fa-check-circle"></i> Instant booking confirmation</div>
      <div class="hd-bw-trust-item"><i class="fas fa-shield-alt"></i> Secure payment via Razorpay</div>
      <div class="hd-bw-trust-item"><i class="fas fa-headset"></i> 24/7 customer support</div>
    </div>

    <div class="hd-bw-cta-wrap" style="padding:12px 18px 16px">
      <button class="hd-bw-cta" onclick="hdGoToRoom()">
        <i class="fas fa-arrow-right"></i> View Rooms & Book
      </button>
    </div>
  </div>

  {{-- CANCELLATION CARD --}}
  <div class="hd-cancel-card">
    <h5><i class="fas fa-check-circle"></i> Free Cancellation Available</h5>
    <p>Cancel at least 24 hours before check-in for a full refund. Booked by mistake? Cancel within 15 minutes of booking for a full refund. Refund processed within 5–7 business days.</p>
  </div>

  {{-- HELP CARD --}}
  <div class="hd-help-card">
    <div class="hd-help-ico"><i class="fas fa-headset"></i></div>
    <h4>Need Help?</h4>
    <p>Our support team is available 24/7 for you</p>
    <div class="hd-help-btns">
      <button class="hd-h-btn call"><i class="fas fa-phone"></i> Call Us</button>
      <button class="hd-h-btn wa"><i class="fab fa-whatsapp"></i> WhatsApp</button>
    </div>
  </div>

</div>{{-- end right col --}}

</div>{{-- end hd-body --}}
</div>{{-- end hd-wrap --}}

{{-- ── MOBILE FIXED BAR ── --}}
<div class="hd-mob-bar" id="hdMobBar">
  <div>
    <div class="hd-mob-price" id="hdMobPrice">View Rooms</div>
    <div class="hd-mob-sub">Tap to check prices & book</div>
  </div>
  <button class="hd-mob-cta" onclick="hdOpenDrawer()">
    <i class="fas fa-calendar-check"></i> Book Now
  </button>
</div>

{{-- ── MOBILE DRAWER ── --}}
<div class="hd-drawer" id="hdDrawer" onclick="hdCloseDrawerOutside(event)">
  <div class="hd-drawer-overlay"></div>
  <div class="hd-drawer-panel" id="hdPanel">
    <div class="hd-drawer-handle"></div>
    <div class="hd-drawer-title">Book your stay</div>
    <div style="padding:14px 18px">
      <div style="margin-bottom:12px">
        <span class="hd-field-lbl">Select Duration</span>
        <div class="hd-bw-slots" id="hdMobSlots">
          @php $shownMob = []; @endphp
          @foreach($rooms as $room)
            @php
              $mprices = App\Models\HourlyRoomPrice::where('room_id', $room->id)
                ->join('booking_hours','hourly_room_prices.hour_id','=','booking_hours.id')
                ->orderBy('booking_hours.serial_number')
                ->select('hourly_room_prices.*','booking_hours.serial_number')
                ->get();
            @endphp
            @foreach($mprices as $mp)
              @if(!in_array($mp->hour, $shownMob))
              @php $shownMob[] = $mp->hour; @endphp
              <div class="hd-bw-slot {{ $loop->parent->first && $loop->first ? 'active' : '' }}"
                onclick="hdMobSlot(this,{{ $mp->price }},'{{ $mp->hour }}')"
                data-price="{{ $mp->price }}" data-hour="{{ $mp->hour }}">
                <span class="hd-bw-slot-hr">{{ $mp->hour == 24 ? 'Full Day' : $mp->hour.' Hrs' }}</span>
                <span class="hd-bw-slot-pr">{{ $fmt($mp->price) }}</span>
              </div>
              @endif
            @endforeach
          @endforeach
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px">
        <div><span class="hd-field-lbl">Check-in</span><input type="date" class="hd-bw-inp" id="hdMCheckIn" min="{{ date('Y-m-d') }}" onchange="hdMCalc()"></div>
        <div><span class="hd-field-lbl">Check-out</span><input type="date" class="hd-bw-inp" id="hdMCheckOut" onchange="hdMCalc()"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px">
        <div><span class="hd-field-lbl">Time</span><input type="time" class="hd-bw-inp" value="10:00"></div>
        <div><span class="hd-field-lbl">Rooms</span>
          <select class="hd-bw-inp" id="hdMRooms" onchange="hdMCalc()">
            <option value="1">1 Room</option>
            <option value="2" selected>2 Rooms</option>
            <option value="3">3 Rooms</option>
          </select>
        </div>
      </div>
      <div class="hd-bw-summary" style="margin-bottom:12px">
        <div class="hd-bw-line"><span id="hdMLabel">Room price</span><span id="hdMRoom">—</span></div>
        <div class="hd-bw-line"><span>GST (5%)</span><span id="hdMGst">—</span></div>
        <div class="hd-bw-line"><span>Total</span><span id="hdMTotal">—</span></div>
      </div>
      <button class="hd-bw-cta" style="margin-bottom:10px" onclick="hdGoToRoom()">
        <i class="fas fa-arrow-right"></i> View Rooms & Book
      </button>
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 12px;font-size:11px;color:#166534">
        <i class="fas fa-check-circle" style="color:#059669"></i>
        Free cancellation 24h before check-in · Refund in 5–7 days
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
var _hdPrice = 0, _hdSlot = '', _hdNights = 1, _hdRooms = 2, _hdAddon = 0;
var _hdMPrice = 0, _hdMSlot = '', _hdMNights = 1;

// Set today as default check-in
window.addEventListener('DOMContentLoaded', function() {
  var today = new Date();
  var d3    = new Date(); d3.setDate(today.getDate() + 1);
  var fmt   = d => d.toISOString().split('T')[0];
  var ci = document.getElementById('hdCheckIn');
  var co = document.getElementById('hdCheckOut');
  var mci = document.getElementById('hdMCheckIn');
  var mco = document.getElementById('hdMCheckOut');
  if(ci) ci.value = fmt(today);
  if(co) co.value = fmt(d3);
  if(mci) mci.value = fmt(today);
  if(mco) mco.value = fmt(d3);

  // Init first slot
  var firstSlot = document.querySelector('#hdSlots .hd-bw-slot.active');
  if(firstSlot) {
    _hdPrice = parseFloat(firstSlot.dataset.price)||0;
    _hdSlot  = firstSlot.dataset.hour;
    hdCalc();
  }
  var firstMobSlot = document.querySelector('#hdMobSlots .hd-bw-slot.active');
  if(firstMobSlot) {
    _hdMPrice = parseFloat(firstMobSlot.dataset.price)||0;
    _hdMSlot  = firstMobSlot.dataset.hour;
    hdMCalc();
  }
});

function hdSelectSlot(el, price, hour) {
  document.querySelectorAll('#hdSlots .hd-bw-slot').forEach(s=>s.classList.remove('active'));
  el.classList.add('active');
  _hdPrice = price; _hdSlot = hour;
  var cow = document.getElementById('hdCheckOutWrap');
  if(cow) cow.style.display = (hour==24||hour=='Full Day') ? '' : 'none';
  hdCalc();
}

function hdCalc() {
  var ci = document.getElementById('hdCheckIn')?.value;
  var co = document.getElementById('hdCheckOut')?.value;
  var rm = parseInt(document.getElementById('hdRooms')?.value||2);
  _hdRooms = rm;
  var isFullDay = (_hdSlot==24||String(_hdSlot).toLowerCase().includes('full'));
  _hdNights = (isFullDay && ci && co) ? Math.max(1, Math.round((new Date(co)-new Date(ci))/86400000)) : 1;
  var sub   = _hdPrice * _hdNights * rm;
  var gst   = Math.round(sub * 0.05);
  var grand = sub + _hdAddon + gst;
  var lbl   = isFullDay ? 'Room × '+_hdNights+' night'+((_hdNights>1)?'s':'')+' × '+rm
                        : 'Room × '+rm+' room'+((rm>1)?'s':'');
  var sym = '{{ $sym }}';
  document.getElementById('hdRoomLabel').textContent = lbl;
  document.getElementById('hdRoomTotal').textContent = sym + sub.toLocaleString();
  document.getElementById('hdGst').textContent       = sym + gst.toLocaleString();
  document.getElementById('hdTotal').textContent     = sym + grand.toLocaleString();
  document.getElementById('hdMobPrice').textContent  = sym + grand.toLocaleString();
  var ar = document.getElementById('hdAddonRow');
  if(ar) ar.style.display = _hdAddon>0 ? 'flex' : 'none';
  if(_hdAddon>0) document.getElementById('hdAddonTotal').textContent = sym + _hdAddon.toLocaleString();
}

function hdToggleAddon(label) {
  setTimeout(function(){
    var cb = label.querySelector('.hd-addon-chk');
    cb.checked ? label.classList.add('checked') : label.classList.remove('checked');
    _hdAddon = 0;
    document.querySelectorAll('.hd-addon-chk:checked').forEach(function(c){
      _hdAddon += parseFloat(c.dataset.price||0);
    });
    hdCalc();
  }, 10);
}

// Mobile drawer
function hdMobSlot(el, price, hour) {
  document.querySelectorAll('#hdMobSlots .hd-bw-slot').forEach(s=>s.classList.remove('active'));
  el.classList.add('active');
  _hdMPrice = price; _hdMSlot = hour;
  hdMCalc();
}
function hdMCalc() {
  var ci = document.getElementById('hdMCheckIn')?.value;
  var co = document.getElementById('hdMCheckOut')?.value;
  var rm = parseInt(document.getElementById('hdMRooms')?.value||2);
  var isFullDay = (_hdMSlot==24||String(_hdMSlot).toLowerCase().includes('full'));
  _hdMNights = (isFullDay && ci && co) ? Math.max(1, Math.round((new Date(co)-new Date(ci))/86400000)) : 1;
  var sub = _hdMPrice * _hdMNights * rm;
  var gst = Math.round(sub*0.05);
  var grand = sub+gst;
  var sym = '{{ $sym }}';
  var lbl = isFullDay ? 'Room × '+_hdMNights+' night'+((_hdMNights>1)?'s':'')+' × '+rm
                      : 'Room × '+rm+' room'+((rm>1)?'s':'');
  document.getElementById('hdMLabel').textContent = lbl;
  document.getElementById('hdMRoom').textContent  = sym + sub.toLocaleString();
  document.getElementById('hdMGst').textContent   = sym + gst.toLocaleString();
  document.getElementById('hdMTotal').textContent = sym + grand.toLocaleString();
  document.getElementById('hdMobPrice').textContent = sym + grand.toLocaleString();
}
function hdOpenDrawer() {
  document.getElementById('hdDrawer').style.display = 'block';
  setTimeout(()=>document.getElementById('hdPanel').classList.add('open'), 10);
  document.body.style.overflow = 'hidden';
}
function hdCloseDrawer() {
  document.getElementById('hdPanel').classList.remove('open');
  setTimeout(()=>{ document.getElementById('hdDrawer').style.display='none'; document.body.style.overflow=''; }, 300);
}
function hdCloseDrawerOutside(e) {
  if(e.target.classList.contains('hd-drawer-overlay')) hdCloseDrawer();
}

// Tab scroll
function hdTab(id, el) {
  var offset = 64 + 52;
  var target = document.getElementById(id);
  if(!target) return;
  var top = target.getBoundingClientRect().top + window.scrollY - offset - 10;
  window.scrollTo({ top: top, behavior: 'smooth' });
  document.querySelectorAll('.hd-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
}
window.addEventListener('scroll', function() {
  var sections = ['sec-intro','sec-facilities','sec-perks','sec-policies','sec-restrictions','sec-location'];
  var tabs = document.querySelectorAll('.hd-tab');
  var offset = 140;
  var curr = '';
  sections.forEach(id => {
    var el = document.getElementById(id);
    if(el && el.getBoundingClientRect().top < offset) curr = id;
  });
  if(curr) {
    tabs.forEach((t,i) => {
      t.classList.toggle('active', ['sec-intro','sec-facilities','sec-perks','sec-policies','sec-restrictions','sec-location'][i] === curr);
    });
  }
});

// Go to rooms
function hdGoToRoom() {
  window.location.href = '{{ route("frontend.rooms", ["hotelId" => $hotel->id]) }}';
}

// Share
function shareHotel() {
  if(navigator.share) {
    navigator.share({ title: '{{ $hotel->title }}', url: window.location.href });
  } else {
    navigator.clipboard.writeText(window.location.href).then(function(){
      alert('Link copied!');
    });
  }
}
</script>
@endsection
