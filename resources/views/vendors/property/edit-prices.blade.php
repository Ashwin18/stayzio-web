@extends('vendors.layout')
@section('section', 'Properties')
@section('page', 'Edit Prices')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Edit Prices — {{ $property->hotel_name }}</h2>
    <p>Update room pricing for your property</p>
  </div>
  <a href="{{ route('vendor.properties.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
</div>

@if(Session::has('success'))
<div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
@endif

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('vendor.properties.update_prices', $property->id) }}">
      @csrf
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;max-width:700px">
        <div style="background:rgba(227,30,36,.04);border:1.5px solid rgba(227,30,36,.15);border-radius:12px;padding:20px;text-align:center">
          <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">3 Hours</div>
          <div style="position:relative">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;font-weight:700;color:#e31e24">₹</span>
            <input type="number" name="price_3hrs" value="{{ $property->price_3hrs }}"
              style="width:100%;padding:12px 12px 12px 30px;border:1.5px solid #e8eaed;border-radius:8px;font-size:20px;font-weight:800;color:#e31e24;text-align:center;font-family:'Poppins',sans-serif" required min="0">
          </div>
        </div>
        <div style="background:rgba(227,30,36,.04);border:1.5px solid rgba(227,30,36,.15);border-radius:12px;padding:20px;text-align:center">
          <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">6 Hours</div>
          <div style="position:relative">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;font-weight:700;color:#e31e24">₹</span>
            <input type="number" name="price_6hrs" value="{{ $property->price_6hrs }}"
              style="width:100%;padding:12px 12px 12px 30px;border:1.5px solid #e8eaed;border-radius:8px;font-size:20px;font-weight:800;color:#e31e24;text-align:center;font-family:'Poppins',sans-serif" required min="0">
          </div>
        </div>
        <div style="background:rgba(227,30,36,.04);border:1.5px solid rgba(227,30,36,.15);border-radius:12px;padding:20px;text-align:center">
          <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">Full Day</div>
          <div style="position:relative">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;font-weight:700;color:#e31e24">₹</span>
            <input type="number" name="price_fullday" value="{{ $property->price_fullday }}"
              style="width:100%;padding:12px 12px 12px 30px;border:1.5px solid #e8eaed;border-radius:8px;font-size:20px;font-weight:800;color:#e31e24;text-align:center;font-family:'Poppins',sans-serif" required min="0">
          </div>
        </div>
      </div>
      <div style="margin-top:8px;padding:10px 14px;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);border-radius:8px;font-size:11px;color:#d97706">
        <i class="ti ti-info-circle"></i> Price changes apply to future bookings. Existing confirmed bookings are not affected.
        Updated prices also sync to the hourly inventory system.
      </div>
      <div style="margin-top:16px">
        <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Update Prices</button>
      </div>
    </form>
  </div>
</div>
@endsection
