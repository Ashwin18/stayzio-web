@extends('admin.layout')
@section('section', 'Property Management')
@section('page', 'Property Submissions')

@section('content')
<style>
.ps-card{background:var(--navy2);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:10px;display:flex;align-items:flex-start;gap:14px;transition:.15s}
.ps-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.06)}
.ps-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px}
.ps-info{flex:1;min-width:0}
.ps-name{font-size:14px;font-weight:800;color:var(--text);margin-bottom:2px}
.ps-meta{font-size:11px;color:var(--muted);display:flex;flex-wrap:wrap;gap:8px;margin-bottom:6px}
.ps-meta span{display:flex;align-items:center;gap:3px}
.ps-prices{display:flex;gap:6px}
.ps-price{font-size:10px;padding:3px 8px;background:var(--navy3);border:1px solid var(--border);border-radius:4px}
.ps-price b{color:var(--red)}
.ps-badge{padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;flex-shrink:0}
.ps-stat{background:var(--navy2);border:1px solid var(--border);border-radius:10px;padding:14px;text-align:center}
.ps-stat-val{font-size:24px;font-weight:900;color:var(--text)}
.ps-stat-lbl{font-size:11px;color:var(--muted);font-weight:600;margin-top:2px}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2><i class="ti ti-building" style="color:var(--red)"></i> Property Submissions</h2>
    <p>Review and approve vendor property registrations</p>
  </div>
</div>

@if(Session::has('success'))
<div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px">
  <div class="ps-stat">
    <div class="ps-stat-val">{{ $counts['total'] }}</div>
    <div class="ps-stat-lbl">Total</div>
  </div>
  <div class="ps-stat">
    <div class="ps-stat-val" style="color:#d97706">{{ $counts['pending'] }}</div>
    <div class="ps-stat-lbl">Pending Review</div>
  </div>
  <div class="ps-stat">
    <div class="ps-stat-val" style="color:#059669">{{ $counts['live'] }}</div>
    <div class="ps-stat-lbl">Live</div>
  </div>
  <div class="ps-stat">
    <div class="ps-stat-val" style="color:#dc2626">{{ $counts['rejected'] }}</div>
    <div class="ps-stat-lbl">Rejected</div>
  </div>
</div>

@if($properties->count() === 0)
<div class="card">
  <div style="text-align:center;padding:48px;color:var(--muted)">
    <i class="ti ti-building-community" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3"></i>
    No property submissions yet. Vendors will submit properties after registration.
  </div>
</div>
@else

@foreach(['pending'=>'Pending Review','approved'=>'Approved','live'=>'Live','rejected'=>'Rejected'] as $status => $label)
@php $group = $properties->where('status', $status); @endphp
@if($group->count() > 0)
<div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin:14px 0 8px">
  {{ $label }} ({{ $group->count() }})
</div>
@foreach($group as $prop)
@php
  $badge = $prop->status_badge;
  $statusColors = ['pending'=>'#d97706','live'=>'#059669','rejected'=>'#dc2626','approved'=>'#2563eb'];
  $iconBg = ($statusColors[$prop->status] ?? '#64748b') . '15';
@endphp
<div class="ps-card">
  <div class="ps-icon" style="background:{{ $iconBg }};color:{{ $statusColors[$prop->status] ?? '#64748b' }}">
    <i class="ti ti-building"></i>
  </div>
  <div class="ps-info">
    <div class="ps-name">{{ $prop->hotel_name }}</div>
    <div class="ps-meta">
      <span><i class="ti ti-user"></i> {{ $prop->vendor_name }}</span>
      <span><i class="ti ti-map-pin"></i> {{ $prop->city }}</span>
      <span><i class="ti ti-bed"></i> {{ $prop->room_type }} · {{ $prop->total_rooms }} rooms</span>
      <span><i class="ti ti-home"></i> {{ ucfirst($prop->property_type) }}</span>
      <span><i class="ti ti-calendar"></i> {{ $prop->created_at->format('d M Y, h:i A') }}</span>
    </div>
    <div class="ps-prices">
      <div class="ps-price"><b>₹{{ number_format($prop->price_3hrs) }}</b> / 3hrs</div>
      <div class="ps-price"><b>₹{{ number_format($prop->price_6hrs) }}</b> / 6hrs</div>
      <div class="ps-price"><b>₹{{ number_format($prop->price_fullday) }}</b> / Full Day</div>
    </div>
  </div>
  <span class="ps-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['color'] }}20">
    {{ $badge['label'] }}
  </span>
  <a href="{{ route('admin.property_submissions.show', $prop->id) }}" class="btn btn-secondary btn-xs">
    <i class="ti ti-eye"></i> Review
  </a>
</div>
@endforeach
@endif
@endforeach

@endif
@endsection
