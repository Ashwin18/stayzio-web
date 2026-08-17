@extends('admin.layout')
@section('section','Bookings')
@section('page','Booking ID Settings')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Booking ID Settings</h2>
    <p>Control the prefix and numbering used for new booking IDs (e.g. SZ01, SZ02...)</p>
  </div>
</div>

@if(session('success'))
<div style="padding:12px 16px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);border-radius:8px;margin-bottom:16px;font-size:13px;color:#059669">
  <i class="ti ti-check"></i> {{ session('success') }}
</div>
@endif

<div class="card" style="max-width:520px">
  <div class="card-body">
    <form action="{{ route('admin.settings.booking_id.update') }}" method="POST">
      @csrf
      <div class="fg" style="margin-bottom:16px">
        <label class="flabel">Prefix</label>
        <input type="text" name="booking_id_prefix" class="fc" maxlength="10" value="{{ old('booking_id_prefix', $settings->booking_id_prefix ?? 'SZ') }}" required>
        <div style="font-size:11px;color:var(--muted);margin-top:4px">Letters/numbers only, e.g. "SZ"</div>
        @error('booking_id_prefix')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
      </div>
      <div class="fg" style="margin-bottom:8px">
        <label class="flabel">Next Booking Number</label>
        <input type="number" name="booking_id_next_number" class="fc" min="1" value="{{ old('booking_id_next_number', $settings->booking_id_next_number ?? 1) }}" required>
        <div style="font-size:11px;color:var(--muted);margin-top:4px">
          The very next booking made on the site will be assigned this number.
          Increasing it skips numbers; existing past bookings are never affected.
        </div>
        @error('booking_id_next_number')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
      </div>

      <div style="padding:12px 14px;background:var(--surface,#f8f9fb);border-radius:8px;margin:16px 0;font-size:13px">
        <strong>Preview:</strong>
        <span id="bidPreview" style="color:var(--red,#e31e24);font-weight:700">SZ01</span>
        will be the next Booking ID.
      </div>

      <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
    </form>
  </div>
</div>

<script>
function updateBidPreview() {
  var prefix = document.querySelector('[name="booking_id_prefix"]').value || 'SZ';
  var num = parseInt(document.querySelector('[name="booking_id_next_number"]').value || 1);
  var padded = num < 10 ? '0' + num : '' + num;
  document.getElementById('bidPreview').textContent = prefix.toUpperCase() + padded;
}
document.querySelector('[name="booking_id_prefix"]').addEventListener('input', updateBidPreview);
document.querySelector('[name="booking_id_next_number"]').addEventListener('input', updateBidPreview);
updateBidPreview();
</script>
@endsection