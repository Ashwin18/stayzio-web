{{-- Refund Policy & Cancel — StayZio (Brevistay-style flat policy) --}}
@php
  $now = \Carbon\Carbon::now();
  $checkInDt = \Carbon\Carbon::parse($b->check_in_date.' '.$b->check_in_time);
  $hoursLeft = $now->diffInHours($checkInDt, false);
  $minutesSinceBooking = \Carbon\Carbon::parse($b->created_at)->diffInMinutes($now);
  $canCancel = $b->order_status !== 'cancelled' && $checkInDt->isFuture();
  $isEligibleForRefund = $hoursLeft >= 24 || $minutesSinceBooking <= 15;
@endphp

@if($b->order_status === 'cancelled')
<div class="bkd-card" style="border-color:#fecaca;background:#fff5f5">
  <div class="bkd-card-body">
    <div style="display:flex;align-items:center;gap:10px;color:#dc2626;font-weight:800;margin-bottom:8px">
      <i class="fas fa-ban"></i> Booking Cancelled
    </div>
    @if($b->cancellation_refund_amount > 0)
    <div style="font-size:12px;color:#7a8394;line-height:1.6">
      Refund of <b style="color:#16a34a">{{ $fmt($b->cancellation_refund_amount) }}</b>
      will reflect in your account within <b>5-7 business days</b>.
    </div>
    @else
    <div style="font-size:12px;color:#7a8394;line-height:1.6">
      No refund applicable as per our cancellation policy.
    </div>
    @endif
  </div>
</div>
@elseif($canCancel)
<div class="bkd-card">
  <div class="bkd-card-hdr"><h3>Cancellation Policy</h3></div>
  <div class="bkd-card-body">
    <div style="background:{{ $isEligibleForRefund ? '#f0fdf4' : '#fef2f2' }};border-left:3px solid {{ $isEligibleForRefund ? '#16a34a' : '#dc2626' }};padding:10px 12px;border-radius:6px;font-size:12px;color:#374151;line-height:1.7;margin-bottom:12px">
      <b style="color:{{ $isEligibleForRefund ? '#16a34a' : '#dc2626' }};display:block;margin-bottom:4px">
        @if($isEligibleForRefund)
          <i class="fas fa-check-circle"></i> Eligible for Full Refund — {{ $fmt($b->grand_total) }}
        @else
          <i class="fas fa-times-circle"></i> Not Eligible for Refund
        @endif
      </b>
      @if($hoursLeft >= 24)
        Cancellation is more than 24 hours before check-in — full refund applies.
      @elseif($minutesSinceBooking <= 15)
        You booked just {{ $minutesSinceBooking }} minute(s) ago. Cancelling within 15 minutes of booking gets you a full refund.
      @else
        Cancellation is within 24 hours of check-in, and the 15-minute grace window has passed.
      @endif
    </div>

    <div style="font-size:11px;color:#7a8394;line-height:1.7;padding:10px 12px;background:#f9f7f6;border-radius:6px;margin-bottom:12px">
      <b style="color:#1a1a2e;display:block;margin-bottom:4px">Refund Rules</b>
      &bull; Full refund if cancelled at least <b>24 hours before check-in</b><br>
      &bull; Full refund if cancelled within <b>15 minutes of booking</b><br>
      &bull; No refund for no-shows or mid-stay cancellations<br>
      &bull; Eligible refunds reflect within <b>5-7 business days</b>
    </div>

    <form action="{{ route('frontend.room_booking.cancel_booking') }}" method="POST"
          onsubmit="return confirm('@if($isEligibleForRefund)You will receive a full refund of {{ $fmt($b->grand_total) }}. Continue?@elseNo refund will be issued as per policy. Are you sure you want to cancel?@endif')">
      @csrf
      <input type="hidden" name="booking_id" value="{{ $b->id }}">
      <button type="submit" style="width:100%;padding:11px;background:#fee2e2;color:#dc2626;border:1.5px solid #fecaca;border-radius:10px;font-size:13px;font-weight:800;cursor:pointer">
        <i class="fas fa-times-circle"></i> Cancel This Booking
      </button>
    </form>
  </div>
</div>
@endif
