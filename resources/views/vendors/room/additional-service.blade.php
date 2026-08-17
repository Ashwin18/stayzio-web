@extends('vendors.layout')
@section('section','Rooms')
@section('page','Additional Services')

@section('content')
@php
  $dContent = \App\Models\RoomContent::where('room_id',$room_id)->where('language_id',$defaultLang->id)->first();
  $hasservice = json_decode($room->additional_service, true) ?? [];
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Additional Services</h2>
    <p>{{ optional($dContent)->title ?? 'Room' }} — select add-on services for this room</p>
  </div>
  <div class="page-hdr-actions">
    @if($dContent)
    <a href="{{ route('frontend.room.details',['slug'=>$dContent->slug,'id'=>$room_id]) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="ti ti-external-link"></i> Preview</a>
    @endif
    <a href="{{ route('vendor.room_management.rooms',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>

<div style="max-width:800px">
  <div class="card">
    <div class="card-hdr">
      <div class="card-title">Select Services & Set Prices</div>
      <div class="card-sub">Check a service to enable it, then set the price per booking</div>
    </div>
    <div class="card-body">
      @if(session('success'))
      <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:var(--green);border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px">
        <i class="ti ti-circle-check"></i> {{ session('success') }}
      </div>
      @endif

      <form id="commonForm" action="{{ route('vendor.room_management.update_additional_service',$room_id) }}" method="POST">
        @csrf
        <input type="hidden" name="listing_id" value="{{ $room_id }}">

        @if(count($services) > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:10px;margin-bottom:18px">
          @foreach($services as $svc)
          @php $checked = array_key_exists($svc->id, $hasservice); $price = $checked ? $hasservice[$svc->id] : ''; @endphp
          <div style="display:flex;align-items:center;gap:12px;background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px 14px;transition:border-color .15s" id="svc-row-{{ $svc->id }}">
            <input type="checkbox" name="checkbox[]" id="svc_{{ $svc->id }}" value="{{ $svc->id }}"
              {{ $checked ? 'checked' : '' }}
              onchange="document.getElementById('price_wrap_{{ $svc->id }}').style.opacity=this.checked?'1':'0.4'"
              style="width:16px;height:16px;flex-shrink:0;accent-color:var(--red)">
            <label for="svc_{{ $svc->id }}" style="flex:1;font-size:13px;font-weight:500;color:var(--text);cursor:pointer;margin:0">
              {{ $svc->title }}
            </label>
            <div id="price_wrap_{{ $svc->id }}" style="display:flex;align-items:center;gap:6px;opacity:{{ $checked?'1':'0.4' }}">
              <span style="font-size:11px;color:var(--muted)">{{ $settings->base_currency_symbol ?? '₹' }}</span>
              <input type="number" name="price_{{ $svc->id }}" value="{{ $price }}" placeholder="0"
                style="width:80px;background:var(--navy2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:12px;text-align:center;outline:none"
                min="0" step="0.01">
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div style="text-align:center;padding:32px;color:var(--muted);font-size:13px">
          <i class="ti ti-package" style="font-size:32px;display:block;margin-bottom:10px"></i>
          No additional services configured yet. Admin needs to add services first.
        </div>
        @endif

        <div style="display:flex;gap:10px;padding-top:8px;border-top:1px solid var(--border)">
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Update Services</button>
          <a href="{{ route('vendor.room_management.rooms',['language'=>$defaultLang->code]) }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
