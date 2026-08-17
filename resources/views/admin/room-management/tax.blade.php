@extends('admin.layout')
@section('section','Finance')
@section('page','Commission Config')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Commission Config</h2><p>Configure tax and platform commission rates</p></div>
</div>
<div style="max-width:600px">
  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-percentage" style="color:var(--green)"></i>  Tax & Commission</div></div>
    <div class="card-body">
      <form action="{{ route('admin.room_management.update_tax_amount') }}" method="POST">
        @csrf
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Hotel Tax Amount (%)</label>
          <input type="number" step="0.001" name="hotel_tax_amount" class="fc"
                 value="{{ $data->hotel_tax_amount ?? 0 }}" placeholder="e.g. 18">
          <div class="fc-hint">Applied on all bookings as a percentage of room rate</div>
          @error('hotel_tax_amount')<p class="fs11 text-red">{{ $message }}</p>@enderror
        </div>
        <div style="background:var(--navy3);border-radius:8px;padding:12px;margin-bottom:14px">
          <div class="fw6 fs12" style="margin-bottom:8px"><i class="ti ti-info-circle" style="color:var(--blue)"></i>  How it works</div>
          <div class="fs11 text-muted" style="line-height:1.7">
            Room price → + Tax (%) → − Coupon = Grand total.<br>
            Per-hotel commission rates are configured in <a href="{{ url('admin/hotel-management/hourly-inventory') }}" style="color:var(--blue)">Hourly Inventory</a>.
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">
          <i class="ti ti-device-floppy"></i> Save Configuration
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
