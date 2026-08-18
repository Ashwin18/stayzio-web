@extends('vendors.layout')
@section('section', 'Properties')
@section('page', $property->hotel_name)

@section('content')
@php $badge = $property->status_badge; @endphp
<style>
@media(max-width:768px){
  .property-detail-grid,.property-price-grid{grid-template-columns:1fr!important}
}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>{{ $property->hotel_name }}</h2>
    <p>Submitted {{ $property->created_at->format('d M Y, h:i A') }}</p>
  </div>
  <div style="display:flex;align-items:center;gap:10px">
    <span style="padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700;background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['color'] }}20">{{ $badge['label'] }}</span>
    @if($property->isLive())
    <a href="{{ route('vendor.properties.edit_prices', $property->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-currency-rupee"></i> Edit Prices</a>
    @endif
    <a href="{{ route('vendor.properties.index') }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>

@if($property->status === 'rejected' && $property->rejection_reason)
<div style="padding:12px 16px;background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.2);border-radius:10px;font-size:12px;color:#dc2626;margin-bottom:14px">
  <strong><i class="ti ti-alert-triangle"></i> Rejected:</strong> {{ $property->rejection_reason }}
</div>
@endif

@if($property->status === 'pending')
<div style="padding:12px 16px;background:rgba(217,119,6,.06);border:1px solid rgba(217,119,6,.2);border-radius:10px;font-size:12px;color:#d97706;margin-bottom:14px">
  <strong><i class="ti ti-clock"></i> Pending Review</strong> — Admin will review your property and get back to you shortly.
</div>
@endif

<div class="card">
  <div class="card-body">
    <div class="property-detail-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
      @foreach([
        ['Hotel Name', $property->hotel_name],
        ['City', $property->city],
        ['Pincode', $property->pincode],
        ['Address', $property->address],
        ['Mobile', $property->mobile_number],
        ['Reception', $property->reception_number ?? '—'],
        ['Property Type', ucfirst($property->property_type)],
        ['Room Type', $property->room_type],
        ['Total Rooms', $property->total_rooms],
        ['Amenities', implode(', ', $property->amenities_list) ?: '—'],
        ['Same City Couples', $property->allow_same_city_couples === 'yes' ? '✅ Yes' : '❌ No'],
        ['Outstation Couples', $property->allow_outstation_couples === 'yes' ? '✅ Yes' : '❌ No'],
        ['Smoking/Drinking', $property->allow_smoking_drinking === 'yes' ? '✅ Yes' : '❌ No'],
        ['Food Facility', $property->food_facility === 'yes' ? '✅ Yes' : '❌ No'],
        ['Owner / Manager', $property->owner_name . ' (' . $property->owner_contact . ')'],
        ['Owner Email', $property->owner_email ?? '—'],
        ['GSTIN', $property->gstin ?? '—'],
        ['Bank', ($property->bank_name ?? '—') . ' - ' . ($property->account_number ?? '')],
      ] as [$label, $value])
      <div style="padding:10px 14px;background:var(--navy3,#f8fafc);border:1px solid var(--border,#e8eaed);border-radius:8px;{{ str_contains($label,'Address') ? 'grid-column:span 2' : '' }}">
        <div style="font-size:9px;font-weight:700;color:var(--muted,#64748b);text-transform:uppercase;margin-bottom:3px">{{ $label }}</div>
        <div style="font-size:13px;font-weight:700;color:var(--text,#0c0c0e)">{{ $value }}</div>
      </div>
      @endforeach
    </div>

    <div class="property-price-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-top:14px">
      <div style="background:rgba(0,184,184,.06);border:1.5px solid rgba(0,184,184,.20);border-radius:10px;padding:16px;text-align:center">
        <div style="font-size:22px;font-weight:900;color:#00A6A6">₹{{ number_format($property->price_3hrs) }}</div>
        <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-top:4px">3 Hours</div>
      </div>
      <div style="background:rgba(0,184,184,.06);border:1.5px solid rgba(0,184,184,.20);border-radius:10px;padding:16px;text-align:center">
        <div style="font-size:22px;font-weight:900;color:#00A6A6">₹{{ number_format($property->price_6hrs) }}</div>
        <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-top:4px">6 Hours</div>
      </div>
      <div style="background:rgba(0,184,184,.06);border:1.5px solid rgba(0,184,184,.20);border-radius:10px;padding:16px;text-align:center">
        <div style="font-size:22px;font-weight:900;color:#00A6A6">₹{{ number_format($property->price_fullday) }}</div>
        <div style="font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-top:4px">Full Day</div>
      </div>
    </div>
  </div>
</div>
@endsection
