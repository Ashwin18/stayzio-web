@extends('vendors.layout')
@section('section','Account')
@section('page','Buy / Extend Plan')
@php Config::set('app.timezone', App\Models\BasicSettings\Basic::first()->timezone); @endphp
@php
  $vendor = Auth::guard('vendor')->user();
  $currPkg = \App\Http\Helpers\VendorPermissionHelper::currentPackagePermission($vendor->id);
  $currMemb = \App\Http\Helpers\VendorPermissionHelper::currMembOrPending($vendor->id);
  $nextPkg = \App\Http\Helpers\VendorPermissionHelper::nextPackage($vendor->id);
  $nextMemb = \App\Http\Helpers\VendorPermissionHelper::nextMembership($vendor->id);
  $hasPending = \App\Http\Helpers\VendorPermissionHelper::hasPendingMembership(Auth::id());
  $sym = $settings->base_currency_symbol ?? '₹';
@endphp

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Plans & Pricing</h2>
    <p>Manage your subscription and access limits</p>
  </div>
</div>

{{-- Current plan status --}}
@if($currPkg)
<div style="display:flex;gap:14px;margin-bottom:20px;flex-wrap:wrap">
  <div style="flex:1;min-width:200px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:14px 18px">
    <div style="font-size:11px;color:var(--green);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Current Plan</div>
    <div style="font-size:16px;font-weight:700;color:var(--text)">{{ $currPkg->title }}</div>
    <div style="font-size:12px;color:var(--muted);margin-top:3px">
      {{ ucfirst($currPkg->term) }}
      · Expires: {{ $currPkg->term === 'lifetime' ? 'Lifetime' : ($currMemb ? \Carbon\Carbon::parse($currMemb->expire_date)->format('d M Y') : '—') }}
      @if($currMemb && $currMemb->is_trial) <span class="badge amber no-dot" style="margin-left:4px">Trial</span> @endif
    </div>
  </div>
  @if($nextPkg)
  <div style="flex:1;min-width:200px;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.2);border-radius:10px;padding:14px 18px">
    <div style="font-size:11px;color:var(--blue);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Next Plan</div>
    <div style="font-size:16px;font-weight:700;color:var(--text)">{{ $nextPkg->title }}</div>
    <div style="font-size:12px;color:var(--muted);margin-top:3px">
      {{ ucfirst($nextPkg->term) }}
      @if($nextMemb && $nextMemb->status == 0) <span class="badge amber no-dot" style="margin-left:4px">Pending approval</span> @endif
    </div>
  </div>
  @endif
</div>
@else
<div style="display:flex;align-items:center;gap:12px;padding:14px 18px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:10px;margin-bottom:20px;font-size:13px;color:var(--amber)">
  <i class="ti ti-alert-triangle" style="font-size:18px;flex-shrink:0"></i>
  <span>No active plan. Please purchase a package to start adding hotels and rooms.</span>
</div>
@endif

@if($hasPending)
<div style="padding:12px 16px;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.2);border-radius:8px;margin-bottom:16px;font-size:13px;color:var(--blue)">
  <i class="ti ti-info-circle"></i> A package request is pending admin approval. You'll be notified via email once it's reviewed.
</div>
@endif

{{-- Package cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px">
  @foreach($packages as $pkg)
  @php
    $isCurrent = $currPkg && $currPkg->id == $pkg->id;
    $isNext    = $nextPkg && $nextPkg->id == $pkg->id;
    $perms     = !empty($pkg->features) ? json_decode($pkg->features, true) : [];
  @endphp
  <div class="pkg-card {{ $isCurrent ? 'featured' : '' }}" style="position:relative">
    @if($pkg->recommended) <div class="pkg-badge">⭐ Recommended</div> @endif
    @if($isCurrent) <div class="pkg-badge" style="background:var(--green)">✓ Current</div> @endif
    @if($isNext && !$isCurrent) <div class="pkg-badge" style="background:var(--blue)">Next</div> @endif

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
      <div class="av {{ $isCurrent?'green':($pkg->recommended?'red':'muted') }}" style="width:38px;height:38px;font-size:18px;flex-shrink:0">
        <i class="{{ $pkg->icon ?? 'ti ti-package' }}"></i>
      </div>
      <div>
        <div style="font-size:14px;font-weight:700;color:var(--text)">{{ $pkg->title }}</div>
        <div style="font-size:11px;color:var(--muted)">{{ ucfirst($pkg->term) }}</div>
      </div>
    </div>

    <div class="pkg-price">@if($pkg->price==0)Free @else{{ $sym }}{{ number_format($pkg->price, 0) }}@endif</div>
    <div class="pkg-term">per {{ $pkg->term }}</div>
    <hr class="divider">

    <div class="pkg-feature"><i class="ti ti-building"></i> {{ $pkg->number_of_hotel >= 999999 ? 'Unlimited hotels' : $pkg->number_of_hotel.' hotel'.($pkg->number_of_hotel!=1?'s':'') }}</div>
    <div class="pkg-feature"><i class="ti ti-bed"></i> {{ $pkg->number_of_room >= 999999 ? 'Unlimited rooms' : $pkg->number_of_room.' room'.($pkg->number_of_room!=1?'s':'') }}</div>
    <div class="pkg-feature"><i class="ti ti-calendar-check"></i> {{ $pkg->number_of_bookings >= 999999 ? 'Unlimited bookings' : $pkg->number_of_bookings.' bookings' }}</div>
    <div class="pkg-feature"><i class="ti ti-photo"></i> {{ $pkg->number_of_images_per_hotel >= 999999 ? 'Unlimited' : $pkg->number_of_images_per_hotel }} images/hotel</div>
    @if(is_array($perms) && in_array('Support Tickets', $perms))
    <div class="pkg-feature"><i class="ti ti-message-circle"></i> Support Tickets</div>
    @endif
    @if(is_array($perms) && in_array('Add Booking From Dashboard', $perms))
    <div class="pkg-feature"><i class="ti ti-plus"></i> Add Booking from Dashboard</div>
    @endif
    @if(!empty($pkg->custom_features))
      @foreach(explode("\n", $pkg->custom_features) as $f)
        @if(trim($f))<div class="pkg-feature"><i class="ti ti-check"></i> {{ trim($f) }}</div>@endif
      @endforeach
    @endif

    <hr class="divider">

    @if($package_count < 2 && !$hasPending)
      @if($isCurrent && $pkg->term != 'lifetime')
        <a href="{{ route('vendor.plan.extend.checkout', $pkg->id) }}" class="btn btn-success btn-sm btn-block"><i class="ti ti-refresh"></i> Extend Plan</a>
      @elseif(!$isCurrent)
        <a href="{{ route('vendor.plan.extend.checkout', $pkg->id) }}" class="btn btn-primary btn-sm btn-block"><i class="ti ti-shopping-cart"></i> Buy Now</a>
      @else
        <div style="text-align:center;font-size:12px;color:var(--green);padding:6px 0"><i class="ti ti-circle-check"></i> Active Plan</div>
      @endif
    @elseif($isCurrent)
      <div style="text-align:center;font-size:12px;color:var(--green);padding:6px 0"><i class="ti ti-circle-check"></i> Active Plan</div>
    @else
      <div style="text-align:center;font-size:11px;color:var(--muted);padding:6px 0">Max 2 packages allowed</div>
    @endif
  </div>
  @endforeach
</div>
@endsection
