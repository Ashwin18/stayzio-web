@extends('vendors.layout')
@section('section', 'Properties')
@section('page', 'My Properties')

@section('content')
<style>
.prop-card{background:var(--navy2,#fff);border:1px solid var(--border,#e8eaed);border-radius:14px;padding:20px;margin-bottom:12px;display:flex;align-items:flex-start;gap:16px;transition:.15s}
.prop-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.06)}
.prop-icon{width:48px;height:48px;background:rgba(0,184,184,.10);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.prop-icon i{font-size:22px;color:#00A6A6}
.prop-info{flex:1;min-width:0}
.prop-name{font-size:15px;font-weight:800;color:var(--text,#0c0c0e);margin-bottom:3px}
.prop-meta{font-size:11px;color:var(--muted,#64748b);display:flex;flex-wrap:wrap;gap:10px;margin-bottom:8px}
.prop-meta span{display:flex;align-items:center;gap:4px}
.prop-prices{display:flex;gap:8px;flex-wrap:wrap}
.prop-price{padding:4px 10px;background:var(--navy3,#f8fafc);border:1px solid var(--border,#e8eaed);border-radius:6px;font-size:11px}
.prop-price strong{color:#00A6A6;font-weight:800}
.prop-badge{padding:4px 12px;border-radius:20px;font-size:10px;font-weight:700;flex-shrink:0}
.prop-actions{display:flex;gap:6px;flex-shrink:0;align-items:center}
.empty-state{text-align:center;padding:60px 20px;color:var(--muted,#64748b)}
.empty-state i{font-size:48px;display:block;margin-bottom:14px;opacity:.3}

@media(max-width:768px){
  .prop-card{display:grid;grid-template-columns:auto 1fr;gap:12px;padding:16px}
  .prop-actions{grid-column:1 / -1;width:100%;justify-content:flex-end;flex-wrap:wrap}
  .prop-price{flex:1 1 calc(33.333% - 8px);text-align:center;min-width:90px}
}
@media(max-width:480px){
  .prop-card{grid-template-columns:1fr}
  .prop-icon{display:none}
  .prop-actions{grid-column:1}
}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>My Properties</h2>
    <p>Manage all your hotel listings on StayZio</p>
  </div>
  <a href="{{ route('vendor.properties.create') }}" class="btn btn-primary">
    <i class="ti ti-plus"></i> Add New Property
  </a>
</div>

@if(Session::has('success'))
<div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
@endif

@if($properties->count() > 0)

  {{-- Stats --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px">
    <div style="background:var(--navy2,#fff);border:1px solid var(--border,#e8eaed);border-radius:10px;padding:14px;text-align:center">
      <div style="font-size:24px;font-weight:900;color:var(--text)">{{ $properties->count() }}</div>
      <div style="font-size:11px;color:var(--muted);font-weight:600">Total Properties</div>
    </div>
    <div style="background:var(--navy2,#fff);border:1px solid var(--border,#e8eaed);border-radius:10px;padding:14px;text-align:center">
      <div style="font-size:24px;font-weight:900;color:#059669">{{ $properties->where('status','live')->count() }}</div>
      <div style="font-size:11px;color:var(--muted);font-weight:600">Live</div>
    </div>
    <div style="background:var(--navy2,#fff);border:1px solid var(--border,#e8eaed);border-radius:10px;padding:14px;text-align:center">
      <div style="font-size:24px;font-weight:900;color:#d97706">{{ $properties->where('status','pending')->count() }}</div>
      <div style="font-size:11px;color:var(--muted);font-weight:600">Pending Review</div>
    </div>
  </div>

  @foreach($properties as $prop)
  @php $badge = $prop->status_badge; @endphp
  <div class="prop-card">
    <div class="prop-icon"><i class="ti ti-building"></i></div>
    <div class="prop-info">
      <div class="prop-name">{{ $prop->hotel_name }}</div>
      <div class="prop-meta">
        <span><i class="ti ti-map-pin"></i> {{ $prop->city }}</span>
        <span><i class="ti ti-bed"></i> {{ $prop->room_type }} · {{ $prop->total_rooms }} rooms</span>
        <span><i class="ti ti-home"></i> {{ ucfirst($prop->property_type) }}</span>
        <span><i class="ti ti-calendar"></i> {{ $prop->created_at->format('d M Y') }}</span>
      </div>
      <div class="prop-prices">
        <div class="prop-price"><strong>₹{{ number_format($prop->price_3hrs) }}</strong> / 3hrs</div>
        <div class="prop-price"><strong>₹{{ number_format($prop->price_6hrs) }}</strong> / 6hrs</div>
        <div class="prop-price"><strong>₹{{ number_format($prop->price_fullday) }}</strong> / Full Day</div>
      </div>
      @if($prop->status === 'rejected' && $prop->rejection_reason)
      <div style="margin-top:8px;padding:8px 12px;background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.2);border-radius:6px;font-size:11px;color:#dc2626">
        <strong>Rejection reason:</strong> {{ $prop->rejection_reason }}
      </div>
      @endif
    </div>
    <div class="prop-actions">
      <span class="prop-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['color'] }}20">
        {{ $badge['label'] }}
      </span>
      @if($prop->isLive())
      <a href="{{ route('vendor.properties.edit_prices', $prop->id) }}" class="btn btn-secondary btn-xs" title="Edit Prices">
        <i class="ti ti-currency-rupee"></i>
      </a>
      @endif
      <a href="{{ route('vendor.properties.show', $prop->id) }}" class="btn btn-secondary btn-xs" title="View Details">
        <i class="ti ti-eye"></i>
      </a>
    </div>
  </div>
  @endforeach

@else
  <div class="empty-state">
    <i class="ti ti-building-community"></i>
    <div style="font-size:16px;font-weight:700;color:var(--text,#0c0c0e);margin-bottom:8px">No properties yet</div>
    <p>Register your first property to start receiving bookings on StayZio.</p>
    <a href="{{ route('vendor.properties.create') }}" class="btn btn-primary" style="margin-top:12px">
      <i class="ti ti-plus"></i> Register Your First Property
    </a>
  </div>
@endif
@endsection
