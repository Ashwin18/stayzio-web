{{-- Cancellation Policy & Cancel Button --}}
@php
  $_cNow = \Carbon\Carbon::now();
  $_cCheckIn = \Carbon\Carbon::parse($b->check_in_date.' '.$b->check_in_time);
  $_cHours = $_cNow->diffInHours($_cCheckIn, false);
  $_cMins = \Carbon\Carbon::parse($b->created_at)->diffInMinutes($_cNow);
  $_cCan = ($b->order_status ?? '') !== 'cancelled' && $_cCheckIn->isFuture();
  $_cElig = $_cHours >= 24 || $_cMins <= 15;
@endphp

@if(($b->order_status ?? '') === 'cancelled')
<div class="bdv2-card" style="border-color:#fecaca;background:#fff5f5">
  <div class="bdv2-card-body">
    <div style="display:flex;align-items:center;gap:10px;color:#dc2626;font-weight:800;margin-bottom:8px;font-size:14px">
      <i class="fas fa-ban"></i> Booking Cancelled
    </div>
    @if(($b->cancellation_refund_amount ?? 0) > 0)
    <div style="font-size:12px;color:#7a8394;line-height:1.6">
      Refund of <b style="color:#16a34a">{{ $fmt($b->cancellation_refund_amount) }}</b>
      will reflect within <b>5-7 business days</b>.
    </div>
    @else
    <div style="font-size:12px;color:#7a8394;line-height:1.6">No refund applicable as per policy.</div>
    @endif
  </div>
</div>
@elseif($_cCan)
<div class="bdv2-card">
  <div class="bdv2-card-hdr"><h3>Cancellation Policy</h3></div>
  <div class="bdv2-card-body">
    <div style="background:{{ $_cElig?'#f0fdf4':'#fef2f2' }};border-left:3px solid {{ $_cElig?'#16a34a':'#dc2626' }};padding:11px 13px;border-radius:7px;font-size:12px;line-height:1.7;margin-bottom:12px">
      <b style="color:{{ $_cElig?'#16a34a':'#dc2626' }};display:block;margin-bottom:4px;font-size:13px">
        {!! $_cElig ? '<i class="fas fa-check-circle"></i> Eligible for Full Refund — '.$fmt($b->grand_total) : '<i class="fas fa-times-circle"></i> Not Eligible for Refund' !!}
      </b>
      {!! $_cHours>=24 ? 'Cancellation is 24+ hours before check-in — full refund applies.' : ($_cMins<=15 ? 'Booked '.$_cMins.' min ago. Within 15 min grace window — full refund.' : 'Within 24h of check-in, 15-min grace window passed — no refund.') !!}
    </div>
    <div style="font-size:11px;color:#7a8394;line-height:1.7;padding:10px 12px;background:#f9f7f6;border-radius:7px;margin-bottom:12px">
      <b style="color:#1a1a2e;display:block;margin-bottom:4px;font-size:11px">Refund Rules</b>
      &bull; Full refund if cancelled <b>24+ hours before check-in</b><br>
      &bull; Full refund if cancelled within <b>15 minutes of booking</b><br>
      &bull; No refund for no-shows or mid-stay cancellations<br>
      &bull; Eligible refunds reflect within <b>5-7 business days</b>
    </div>
    @php
      $_confirmMsg = $_cElig
        ? 'You will receive a full refund of '.$fmt($b->grand_total).'. Continue?'
        : 'No refund will be issued as per policy. Are you sure you want to cancel?';
    @endphp
    <form action="{{ route('frontend.room_booking.cancel_booking') }}" method="POST"
          onsubmit="return confirm('{{ addslashes($_confirmMsg) }}')">
      @csrf
      <input type="hidden" name="booking_id" value="{{ $b->id }}">
      <button type="submit" style="width:100%;padding:11px;background:#fee2e2;color:#dc2626;border:1.5px solid #fecaca;border-radius:10px;font-size:13px;font-weight:800;cursor:pointer">
        <i class="fas fa-times-circle"></i> Cancel This Booking
      </button>
    </form>
  </div>
</div>
@else
<div class="bdv2-card" style="border-color:#e5e7eb">
  <div class="bdv2-card-hdr"><h3>Cancellation</h3></div>
  <div class="bdv2-card-body">
    <div style="background:#f9fafb;border-left:3px solid #6b7280;padding:11px 13px;border-radius:7px;font-size:12px;line-height:1.6;color:#374151">
      <b style="color:#374151;display:block;margin-bottom:4px;font-size:13px"><i class="fas fa-clock"></i> Cancellation Window Closed</b>
      Your check-in time has already passed. As per our policy, bookings cannot be cancelled or refunded after check-in.
    </div>
  </div>
</div>
@endif
